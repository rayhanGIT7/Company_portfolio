<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{

    // Data show

    public function show()
    {
        $data = DB::table('blog')->get();
        return view('admin.blog', compact('data'));
    }

    // Store Data
    public function store(Request $request)
    {

        // Validate the incoming data
        $validatedData = $request->validate([

            'title' => 'required|string|max:255',
            'detail' => 'required|string',
            'date' => 'required|date',

            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $image = $request->file('image');
        $encodedImage = base64_encode(file_get_contents($image->getPathname()));


        DB::table('blog')->insert([

            'title' => $validatedData['title'],
            'details' => $validatedData['detail'],
            'date' => $validatedData['date'],

            'image' =>  $encodedImage,
        ]);



        return redirect('blog')->with('success', 'Home information added successfully!');
    }

    // GetData
    public function getData(string $id)
    {
        $data = DB::table('blog')->where('id', $id)->first();

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

            $oldImageData = DB::table('blog')
                ->where('id', $request->Edit_id)
                ->select('image')
                ->first();
            $encodedImage = $oldImageData->image ?? '';
        }

        // Update the database
        DB::table('blog')
            ->where('id', $request->Edit_id)
            ->update([

                'title' => $request->title,
                'details' => $request->detail,
                'date' => $request->date,

                'image' => $encodedImage,
            ]);

        return redirect('blog')->with('success', 'Home information updated successfully!');
    }


    //  delete data
    public function delete(Request $request)
    {
        dd($request->all());
        $home = DB::table('blog')->findOrFail($request->id);

        $home->delete();

        return redirect('blog')->with('success', 'Home information deleted successfully!');
    }
}
