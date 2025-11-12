<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
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

        .party-header {
            background-color: #f8f9fa;
            padding: 8px 10px;
            border-left: 4px solid #007bff;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }

        .party-table {
            box-shadow: 0 0 3px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }

        .table-secondary th {
            background-color: #e2e3e5;
        }

        .table-primary th {
            background-color: #cce7ff;
        }

        .table-success th {
            background-color: #d4edda;
        }

        .grand-total-label {
            font-size: 12px;
            font-weight: bold;
        }

        .grand-total-value {
            font-size: 12px;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }

        .signature-line {
            margin-top: 30px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 150px;
        }

        /* Landscape orientation */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Krishna Minerals Sales Statement</h1>
        <p>
            @if(!empty($filterValues['date_from']) && !empty($filterValues['date_to']))
                Period: {{ date('d-m-Y', strtotime($filterValues['date_from'])) }} to {{ date('d-m-Y', strtotime($filterValues['date_to'])) }}
            @elseif(!empty($filterValues['date_from']))
                From: {{ date('d-m-Y', strtotime($filterValues['date_from'])) }}
            @elseif(!empty($filterValues['date_to']))
                To: {{ date('d-m-Y', strtotime($filterValues['date_to'])) }}
            @endif
        </p>
        @if(!empty($filterValues['party_name']))
        <p>Party: {{ $filterValues['party_name'] }}</p>
        @endif
        @if(!empty($filterValues['material']))
        <p>Material: {{ $filterValues['material'] }}</p>
        @endif
    </div>

    <div class="table-responsive">
        @foreach($partyWiseSales as $partyData)
        <div class="party-section">
            <div class="party-header">Party: {{ $partyData['party']->name ?? 'Under Pending Load' }}</div>
            
            <table class="party-table">
                <thead>
                    <tr class="border-b">
                        <th>Challan</th>
                        <th>Date</th>
                        <th>Vehicle Number</th>
                        <th>Royalty Name</th>
                        <th>Royalty Number</th>
                        <th>Place</th>
                        <th>Material</th>
                        <th>Net Weight</th>
                        <th>Rate</th>
                        <th>GST(%)</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $partyNetWeight = 0;
                        $partyPartyWeight = 0;
                        $partyAmount = 0;
                    @endphp
                    @foreach($partyData['challanWiseData'] as $challanId => $challanData)
                        @forelse ($challanData['records'] as $sale)
                        <tr>
                            <td>{{ $challanData['challanNumber'] }}</td>
                            <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                            <td>{{ $sale->vehicle->name ?? '-' }}</td>
                            <td>{{ $sale->royalty->name ?? '-' }}</td>
                            <td>{{ $sale->royalty_number ?? '-' }}</td>
                            <td>{{ $sale->place->name ?? '-' }}</td>
                            <td>{{ $sale->material->name ?? '-' }}</td>
                            @php
                                // Calculate display weight: party weight if available and not zero, otherwise net weight
                                $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
                                $partyNetWeight += $displayWeight;
                                $partyAmount += $sale->amount ?? 0;
                            @endphp
                            <td class="text-end">{{ number_format($displayWeight, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->rate ?? 0, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->gst ?? 0, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->amount ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No Record Found</td>
                        </tr>
                        @endforelse
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <th colspan="7" class="text-end">Party Total:</th>
                        <th class="text-end">{{ number_format($partyNetWeight, 2) }}</th>
                        <th></th>
                        <th></th>
                        <th class="text-end">{{ number_format($partyAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endforeach
        
        <!-- @if(count($partyWiseSales) > 0 && empty(request()->challan_id))
        <div class="grand-total-section">
            <table class="grand-total-table">
                <tfoot>
                    <tr class="table-success">
                        <th colspan="7" class="text-end grand-total-label">Grand Total:</th>
                        <th class="text-end grand-total-value">{{ number_format($grandTotalDisplayWeight, 2) }}</th>
                        <th class="text-end grand-total-value">{{ number_format($grandTotalAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif -->
    </div>

    <div class="footer">
        <div class="signature-line">Authorized Signature</div>
    </div>
</body>
</html>