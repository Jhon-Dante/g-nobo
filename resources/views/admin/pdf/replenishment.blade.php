<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PDF - Reposicion de Inventarios</title>
        <link rel="stylesheet" href="{{ asset('css/orders.css') }}" />
        <style>
            .bordered{
                border: solid 1px black;
            }
            .border-bottom{
                border-bottom: solid 1px black;
                width: 100%;
            }
            tr td{
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="img">
                <img class="logo" src="{{ asset('img/logo-black.png') }}">
            </div>
        </div>
        <div class="invoice-box">
            <table cellpadding="0" cellspacing="0">
                <tr class="information">
                    <td class="border-top">
                        <h4 class="text-left text-uppercase">Reposicion de Inventario</h4>
                    </td>
                </tr>
            </table>
            <table class="bordered" border="1" cellspacing="0" cellpadding="0">
                <tr class="border-bottom">
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Producto</th>
                    <th>Presentacion</th>
                    <th>Tipo</th>
                    <th>Cantidad original</th>
                    <th>Cantidad modificada</th>
                    <th>Cantidad final</th>
                    <th>Fecha</th>
                    <th>Razon</th>
                </tr>
                @php
                    $units =  [
                        '1' => 'Gr',
                        '2' => 'Kg',
                        '3' => 'Ml',
                        '4' => 'L',
                        '5' => 'Cm'
                    ];
                @endphp
                @foreach($data as $key => $item)
                    <tr class="border-bottom">
                        <td> {{$item['id']}} </td>
                        <td> {{$item['user']['name']}} </td>
                        <td> {{$item['presentation']['product']['es_name']}} </td>
                        <td> {{$item['presentation']['presentation']}} {{$item['presentation'] && $item['presentation']['unit'] ? $units[$item['presentation']['unit']] : ''}} </td>
                        <td> {{$item['type'] == 0 ? 'Entrada' : 'Salida'}} </td>
                        <td> {{$item['existing']}} </td>
                        <td> {{$item['modified']}} </td>
                        <td> {{$item['final']}} </td>
                        <td> {{\Carbon\Carbon::parse($item['created_at'])->format('d-m-Y h:i A')}} </td>
                        <td> {{$item['reason']}} </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </body>
</html>