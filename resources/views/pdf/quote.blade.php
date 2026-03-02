<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Kosztorys materiałów – Estimo</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; color: #1c1917; margin: 0; padding: 16px; }
        h1 { font-size: 14pt; margin: 0 0 4px 0; }
        .meta { font-size: 9pt; color: #57534e; margin-bottom: 16px; }
        h2 { font-size: 11pt; margin: 16px 0 8px 0; border-bottom: 1px solid #d6d3d1; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { text-align: left; padding: 6px 8px; border: 1px solid #e7e5e4; }
        th { background: #f5f5f4; font-weight: 600; }
        .col-qty { width: 80px; text-align: right; }
        .col-notes { width: 60px; text-align: center; }
        .room-block { margin-bottom: 14px; break-inside: avoid; }
        .room-title { font-weight: 600; margin-bottom: 4px; }
        .room-category { font-size: 9pt; color: #57534e; margin-bottom: 6px; }
        .notes-cell { background: #fafaf9; }
    </style>
</head>
<body>
    <h1>Kosztorys materiałów</h1>
    <p class="meta">Wygenerowano: {{ now()->format('d.m.Y H:i') }} · Estimo</p>

    <h2>Szczegóły – rozbicie na pomieszczenia</h2>
    @foreach($items as $item)
        @php $labels = \App\Livewire\QuoteSummary::labelsForCategory($item['category_key'] ?? 'other'); @endphp
        <div class="room-block">
            <div class="room-title">{{ $item['room_name'] ?? 'Bez nazwy' }}</div>
            <div class="room-category">{{ $item['category'] ?? '' }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Materiał</th>
                        <th class="col-qty">Ilość</th>
                        <th class="col-notes">Notatki</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($item['materials'] ?? []) as $key => $val)
                        @if($key !== 'meta' && is_numeric($val) && ($val > 0 || ($val == 0 && in_array($key, ['fuga_kg', 'laczniki']))))
                            <tr>
                                <td>{{ $labels[$key] ?? $key }}</td>
                                <td class="col-qty">{{ \App\Livewire\QuoteSummary::formatValue($key, $val) }}</td>
                                <td class="col-notes notes-cell">□</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <h2>Całkowite zapotrzebowanie na inwestycję</h2>
    <table>
        <thead>
            <tr>
                <th>Materiał</th>
                <th class="col-qty">Suma</th>
                <th class="col-notes">Notatki</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aggregated as $key => $val)
                @if($val > 0 || ($val == 0 && in_array($key, ['fuga_kg', 'laczniki'])))
                    <tr>
                        <td>{{ $mergedLabels[$key] ?? $key }}</td>
                        <td class="col-qty">{{ \App\Livewire\QuoteSummary::formatValue($key, $val) }}</td>
                        <td class="col-notes notes-cell">□</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
