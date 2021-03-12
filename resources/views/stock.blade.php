<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pedidos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <style>
        @page{
            margin: 0cm 0cm;
            font-size: 1em;
        }
        body{
            margin-top: 3cm;
        }
        header{
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #1d68a7;
            color: #ffffff;
            text-align: center;
            line-height: 30px;
        }
    </style>
</head>
<body>
<header>
    <h3><strong>Reporte de InnerSet Store</strong></h3>
</header>
<div class="container">
    <h4 style="text-align: center">Stock de Productos</h4>
    <table class="table table-sm table-bordered">
        <thead class="thead-light">
        <tr>
            <th scope="col">N°</th>
            <th scope="col">Nombre de Producto</th>
            <th scope="col">Stock</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stock as $product)
            <tr>
                <th scope="row">{{$product->id}}</th>
                <td>{{$product->name}}</td>
                <td>{{($product->stock)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

