<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bienvenido</title>
    <link rel="StyleSheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700" />
    @include('emails.new-style')
</head>

<body>
    <div class="container">
        @include('emails.partials.header')
        <h1 class="title text-center">Registro de clientes</h1>
        <div class="text-justify">
            <div class="text-justify">
                <p>Hola, {{ $user->name }}</p>
                <p class="mt-2">Te damos la bienvenida a <b>{{ config('app.name') }}</b> </p>
                <p>Tu Usuario para ingresar al sistema es: <code>{{ $user->email }}</code></p>
                <p class="mt-1">Tu Clave para ingresar al sistema es: <code>{{ $password }}</code></p>
                <p class="mt-2">Por favor guarda este registro en un lugar seguro, y recuerda que tu clave es confidencial no debes entregársela a terceras personas.</p>
            </div>
        </div>
        @include('emails.partials.footer')
    </div>
</body>

</html>