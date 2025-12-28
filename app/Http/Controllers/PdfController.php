<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function download(Request $request)
    {
        $data = $request->validate([
            'services' => 'required|array',
            'total' => 'required|numeric',
        ]);

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
            return back()->with('error', 'Błąd podczas generowania PDF: ' . $e->getMessage());
        }
    }
}

