<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VENTAS</title>
  <link rel="stylesheet" href="{{ asset('css/reports.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/purchases.css') }}" />
</head>

<body>
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
    <h2 class="font-weight-bold">Reportes de ventas</h2>
  </div>
  <table width="100%" border="1" cellspacing="0" cellpadding="0" class="table">
    <thead>
      <tr>
        <th>
          @if($type === 'daily')
          Fecha
          @elseif($type === 'monthly')
          Mes
          @else
          Año
          @endif
        </th>
        <th>Ventas brutas en $</th>
        <th>Ventas brutas en Bs</th>
        <th>Utilidad bruta</th>
        <th>% Utilidad</th>
        <th>Ventas netas en $</th>
        <th>Ventas netas en Bs</th>
        <th>Utilidad Neta</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $key => $item)
      <tr>
        <td>
          {{ $type === 'monthly' ? $months[$item['label']] : $item['label'] }}
        </td>
        <td>{{ $item['purchases'] }}</td>
        <td>{{ number_format($item['purchases_bs'], 2, ',', '.') }}</td>
        <td>{{ $item['utility'] }}</td>
        <td>{{ $item['utility_percentage'] }}%</td>
        <td>{{ $item['purchases_neta'] }}</td>
        <td>{{ number_format($item['purchases_neta_bs'], 2, ',', '.') }}</td>
        <td>{{ $item['utility_neta'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</html>