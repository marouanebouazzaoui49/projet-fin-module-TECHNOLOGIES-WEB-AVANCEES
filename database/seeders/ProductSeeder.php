<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les catégories
        $electronics = Category::create([
            'name' => 'Électroniques',
            'description' => 'Produits électroniques et accessoires',
        ]);

        $books = Category::create([
            'name' => 'Livres',
            'description' => 'Livres et guides',
        ]);

        $clothing = Category::create([
            'name' => 'Vêtements',
            'description' => 'Vêtements et accessoires',
        ]);

        // Créer les produits
        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Casque Bluetooth',
            'description' => 'Casque sans fil avec batterie 30h',
            'price' => 79.99,
            'stock' => 50,
            'image' => 'headphones.jpg',
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Webcam HD',
            'description' => 'Webcam 1080p avec microphone intégré',
            'price' => 49.99,
            'stock' => 30,
            'image' => 'webcam.jpg',
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'Laravel for Beginners',
            'description' => 'Guide complet pour débuter avec Laravel',
            'price' => 24.99,
            'stock' => 100,
            'image' => 'laravel-book.jpg',
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'PHP Advanced Concepts',
            'description' => 'Concepts avancés de PHP',
            'price' => 34.99,
            'stock' => 60,
            'image' => 'php-book.jpg',
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'T-Shirt Developer',
            'description' => 'T-shirt confortable pour développeurs',
            'price' => 19.99,
            'stock' => 150,
            'image' => 'tshirt.jpg',
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Hoodie Programmeur',
            'description' => 'Hoodie chaud et stylé',
            'price' => 49.99,
            'stock' => 80,
            'image' => 'hoodie.jpg',
        ]);
    }
}
