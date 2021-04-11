<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Nyholm\Psr7\Response;

class UploadController extends Controller
{

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator->fails()) {
            return Response([
                'status' => 'Failed',
                'message' => $validator->messages()],
                400);
        }

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('images'), $imageName);

        return Response(['status' => 'Success',
            'message' => 'Image Uploaded Successfully',
            'file_id' => $imageName]);
    }

}
