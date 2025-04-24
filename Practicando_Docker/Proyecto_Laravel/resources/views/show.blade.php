<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show</title>
</head>
<body>
    <body>
        <h1>Product </h1>
        <h3>Product: {{$product -> name}}</h3>
        <p>Description: {{$product -> description}}</p>
        <p>Price: {{$product -> price}}</p>
        <p>Quantity: {{$product -> quantity}}</p>
        <a href="{{route('product.edit', ["product" => $product -> id])}}">Edit</a>
        <br>
        <a href="{{route('products.index')}}">Back</a>
    </body>
</body>
</html>