<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Krishna Minerals</title>
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
  </style>
</head>
<body>
  <table>
    <tr>
      <td colspan="2" style="text-align: center;">Krishna Minerals</td>
    </tr>
    <tr>
      <td>Challan Number : {{ 'P_'.$challan_number }}</td>
      <td>Date & Time : {{ $date_time }}</td>
    </tr>
    <tr>
      <td colspan="2">Receiver Name : {{ $receiver }}</td>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td>Vehicle Number : {{ $vehicle_number }}</td>
    </tr>
    <tr>
      <td>Gross Weight : {{ $gross_weight }}</td>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td>Tare Weight : {{ $tare_weight }}</td>
    </tr>
    <tr>
      <td>Net Weight : {{ $net_weight }}</td>
    </tr>
    <tr>
      <td colspan="2">Material : {{ $material }}</td>
    </tr>
  </table>
  <hr style="margin: 20px 0;">

  <table>
    <tr>
      <td colspan="2" style="text-align: center;">Krishna Minerals</td>
    </tr>
    <tr>
      <td>Challan Number : {{ 'P_'.$challan_number }}</td>
      <td>Date & Time : {{ $date_time }}</td>
    </tr>
    <tr>
      <td colspan="2">Receiver Name : {{ $receiver }}</td>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td>Vehicle Number : {{ $vehicle_number }}</td>
    </tr>
    <tr>
      <td>Gross Weight : {{ $gross_weight }}</td>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td>Tare Weight : {{ $tare_weight }}</td>
    </tr>
    <tr>
      <td>Net Weight : {{ $net_weight }}</td>
    </tr>
    <tr>
      <td colspan="2">Material : {{ $material }}</td>
    </tr>
  </table>
  <span>Signature : __________________________________</span>
  <hr style="margin: 20px 0;">


  <table>
    <tr>
      <td colspan="2" style="text-align: center;">Krishna Minerals</td>
    </tr>
    <tr>
      <td>Challan Number : {{ 'P_'.$challan_number }}</td>
      <td>Date & Time : {{ $date_time }}</td>
    </tr>
    <tr>
      <td colspan="2">Receiver Name : {{ $receiver }}</td>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td>Vehicle Number : {{ $vehicle_number }}</td>
    </tr>
    <tr>
      <td>Gross Weight : {{ $gross_weight }}</td>
    </tr>
    <tr>
      <td rowspan="2"></td>
      <td>Tare Weight : {{ $tare_weight }}</td>
    </tr>
    <tr>
      <td>Net Weight : {{ $net_weight }}</td>
    </tr>
    <tr>
      <td colspan="2">Material : {{ $material }}</td>
    </tr>
  </table>
</body>
</html>
