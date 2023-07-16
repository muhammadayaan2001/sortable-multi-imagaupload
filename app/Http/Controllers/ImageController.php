<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function showWelcomePage()
    {
        $images = Image::orderBy('position')->get(); // Fetch images ordered by position

        return view('welcome', compact('images'));
    }

    public function uploadImages(Request $request)
    {
        $images = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = $file->getClientOriginalName();
                $path = public_path('uploads');
                $file->move($path, $filename);
    
                $images[] = Image::create(['images' => $filename]);
            }
        }
    
        return response()->json(['success' => true, 'images' => $images]);
    }

    public function updateImagePosition(Request $request)
    {
        $images = $request->input('images');

        foreach ($images as $index => $image) {
            Image::where('id', $image['id'])->update(['position' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
