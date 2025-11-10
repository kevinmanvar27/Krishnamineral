<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Krishna Minerals - Blasting</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      page-break-inside: avoid; /* prevents table from splitting */
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid black;
      padding: 10px 15px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .page {
      page-break-after: always; /* start a new page after 2 tables */
    }
    
    .text-center {
      text-align: center;
    }
  </style>
</head>
<body>
  <table>
    <tr>
      <td colspan="2" style="text-align: center;">Krishna Minerals</td>
    </tr>
    <tr>
      <td>Challan Number : BL_{{ $challan_number }}</td>
      <td>Date & Time : {{ \Carbon\Carbon::parse($date_time)->format('d-m-Y H:i') }}</td>
    </tr>
    <tr>
      <td colspan="2">Blaster Name : {{ $blaster_name }}</td>
    </tr>
    <tr>
      <td colspan="2">
        <table style="width: 100%; border: none;">
          <tr>
            <td style="border: 1px solid black;"><strong>Geliten</strong></td>
            <td style="border: 1px solid black;">{{ $geliten }}</td>
            <td style="border: 1px solid black;"><strong>Geliten Rate</strong></td>
            <td style="border: 1px solid black;">{{ $geliten_rate }}</td>
            <td style="border: 1px solid black;"><strong>Geliten Total</strong></td>
            <td style="border: 1px solid black;">{{ $geliten_total }}</td>
          </tr>
          <tr>
            <td style="border: 1px solid black;"><strong>DF</strong></td>
            <td style="border: 1px solid black;">{{ $df }}</td>
            <td style="border: 1px solid black;"><strong>DF Rate</strong></td>
            <td style="border: 1px solid black;">{{ $df_rate }}</td>
            <td style="border: 1px solid black;"><strong>DF Total</strong></td>
            <td style="border: 1px solid black;">{{ $df_total }}</td>
          </tr>
          <tr>
            <td style="border: 1px solid black;"><strong>OD VAT</strong></td>
            <td style="border: 1px solid black;">{{ $odvat }}</td>
            <td style="border: 1px solid black;"><strong>OD Rate</strong></td>
            <td style="border: 1px solid black;">{{ $od_rate }}</td>
            <td style="border: 1px solid black;"><strong>OD Total</strong></td>
            <td style="border: 1px solid black;">{{ $od_total }}</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table style="width: 100%; border: none;">
          <thead>
            <tr>
              <th style="border: 1px solid black;">Controll</th>
              <th style="border: 1px solid black;">Meter</th>
              <th style="border: 1px solid black;">Rate</th>
              <th style="border: 1px solid black;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($controll_data as $control)
            <tr>
              <td style="border: 1px solid black;">{{ $control['name'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $control['meter'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $control['rate'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $control['total'] ?? 'N/A' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">Gross Total : {{ $gross_total }}</td>
    </tr>
  </table>
  
  <div style="margin-top: 30px;">
    <span>Blaster Signature : ____________________</span>
    <span style="float: right;">Authorize Signature : ____________________</span>
  </div>
  
  <hr style="margin: 20px 0;">
  
  <table>
    <tr>
      <td colspan="2" style="text-align: center;">Krishna Minerals</td>
    </tr>
    <tr>
      <td>Challan Number : BL_{{ $challan_number }}</td>
      <td>Date & Time : {{ \Carbon\Carbon::parse($date_time)->format('d-m-Y H:i') }}</td>
    </tr>
    <tr>
      <td colspan="2">Blaster Name : {{ $blaster_name }}</td>
    </tr>
    <tr>
      <td colspan="2">
        <table style="width: 100%; border: none;">
          <tr>
            <td style="border: 1px solid black;"><strong>Geliten</strong></td>
            <td style="border: 1px solid black;">{{ $geliten }}</td>
            <td style="border: 1px solid black;"><strong>Geliten Rate</strong></td>
            <td style="border: 1px solid black;">{{ $geliten_rate }}</td>
            <td style="border: 1px solid black;"><strong>Geliten Total</strong></td>
            <td style="border: 1px solid black;">{{ $geliten_total }}</td>
          </tr>
          <tr>
            <td style="border: 1px solid black;"><strong>DF</strong></td>
            <td style="border: 1px solid black;">{{ $df }}</td>
            <td style="border: 1px solid black;"><strong>DF Rate</strong></td>
            <td style="border: 1px solid black;">{{ $df_rate }}</td>
            <td style="border: 1px solid black;"><strong>DF Total</strong></td>
            <td style="border: 1px solid black;">{{ $df_total }}</td>
          </tr>
          <tr>
            <td style="border: 1px solid black;"><strong>OD VAT</strong></td>
            <td style="border: 1px solid black;">{{ $odvat }}</td>
            <td style="border: 1px solid black;"><strong>OD Rate</strong></td>
            <td style="border: 1px solid black;">{{ $od_rate }}</td>
            <td style="border: 1px solid black;"><strong>OD Total</strong></td>
            <td style="border: 1px solid black;">{{ $od_total }}</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table style="width: 100%; border: none;">
          <thead>
            <tr>
              <th style="border: 1px solid black;">Controll</th>
              <th style="border: 1px solid black;">Meter</th>
              <th style="border: 1px solid black;">Rate</th>
              <th style="border: 1px solid black;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($controll_data as $control)
            <tr>
              <td style="border: 1px solid black;">{{ $control['name'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $control['meter'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $control['rate'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $control['total'] ?? 'N/A' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">Gross Total : {{ $gross_total }}</td>
    </tr>
  </table>
  
  <div style="margin-top: 30px;">
    <span>Blaster Signature : ____________________</span>
    <span style="float: right;">Authorize Signature : ____________________</span>
  </div>
</body>
</html>