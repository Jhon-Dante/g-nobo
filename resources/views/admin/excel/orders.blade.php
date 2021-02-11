<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>PEDIDOS</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>REPORTE DE PEDIDOS</h1>
  </td>
  <!--  Bold -->
  <table>
    <thead>
      <tr>
        <th>Fecha</th>
        <th># Pedidos</th>
        <th>Pendientes</th>
        <th>Aprobados</th>
        <th>Completados</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $key => $item)
      <tr>
        <td>{{ \Carbon\Carbon::parse($item['label'])->format('d-m-Y') }}</td>
        <td>{{ $item['orders'] }}</td>
        <td>{{ $item['pending'] }}</td>
        <td>{{ $item['processing'] }}</td>
        <td>{{ $item['completed'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</html>