<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    // Data show

    public function show()
    {
        $data = DB::table('home')->get();
        return view('admin.home', compact('data'));
    }

    // Store Data
    public function store(Request $request)
    {

        // Validate the incoming data
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'detail' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $image = $request->file('image');
        $encodedImage = base64_encode(file_get_contents($image->getPathname()));


        DB::table('home')->insert([
            'company_name' => $validatedData['company_name'],
            'title' => $validatedData['title'],
            'details' => $validatedData['detail'],
            'image' =>  $encodedImage,
        ]);



        return redirect('home')->with('success', 'Home information added successfully!');
    }

    // GetData
    public function getData(string $id)
    {
        $data = DB::table('home')->where('id', $id)->first();

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

            $oldImageData = DB::table('home')
                ->where('id', $request->Edit_id)
                ->select('image')
                ->first();
            $encodedImage = $oldImageData->image ?? '';
        }

        // Update the database
        DB::table('home')
            ->where('id', $request->Edit_id)
            ->update([
                'company_name' => $request->company_name,
                'title' => $request->title,
                'details' => $request->detail,
                'image' => $encodedImage,
            ]);

        return redirect('home')->with('success', 'Home information updated successfully!');
    }


    //  delete data
    public function delete(Request $request)
    {
        dd($request->all());
        $home = DB::table('home')->findOrFail($request->id);

        $home->delete();

        return redirect('home')->with('success', 'Home information deleted successfully!');
    }
}
