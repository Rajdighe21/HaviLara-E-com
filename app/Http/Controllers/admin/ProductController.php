<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        // dd($products);
        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }
        $products = $products->paginate(10);
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }

    public function create()
    {
        $data = [];

        $data['categories'] = Category::orderBy('name', 'ASC')->get();
        $data['brands'] = Brand::orderBy('name', 'ASC')->get();

        return view('admin.products.create', $data);
    }


    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'slug' => 'required | unique:products',
            'price' => 'required | numeric',
            'sku' => 'required | unique:products',
            'track_qty' => 'required | in:Yes,No',
            'category' => 'required | numeric',
            'is_featured' => 'required | in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required | numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id     = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            if (!empty($request->image_array)) {

                foreach ($request->image_array as  $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    $sourcePath = public_path('temp') . '/' . $tempImageInfo->name;
                    $destPath = public_path('uploads') . '/' . $imageName;
                    File::move($sourcePath, $destPath);
                   
                }
            }


            $request->session()->flash('success', 'Product Added Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Products Added Successfully !'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }



    public function edit($id, Request $request)
    {
        $product  = Product::find($id);
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $data = [];
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['categories'] = Category::orderBy('name', 'ASC')->get();
        $data['brands'] = Brand::orderBy('name', 'ASC')->get();
        return view('admin.products.edit', $data);
    }


    public function update($id, Request $request)
    {
        $product  = Product::find($id);


        $rules = [
            'title' => 'required',
            'slug' => 'required | unique:products,slug,' . $product->id . ',id',
            'price' => 'required | numeric',
            'sku' => 'required | unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required | in:Yes,No',
            'category' => 'required | numeric',
            'is_featured' => 'required | in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required | numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id     = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();





            $request->session()->flash('success', 'Product Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Products Updated Successfully !'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destory($id, Request $request)
    {
        $product = Product::find($id);

        if (empty($product)) {
            $request->session()->flash('error', 'Sub Category Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        } else {

            $product->delete();
            $request->session()->flash('success', 'Sub Category Deleted Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category Deleted Successfully',
            ]);
        }
    }
}
