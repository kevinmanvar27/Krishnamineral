<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challan Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .challan-header {
            background-color: #e9ecef;
            padding: 10px;
            border-left: 4px solid #28a745;
            font-weight: bold;
            margin: 10px 0;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 200px;
        }

        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Krishna Minerals Challan Details</h1>
        @if(isset($challanData))
        <div class="challan-header">Challan: {{ $challanData['challanNumber'] }}</div>
        <p>Party: {{ $partyData['party']->name ?? 'N/A' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Vehicle Number</th>
                <th>Royalty Name</th>
                <th>Royalty Number</th>
                <th>Place</th>
                <th>Material</th>
                <th>Net Weight</th>
                <th>Party Weight</th>
                <th>Rate</th>
                <th>GST</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($challanData))
                @forelse ($challanData['records'] as $sale)
                <tr>
                    <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                    <td>{{ $sale->vehicle->name ?? '-' }}</td>
                    <td>{{ $sale->royalty->name ?? '-' }}</td>
                    <td>{{ $sale->royalty_number ?? '-' }}</td>
                    <td>{{ $sale->place->name ?? '-' }}</td>
                    <td>{{ $sale->material->name ?? '-' }}</td>
                    <td class="text-end">{{ number_format($sale->net_weight ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($sale->party_weight ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($sale->rate ?? 0, 2) }}</td>
                    <td class="text-end">{{ number_format($sale->gst ?? 0, 2) }}%</td>
                    <td class="text-end">{{ number_format($sale->amount ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center">No Record Found</td>
                </tr>
                @endforelse
            @endif
        </tbody>
        <tfoot>
            @if(isset($challanData))
            <tr class="total-row">
                <th colspan="6" class="text-end">Challan Total:</th>
                <th class="text-end">{{ number_format($challanData['netWeight'], 2) }}</th>
                <th class="text-end">{{ number_format($challanData['partyWeight'], 2) }}</th>
                <th></th>
                <th></th>
                <th class="text-end">{{ number_format($challanData['amount'], 2) }}</th>
            </tr>
            @endif
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature-line">Authorized Signature</div>
    </div>
</body>
</html>