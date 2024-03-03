<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{

    // Data show

    public function show()
    {
        $data = DB::table('project')->get();
        return view('admin.project', compact('data'));
    }

    // Store Data
    public function store(Request $request)
    {
// dd($request->all());
        // Validate the incoming data
        $validatedData = $request->validate([

            'title' => 'required|string|max:255',
            'name' => 'required|string',

            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $image = $request->file('image');
        $encodedImage = base64_encode(file_get_contents($image->getPathname()));


        DB::table('project')->insert([

            'title' => $validatedData['title'],
            'name' => $validatedData['name'],

            'image' =>  $encodedImage,
        ]);



        return redirect('project')->with('success', 'Home information added successfully!');
    }

    // GetData
    public function getData(string $id)
    {
        $data = DB::table('project')->where('id', $id)->first();

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

            $oldImageData = DB::table('project')
                ->where('id', $request->Edit_id)
                ->select('image')
                ->first();
            $encodedImage = $oldImageData->image ?? '';
        }

        // Update the database
        DB::table('project')
            ->where('id', $request->Edit_id)
            ->update([

                'title' => $request->title,
                'name' => $request->name,

                'image' => $encodedImage,
            ]);

        return redirect('project')->with('success', 'Home information updated successfully!');
    }


    //  delete data
    public function delete(Request $request)
    {
        dd($request->all());
        $home = DB::table('project')->findOrFail($request->id);

        $home->delete();

        return redirect('project')->with('success', 'Home information deleted successfully!');
    }
}
