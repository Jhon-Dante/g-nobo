<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>REPORTE PRODUCTOS MAS VENDIDOS</title>
  <link rel="stylesheet" href="{{ asset('css/reports.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/purchases.css') }}" />
</head>

<body>
  <main>
    <div class="header">
      <div class="img">
        <img class="logo" src="{{ asset('img/logo-black.png') }}">
      </div>
    </div>
    @if($from && $to)
    <div class="date">
      <span>Desde {{ $from }} hasta {{ $to }}</span>
    </div>
    @endif
    <div class="title">
      <h2 class="font-weight-bold">Productos mas vendidos</h2>
    </div>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th>Nombre del producto</th>
          <th>Unidades vendidas</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $key => $item)
        <tr>
          <td>{{ $item['presentation_formatted'] }}</td>
          <td>{{ $item['purchases_number'] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </main>

</html>