<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>PRODUCTOS</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>LISTADO DE PRODUCTOS {{ $today }}</h1>
  </td>
  <!--  Bold -->
  <table>
    <thead>
      <tr>
        <th>Código del Producto</th>
        <th>Nombre</th>
        <th>Presentación</th>
        <th>Tipo</th>
        <th>Existencia</th>
        <th>Costo Unitario</th>
        <th>Umbral De Existencia</th>
        <th>Mín. de Venta</th>
        <th>Max. de Venta</th>
        <th>Precio ($)</th>
        <th>Margen de Ganancia ($)</th>
        <th>Porcentaje de utilidad</th>
        <th>Fecha de ingreso</th>
        <th>Fecha de modificación</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $key => $item)
      @foreach($item['presentations'] as $key => $product)
      <tr>
        <td>{{ $item['id'] }}</td>
        <td>{{ $item['name'] }}</td>
        <td>{{ $item['variable'] == 1 ? $product['unitType'] : '' }}</td>
        <td>{{ $item['type_variable'] }}</td>
        <td>{{ $product['amount'] }}</td>
        <td>{{ $product['cost'] }}</td>
        <td>{{ $product['umbral'] }}</td>
        <td>{{ $product['min'] }}</td>
        <td>{{ $product['max'] }}</td>
        <td>{{ number_format($item['variable'] == 0 ? $item['price_1'] : $product['price'], 2, '.', ',') }}</td>
        <td>{{ number_format($item['variable'] == 0 ? $item['price_1']-$product['cost'] : $product['price']-$product['cost'], 2, '.', ',') }}</td>
        @php
            $val = 0;
            if (($item['variable'] == 0 && !is_null($item['price_1']) && $item['price_1'] > 0 && !is_null($product['cost']) && $product['cost'] > 0)
              || (!is_null($product['price'] && $product['price'] > 0 && !is_null($product['cost']) && $product['cost'] > 0))){
              try {
                $val = number_format($item['variable'] == 0 ?
                  ((($item['price_1'] - $product['cost']) / $product['cost']) * 100)
                    :
                  ((($product['price'] - $product['cost']) / $product['cost'])  * 100)
                  , 2, '.', ',');
              } catch (\Exception $th) {
                $val = 0;
              }
            }
        @endphp
        <td>{{$val}}</td>
        <td>{{ $item['es_date'] }}</td>
        <td>{{ $item['es_update'] }}</td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>

</html>
