<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Krishna Minerals - Drilling</title>
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
      <td>Challan Number : DR_{{ $challan_number }}</td>
      <td>Date & Time : {{ \Carbon\Carbon::parse($date_time)->format('d-m-Y H:i') }}</td>
    </tr>
    <tr>
      <td colspan="2">Drilling Name : {{ $drilling_name }}</td>
    </tr>
    <tr>
      <td colspan="2">
        <table style="width: 100%; border: none;">
          <thead>
            <tr>
              <th style="border: 1px solid black;">Hole</th>
              <th style="border: 1px solid black;">Foot</th>
              <th style="border: 1px solid black;">Rate</th>
              <th style="border: 1px solid black;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($hole_data as $hole)
            <tr>
              <td style="border: 1px solid black;">{{ $hole['name'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $hole['foot'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $hole['rate'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $hole['total'] ?? 'N/A' }}</td>
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
    <span>Driller Signature : ____________________</span>
    <span style="float: right;">Authorize Signature : ____________________</span>
  </div>
  
  <hr style="margin: 20px 0;">
  
  <table>
    <tr>
      <td colspan="2" style="text-align: center;">Krishna Minerals</td>
    </tr>
    <tr>
      <td>Challan Number : DR_{{ $challan_number }}</td>
      <td>Date & Time : {{ \Carbon\Carbon::parse($date_time)->format('d-m-Y H:i') }}</td>
    </tr>
    <tr>
      <td colspan="2">Drilling Name : {{ $drilling_name }}</td>
    </tr>
    <tr>
      <td colspan="2">
        <table style="width: 100%; border: none;">
          <thead>
            <tr>
              <th style="border: 1px solid black;">Hole</th>
              <th style="border: 1px solid black;">Foot</th>
              <th style="border: 1px solid black;">Rate</th>
              <th style="border: 1px solid black;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($hole_data as $hole)
            <tr>
              <td style="border: 1px solid black;">{{ $hole['name'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $hole['foot'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $hole['rate'] ?? 'N/A' }}</td>
              <td style="border: 1px solid black;">{{ $hole['total'] ?? 'N/A' }}</td>
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
    <span>Driller Signature : ____________________</span>
    <span style="float: right;">Authorize Signature : ____________________</span>
  </div>
</body>
</html>