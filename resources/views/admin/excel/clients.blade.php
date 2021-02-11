<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>CLIENTES</title>
</head>

<body>
  <!-- Headings -->
  <td>
    <h1>LISTADO DE CLIENTES {{ $today }}</h1>
  </td>
  <!--  Bold -->
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Identificación</th>
        <th>Fecha de Registro</th>
        <th>Teléfono</th>
        <th>Estatus</th>
        <th>Correo</th>
        <th>Estado</th>
        <th>Municipio</th>
        <th>Paroquia</th>
        <th>Dirección</th>
      </tr>
    </thead>
    <tbody>
      @foreach($clients as $key => $client)
      <tr>
        <td>{{ $client['name'] }}</td>
        <td>{{ $client['full_document'] }}</td>
        <td>{{ $client['es_date'] }}</td>
        <td>{{ $client['telefono'] }}</td>
        <td>{{ $client['status_name'] }}</td>
        <td>{{ $client['email'] }}</td>
        <td>{{ $client['estado']['nombre'] }}</td>
        <td>{{ $client['municipality']['name'] }}</td>
        <td>{{ $client['parish']['name'] }}</td>
        <td>{{ $client['direccion'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</html>