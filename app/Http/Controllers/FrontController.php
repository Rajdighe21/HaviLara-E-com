<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {

        $Products = Product::where('is_Featured', 'Yes')->orderBy('id', 'DESC')->take(8)->where('status', 1)->get();
        $data['featuredProducts'] = $Products;

        $latestProducts = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();
        $data['latestProducts'] = $latestProducts;

        return view('front.home', $data);
    }
}
