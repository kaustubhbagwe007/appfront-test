<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendPriceChangeNotification;
use App\Services\FileSystemService;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('admin.products.index', compact('products'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('admin.products.edit', compact('product'));
    }

    public function update($id, ProductRequest $request)
    {
        $product = Product::findOrFail($id);

        // Store the old price before updating
        $oldPrice = $product->price;

        $updateFields = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ];

        if ($request->hasFile('image')) {
            $updateFields['image'] = (new FileSystemService)->store($request->file('image'));
        }

        $product->update($updateFields);

        // Check if price has changed
        if ($oldPrice != $product->price) {
            // Get notification email from env
            $notificationEmail = env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com');

            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    $notificationEmail
                );
            } catch (\Exception $e) {
                 Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(ProductRequest $request)
    {
        $createFields = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => 'product-placeholder.jpg',
        ];

        if ($request->hasFile('image')) {
            $createFields['image'] = (new FileSystemService)->store($request->file('image'));
        }

        Product::create($createFields);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
    }
}
