<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
</head>
<body>
    <h1> Product List </h1>
        @foreach ($products as $product)
            <li>{{ $product->name }}</li>
        @endforeach

    <table>
        <thead>
            <tr>
                <th>Name</th><br>
                <th>Description</th><br>
                <th>Price</th><br>
                <th>Quantity</th><br>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <a href="{{ route('products.show', ['product' => $product->id]) }}">View</a>
                    </td>
                    <td>
                        <form action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>