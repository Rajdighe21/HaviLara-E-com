<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Validator;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brand.list', compact('brands'));
    }
    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required | unique:sub_categories',
            'status' => "required"
        ]);


        if ($validator->passes()) {

            $brands = new Brand();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success', 'Sub Category Created Sucessfully');

            return response()->json([
                'status' => true,
                'success' => 'Sub Category Created Sucessfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    public function edit($brand, Request $request)
    {

        $brand = Brand::find($brand);
        return view('admin.brand.edit', compact('brand'));
    }

    public function update($brand, Request $request)
    {

        $brands = Brand::find($brand);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required | unique:sub_categories',
            'status' => "required"
        ]);


        if ($validator->passes()) {

            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success', 'Brand Updated Sucessfully');

            return response()->json([
                'status' => true,
                'success' => 'Brand Updated Sucessfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destory($BrandId, Request $request)
    {
        $brands = Brand::find($BrandId);

        if (empty($brands)) {
            $request->session()->flash('error', 'Brand Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);

        } else {

            $brands->delete();
            $request->session()->flash('success', 'Brand Deleted Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand Deleted Successfully',
            ]);
        }
    }
}
