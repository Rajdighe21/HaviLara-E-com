<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {

        $categorySeleceted = '';
        $subCategorySeleceted = '';
        $brandsArray = [];



        $categories = Category::orderBy('name', 'ASC')->with('sub_category')->where('status', 1)->get();
        $brands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();

        //Apply Filter Here 
        $products = Product::where('status', 1);

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySeleceted = $category->id;
        }
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySeleceted = $subCategory->id;
        }

        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        // $products = Product::orderBy('id', 'DESC')->get();
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {

            if ($request->get('price_max') == 1000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }

        if ($request->get('sort')) {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC');
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC');
            } else {
                $products = $products->orderBy('price', 'DESC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
        }


        $products = $products->paginate(6);
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySeleceted'] = $categorySeleceted;
        $data['subCategorySeleceted'] = $subCategorySeleceted;
        $data['brandsArray'] = $brandsArray;
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : intval($request->get('price_max'));
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = intval($request->get('sort'));

        return  view('front.shop', $data);
    }
}
