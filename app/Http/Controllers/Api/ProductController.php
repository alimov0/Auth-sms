<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('user')->latest()->paginate(12); 
        return $this->responsePagination($products, ProductResource::collection($products), 'Products list');
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $product = Product::create($data); 
        $product->load('user');

        return $this->success(new ProductResource($product), 'Product created', 201);
    }

    public function show(Product $product)
    {
        $product->load('user');
        return $this->success(new ProductResource($product), 'Product detail');
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) { 
            return $this->error('You are not the owner of this product', 403);
        }

        $product->update($request->validated());
        $product->load('user');

        return $this->success(new ProductResource($product), 'Product updated');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            return $this->error('You are not the owner of this product', 403);
        }

        $product->delete();
        return $this->success((object)[], 'Product deleted');
    }
}
