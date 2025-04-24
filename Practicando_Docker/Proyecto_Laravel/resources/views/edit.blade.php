<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit</title>
</head>
<body>
    <form action="{{ route('products.update', ["product" => $product -> id]) }}" method="post"> 
        @csrf
        {{ method_field('PUT') }}
        <label> Name: </label>
        <input type="text" name="name" value="{{ $product -> description }}"><br><br>

        <label> Description:</label>
        <input type="text" name="description" value="{{ $product -> description }}"><br><br>

        <label> Price: </label>
        <input type="number" name="price" value="{{ $product -> price }}"><br><br> 

        <label> Quantity </label>
        <input type="number" name="quantity" value="{{ $product -> quantity }}"><br><br>

        <input type="submit" value="Edit">

</body>
</html>