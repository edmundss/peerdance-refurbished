<?php

namespace App\Http\Controllers;

use App\Models\Store\Product;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [
            'products' => Product::all(),
        ];

        return view('store.products.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $params = [
            'product' => $product,
            'variant' => $product->product_variants()->first(),
            'sizes' => $product->product_variants()->groupBy('size')->pluck('size','size'),
            'colors' => $product->product_variants()->groupBy('color')->pluck('color', 'color'),
        ];

        return view('store.products.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        //
    }

    public function sync()
    {
        $api_key = "3ronjs2s-64vm-g6wj:7wku-mpegpggo4ril";

        $client = new Client(['base_uri' => 'https://api.printful.com']);
    	$response = $client->request('GET', 'sync/products', [
	        'headers' => [
			    'Authorization' => 'Basic ' . base64_encode($api_key),
			    'Accept'        => 'application/json',
			]
	    ]);
		$products = json_decode($response->getBody()->getContents())->result;

        foreach ($products as $p) {
            $p = (array)$p;
            Product::updateOrCreate(['id' => $p['id']],
                [
                'id' => $p['id'],
                'name' => $p['name'],
                'thumbnail_url' => $p['thumbnail_url'],
            ]);
        }
    }
}
