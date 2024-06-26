<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix errors',
                'errors' => $validator->errors()
            ]);
        }

        //upload image
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;

        //lưu trữ thông tin hình ảnh trong cơ sở dữ liệu
        $tempImage = new TempImage();
        $tempImage->name = $imageName;
        $tempImage->save();

        //di chuyen image vao thu muc temp
        $image->move(public_path('uploads/temp'), $imageName);

        return response()->json([
            'status' => true,
            'message' => 'Image uploaded successfully.',
            'image' => $tempImage
        ]);
    }
}
