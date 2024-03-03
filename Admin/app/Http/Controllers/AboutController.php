<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{

    // Data show

    public function show()
    {
        $data = DB::table('about')->get();
        return view('admin.about', compact('data'));
    }

    // Store Data
    public function store(Request $request)
    {

        // Validate the incoming data
        $validatedData = $request->validate([

            'title' => 'required|string|max:255',
            'detail' => 'required|string',
            'coding' =>'required|integer',
            'photoshop' =>'required|integer',
            'animation' =>'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $image = $request->file('image');
        $encodedImage = base64_encode(file_get_contents($image->getPathname()));


        DB::table('about')->insert([

            'title' => $validatedData['title'],
            'details' => $validatedData['detail'],
            'coding_percentage' => $validatedData['coding'],
            'photoshop_percentage' => $validatedData['photoshop'],
            'animation_percentage' => $validatedData['animation'],
            'image' =>  $encodedImage,
        ]);



        return redirect('about')->with('success', 'Home information added successfully!');
    }

    // GetData
    public function getData(string $id)
    {
        $data = DB::table('about')->where('id', $id)->first();

        return response()->json($data);
    }

    //  update data
    public function update(Request $request)
    {
        $image = $request->file('image');
        $encodedImage = null;

        // Check if a file was uploaded
        if ($image) {
            $encodedImage = base64_encode(file_get_contents($image->getPathname()));
        } else {

            $oldImageData = DB::table('about')
                ->where('id', $request->Edit_id)
                ->select('image')
                ->first();
            $encodedImage = $oldImageData->image ?? '';
        }

        // Update the database
        DB::table('about')
            ->where('id', $request->Edit_id)
            ->update([

                'title' => $request->title,
                'details' => $request->detail,
                'coding_percentage' => $request->coding,
                'photoshop_percentage' => $request->photoshop,
                'animation_percentage' => $request->animation,
                'image' => $encodedImage,
            ]);

        return redirect('about')->with('success', 'Home information updated successfully!');
    }


    //  delete data
    public function delete(Request $request)
    {
        dd($request->all());
        $home = DB::table('about')->findOrFail($request->id);

        $home->delete();

        return redirect('about')->with('success', 'Home information deleted successfully!');
    }
}
