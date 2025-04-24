<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>
<body>
    <form action= "{{ route('products.store')}}">
        @csrf
        <label> Name: </label>
        <input type="text" name="name"><br><br>

        <label> Description: </label>
        <input type="text" name="description"><br><br>
        
        <label> Price: </label>
        <input type= "number" name="price"><br><br>

        <label> Quantity: </label>
        <input type="number" name="quantity"><br><br>

        <input type="submit" value="Create">
    </form>
</body>
</html>