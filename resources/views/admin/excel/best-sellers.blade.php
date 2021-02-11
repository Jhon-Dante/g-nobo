<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>PRODUCTOS MAS VENDIDOS</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>REPORTE PRODUCTOS MAS VENDIDOS</h1>
  </td>
  <!--  Bold -->
  <table>
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

</html>