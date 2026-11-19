<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $inventory->inventory_code }} Shelf Inventory</title>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 9pt; color: #1f2937; }
        .header { width: 100%; border-bottom: 2px solid #006439; padding-bottom: 8px; margin-bottom: 12px; }
        .title { color: #006439; font-size: 18pt; font-weight: bold; margin: 0; }
        .meta { font-size: 8pt; text-align: right; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #006439; color: white; text-transform: uppercase; font-size: 8pt; padding: 7px; border: 1px solid #006439; }
        td { padding: 6px 7px; border: 1px solid #d1d5db; vertical-align: middle; }
        tr { page-break-inside: avoid; }
        .group td { background: #eff6ff; color: #1e3a8a; font-weight: bold; border: 1px solid #bfdbfe; }
        .check { width: 18px; height: 18px; border: 1px solid #111827; display: inline-block; }
        .location { font-weight: bold; }
        .footer { margin-top: 10px; font-size: 8pt; color: #6b7280; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <p class="title">Shelf Inventory</p>
                <div>{{ $inventory->librarian?->library?->name ?? 'Library' }}</div>
            </td>
            <td class="meta">
                <div><strong>Reference:</strong> {{ $inventory->inventory_code }}</div>
                <div><strong>Inventory date:</strong> {{ $inventory->created_at->format('F j, Y h:i A') }}</div>
                <div><strong>Created by:</strong> {{ $inventory->librarian?->user?->name->full }}</div>
                <div><strong>Total copies:</strong> {{ $lines->count() }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 4%">No.</th>
                <th style="width: 12%">Accession Number</th>
                <th>Title</th>
                <th style="width: 14%">Item Type</th>
                <th style="width: 18%">Shelf / Location</th>
                <th style="width: 12%">Status</th>
                <th style="width: 8%">Found</th>
                <th style="width: 8%">Missing</th>
            </tr>
        </thead>
        <tbody>
            @php($groupKey = '')
            @foreach ($lines as $index => $line)
                @php($itemType = $line->accession?->item?->itemType?->name ?? 'Unclassified')
                @php($title = $line->accession?->item?->title ?? 'Untitled')
                @php($key = $itemType.'|'.$title)
                @if ($key !== $groupKey)
                    @php($groupKey = $key)
                    <tr class="group">
                        <td colspan="8">{{ $itemType }} · {{ $title }} · Qty {{ $lines->filter(fn($copy) => ($copy->accession?->item?->itemType?->name ?? 'Unclassified') === $itemType && ($copy->accession?->item?->title ?? 'Untitled') === $title)->count() }}</td>
                    </tr>
                @endif
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $line->accession?->accession_number ?? '—' }}</td>
                    <td>{{ $title }}</td>
                    <td>{{ $itemType }}</td>
                    <td class="location">{{ $line->currentSection?->name ?? $line->accession?->section?->name ?? 'Unshelved' }}</td>
                    <td>{{ ucfirst($line->current_status) }}</td>
                    <td style="text-align: center"><span class="check"></span></td>
                    <td style="text-align: center"><span class="check"></span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Record findings manually and keep this copy with the inventory record.
    </div>
</body>
</html>
