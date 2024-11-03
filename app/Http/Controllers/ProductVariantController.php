<?php

namespace App\Http\Controllers;

use App\Models\Store\ProductVariant;
use App\Models\Store\ProductVariantPicture;
use GuzzleHttp\Client;
use App\Models\Store\Product;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Store\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVariant $productVariant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVariant $productVariant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductVariant $productVariant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVariant $productVariant)
    {
        //
    }

    public function get_json(Request $request)
    {
        $productVariant = ProductVariant::where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->where('color', $request->color)
            ->first();

        // Mēdz gadīties tā, ka izvēlētai krādai
        if(!$productVariant) {
            $productVariant = ProductVariant::where('product_id', $request->product_id)
                ->where('color', $request->color)
                ->first();


            $available_sizes = ProductVariant::where('product_id', $request->product_id)
                ->where('color', $request->color)
                ->pluck('size');

            return [
                'id' => $productVariant->id,
                'preview_url' => $productVariant->pictures()->first()->preview_url,
                'price' => $productVariant->price,
                'available_sizes' => $available_sizes
            ];
        }

        return [
            'id' => $productVariant->id,
            'preview_url' => $productVariant->pictures()->first()->preview_url,
            'price' => $productVariant->price
        ];
    }

    public function sync()
    {
        $api_key = "3ronjs2s-64vm-g6wj:7wku-mpegpggo4ril";
        $client = new Client(['base_uri' => 'https://api.printful.com']);

        $products = Product::all();

        foreach ($products as $p) {
            $response = $client->request('GET', "sync/products/$p->id", [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($api_key),
                    'Accept'        => 'application/json',
                ]
            ]);
            $variant_data = json_decode($response->getBody()->getContents())->result->sync_variants;

            foreach ($variant_data as $v) {
                    // return dd($v);
                    $response = $client->request('GET', "products/variant/$v->variant_id", [
                        'headers' => [
                            'Authorization' => 'Basic ' . base64_encode($api_key),
                            'Accept'        => 'application/json',
                        ]
                    ]);
                    $info = json_decode($response->getBody()->getContents())->result->variant;

                    $input = [
                        'id' => $v->id,
                        'product_id' => $p->id,
                        'size' => $info->size,
                        'color' => $info->color,
                        'color_hex' => $info->color_code,
                        'price' => $v->retail_price,
                    ];

                    ProductVariant::updateOrCreate($input);

                    foreach ($v->files as $f) {
                        ProductVariantPicture::updateOrCreate(
                            ['id' => $f->id],
                            [
                            'id' => $f->id,
                            'product_id' => $p->id,
                            'color' => $info->color,
                            'thumbnail_url' => $f->thumbnail_url,
                            'preview_url' => $f->preview_url
                        ]);
                    }
            }


            // code...
        }
        return 'Done!';
    }
}
