<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>PEDIDOS</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>LISTADO DE PEDIDOS {{ $today }}</h1>
  </td>
  <!--  Bold -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Fecha/Hora</th>
        <th>Cliente</th>
        <th>Monto</th>
        <th>Medio de Pago</th>
        <th>Tipo de Entrega</th>
        <th>Estatus</th>
        <th>Fecha de Entrega</th>
        <th>Turno</th>
        <th>ID Transación</th>
        <th>Estado</th>
        <th>Municipio</th>
        <th>Sector</th>
      </tr>
    </thead>
    <tbody>
      @foreach($purchases as $key => $purchase)
      <tr>
        <td>{{ $purchase['id'] }}</td>
        <td>{{ $purchase['createdAt'] }}</td>
        <td>{{ $purchase['clientName'] }}</td>
        <td>{{ number_format($purchase['amount'], 2, '.', ',') }} {{ $purchase['currency'] == 1 ? 'Bs.' : 'USD' }}</td>
        <td>{{ $purchase['paymentType'] }}</td>
        <td>{{ $purchase['deliveryType'] }}</td>
        <td>{{ $purchase['statusType'] }}</td>
        <td>{{ $purchase['deliveryDay'] }}</td>
        <td>{{ $purchase['typeTurn'] }}</td>
        <td>{{ $purchase['code'] }}</td>
        <td>{{ $purchase['stateName'] }}</td>
        <td>{{ $purchase['municipalityName'] }}</td>
        <td>{{ $purchase['parishName'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</html>