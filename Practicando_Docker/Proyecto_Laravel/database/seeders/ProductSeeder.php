<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
 
    public function run(): void
    {
     
        Product::create([
            'name' => 'Producto 1',
            'description' => 'Descripción del producto 1',
            'price' => 19.99,
            'quantity' => 10,
        ]);

        Product::create([
            'name' => 'Producto 2',
            'description' => 'Descripción del producto 2',
            'price' => 29.99,
            'quantity' => 5,
        ]);

        Product::create([
            'name' => 'Producto 3',
            'description' => 'Descripción del producto 2',
            'price' => 29.99,
            'quantity' => 5,
        ]);

        Product::create([
            'name' => 'Producto 4',
            'description' => 'Descripción del producto 2',
            'price' => 29.99,
            'quantity' => 5,
        ]);

        Product::create([
            'name' => 'Producto 5',
            'description' => 'Descripción del producto 2',
            'price' => 29.99,
            'quantity' => 5,
        ]);

        Product::create([
            'name' => 'Producto 6',
            'description' => 'Descripción del producto 6',
            'price' => 29.99,
            'quantity' => 5,
        ]);

    }
}