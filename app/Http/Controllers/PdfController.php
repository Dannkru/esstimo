<?php

namespace App\Http\Controllers;

use App\Livewire\QuoteSummary;
use App\Services\Quote\QuoteSessionManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends Controller
{
    /**
     * Pobierz PDF kosztorysu materiałów (lista zakupów z kalkulatora materiałów).
     */
    public function quoteDownload(): Response
    {
        $manager = app(QuoteSessionManager::class);
        $items = $manager->getItems();
        $aggregated = $manager->aggregateMaterials();
        $mergedLabels = QuoteSummary::mergedMaterialLabels();

        if (empty($items)) {
            return redirect()->route('materials.summary')->with('message', 'Brak pozycji do wydruku.');
        }

        $pdf = Pdf::loadView('pdf.quote', [
            'items' => $items,
            'aggregated' => $aggregated,
            'mergedLabels' => $mergedLabels,
        ]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('kosztorys-materialow-estimo.pdf');
    }

    public function download(Request $request)
    {
        $data = $request->validate([
            'services' => 'required|array|max:1000',
            'services.*.id' => 'required|integer|exists:services,id',
            'services.*.name' => 'required|string|max:255',
            'services.*.unit' => 'required|string|max:20',
            'services.*.quantity' => 'required|numeric|min:0|max:1000000',
            'services.*.price' => 'required|numeric|min:0|max:1000000',
            'services.*.total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // Weryfikacja czy total jest zgodne z sumą (z tolerancją 0.01 dla zaokrągleń)
        $calculatedTotal = collect($data['services'])->sum(function($service) {
            return ($service['quantity'] ?? 0) * ($service['price'] ?? 0);
        });

        if (abs($calculatedTotal - $data['total']) > 0.01) {
            \Log::warning('Próba generowania PDF z nieprawidłową sumą', [
                'calculated_total' => $calculatedTotal,
                'provided_total' => $data['total'],
                'difference' => abs($calculatedTotal - $data['total']),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            abort(422, 'Suma nie jest zgodna z danymi usług');
        }

        try {
            $pdf = Pdf::loadView('pdf.estimate', [
                'selectedServicesForPrint' => $data['services'],
                'total' => $data['total'],
            ])->setOption('defaultFont', 'DejaVu Sans')
              ->setOption('isHtml5ParserEnabled', true)
              ->setOption('isRemoteEnabled', false);

            $filename = 'wycena-estimo-' . now()->format('Y-m-d-His') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Błąd generowania PDF: ' . $e->getMessage());
            return back()->with('error', 'Błąd podczas generowania PDF. Spróbuj ponownie.');
        }
    }
}

