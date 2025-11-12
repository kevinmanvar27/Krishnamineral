<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carting Statement</title>
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

        /* Landscape orientation */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: right;
            padding: 10px;
            font-size: 10px;
        }
        
        .signature-line {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 5px;
            display: inline-block;
        }
        
        .grand-total-section {
            margin-top: 20px;
        }
        
        .grand-total-table {
            width: 50%;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Krishna Minerals Carting Statement</h1>
        <p>
            @if(!empty($filterValues['date_from']) && !empty($filterValues['date_to']))
                Period: {{ date('d-m-Y', strtotime($filterValues['date_from'])) }} to {{ date('d-m-Y', strtotime($filterValues['date_to'])) }}
            @elseif(!empty($filterValues['date_from']))
                From: {{ date('d-m-Y', strtotime($filterValues['date_from'])) }}
            @elseif(!empty($filterValues['date_to']))
                To: {{ date('d-m-Y', strtotime($filterValues['date_to'])) }}
            @endif
        </p>
        @if(!empty($filterValues['transporter_name']))
        <p>Transporter: {{ $filterValues['transporter_name'] }}</p>
        @endif
        @if(!empty($filterValues['vehicle_number']))
        <p>Vehicle Number: {{ $filterValues['vehicle_number'] }}</p>
        @endif
    </div>

    <div class="content">
        @foreach($transporterWiseSales as $transporterData)
        <div class="party-section">
            <h4 class="party-header">Transporter: {{ $transporterData['transporterName'] ?? 'N/A' }}</h4>
            
            <table class="party-table">
                <thead>
                    <tr class="border-b">
                        <th>Challan</th>
                        <th>Date</th>
                        <th>Material</th>
                        <th>Party Name</th>
                        <th>Loading Name</th>
                        <th>Vehicle Number</th>
                        <th>Place</th>
                        <th>Net Weight</th>
                        <th>Carting Rate</th>
                        <th>Carting Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $transporterNetWeight = 0;
                        $transporterAmount = 0;
                        $transporterCartingAmount = 0;
                    @endphp
                    @foreach($transporterData['challanWiseData'] as $challanId => $challanData)
                        @forelse ($challanData['records'] as $sale)
                        <tr>
                            <td>{{ $challanData['challanNumber'] }}</td>
                            <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                            <td>{{ $sale->material->name ?? '-' }}</td>
                            <td>{{ $sale->party->name ?? '-' }}</td>
                            <td>{{ $sale->loading->name ?? '-' }}</td>
                            <td>{{ $sale->vehicle->name ?? '-' }}</td>
                            <td>{{ $sale->place->name ?? '-' }}</td>
                            @php
                                // Calculate display weight: party weight if available and not zero, otherwise net weight
                                $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
                                $transporterNetWeight += $displayWeight;
                                $transporterAmount += $sale->amount ?? 0;
                                $transporterCartingAmount += $sale->carting_amount ?? 0;
                            @endphp
                            <td class="text-end">{{ number_format($displayWeight, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->carting_rate ?? 0, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->carting_amount ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No Record Found</td>
                        </tr>
                        @endforelse
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <th colspan="7" class="text-end">Transporter Total:</th>
                        <th class="text-end">{{ number_format($transporterNetWeight, 2) }}</th>
                        <th></th>
                        <th class="text-end">{{ number_format($transporterCartingAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endforeach
        
        <!-- @if(isset($transporterWiseSales) && count($transporterWiseSales) > 0 && empty(request()->challan_id))
        <div class="grand-total-section">
            <table class="grand-total-table">
                <tfoot>
                    <tr class="table-success">
                        <th colspan="9" class="text-end grand-total-label">Grand Total:</th>
                        <th class="text-end grand-total-value">{{ number_format($grandTotalDisplayWeight, 2) }}</th>
                        <th class="text-end grand-total-value">{{ number_format($grandTotalCartingAmount, 2) }}</th>
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