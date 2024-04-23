<?php

namespace App\Http\Controllers\admin;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {

        $image = $request->image;

        if (!empty($request->image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();
            $image->move(public_path() . '/temp', $newName);


            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/' . $newName),
                'message' => "Image Uploded Suceessfully",
            ]);
        }
    }
}
