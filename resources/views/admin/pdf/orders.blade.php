<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ORDERS</title>
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
    {{--<div class="date">
      <span>Desde {{ $from }} hasta {{ $to }}</span>
    </div>--}}
    @if($from && $to)
    <div class="date">
      <span>Desde {{ $from }} hasta {{ $to }}</span>
    </div>
    @endif
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
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
  </main>

</html>