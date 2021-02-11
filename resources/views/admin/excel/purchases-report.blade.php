<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Ventas</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>REPORTE DE VENTAS  
      @if($type === 'daily')
        DIARIO
      @elseif($type === 'monthly')
        MENSUAL
      @else 
        ANUAL
      @endif
    </h1>
  </td>
  <!--  Bold -->
  <table>
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
        <td>{{ $item['purchases_neta_bs'] }}</td>
        <td>{{ $item['utility_neta'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</html>