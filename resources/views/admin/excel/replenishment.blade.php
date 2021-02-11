<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>REPOSICION DE INVENTARIO</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>LISTADO DEL {{ $today }}</h1>
  </td>
  <!--  Bold -->
  <table>
    <thead>
      <tr>
        <th>Código del Producto</th>
        <th>Usuario</th>
        <th>Producto</th>
        <th>Presentacion</th>
        <th>Tipo de Reposición</th>
        <th>Cantidad Original</th>
        <th>Cantidad Modificada</th>
        <th>Cantidad Final</th>
        <th>Fecha</th>
        <th>Razon</th>
      </tr>
    </thead>
    <tbody>
        @foreach($reps as $key => $rep)
            <tr>
                <td>{{ $rep['id'] }}</td>
                <td>{{ $rep['user']['name'] }}</td>
                <td>{{ $rep['presentation']['product']['es_name'] }}</td>
                <td>{{ $rep['presentation']['presentation_formatted'] }}</td>
                <td>{{ $rep['type'] == 0 ? 'Entrada' : 'Salida' }}</td>
                <td>{{ $rep['existing'] }}</td>
                <td>{{ $rep['modified']}} </td>
                <td>{{ $rep['final']}} </td>
                <td>{{ \Carbon\Carbon::parse($rep['created_at'])->format('d-m-Y h:i A') }} </td>
                <td>{{ $rep['reason']}} </td>
            </tr>
        @endforeach
    </tbody>
  </table>

</html>