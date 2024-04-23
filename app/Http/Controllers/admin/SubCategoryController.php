<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');

        }
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        return view("admin/sub_category/create", $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required | unique:sub_categories',
            'category' => 'required',
            'status' => "required"
        ]);


        if ($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

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

    public function edit($SubcategoryID, Request $request)
    {

        $Subcategory = SubCategory::find($SubcategoryID);
        $categories = Category::all();
        if (empty($Subcategory)) {
            return redirect()->route("sub-categories.index");
        } else {
            return view('admin/sub_category/edit', compact('Subcategory', 'categories'));
        }

    }

    public function update($SubcategoryID, Request $request)
    {
        $subCategory = SubCategory::find($SubcategoryID);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required | unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => "required"
        ]);


        if ($validator->passes()) {

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category Updated Sucessfully');

            return response()->json([
                'status' => true,
                'success' => 'Sub Category Updated Sucessfully',
            ]);
        } else {


            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    public function destory($SubcategoryID, Request $request)
    {
        $subCategory = SubCategory::find($SubcategoryID);

        if (empty($subCategory)) {
            $request->session()->flash('error', 'Sub Category Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);

        } else {

            $subCategory->delete();
            $request->session()->flash('success', 'Sub Category Deleted Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category Deleted Successfully',
            ]);
        }
    }

}
