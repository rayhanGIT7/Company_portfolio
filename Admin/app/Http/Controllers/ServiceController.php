<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{

    // Data show

    public function show()
    {
        $data = DB::table('service')->get();
        return view('admin.service', compact('data'));
    }

    // Store Data
    public function store(Request $request)
    {

    //    dd($request->all()) ;
        // Validate the incoming data
        $validatedData = $request->validate([

            'service_name' => 'required|string',
            'service_detail' => 'required|string',
            'service_category' => 'required|array|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);





        $image = $request->file('image');
        $encodedImage = base64_encode(file_get_contents($image->getPathname()));


        DB::table('service')->insert([

            'service_name' => $validatedData['service_name'],
            'service_detail' => $validatedData['service_detail'],
            'service_category' => implode('  ✓  ', $validatedData['service_category']),

            'image' =>  $encodedImage,
        ]);



        return redirect('service')->with('success', 'Home information added successfully!');
    }

    // GetData
    public function getData(string $id)
    {
        $data = DB::table('service')->where('id', $id)->first();

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

            $oldImageData = DB::table('service')
                ->where('id', $request->Edit_id)
                ->select('image')
                ->first();
            $encodedImage = $oldImageData->image ?? '';
        }

        // Update the database
        DB::table('service')
            ->where('id', $request->Edit_id)
            ->update([


                'service_name' => $request->service_name,
                'service_detail' => $request->service_detail,
                'image' => $encodedImage,
            ]);

        return redirect('service')->with('success', 'Home information updated successfully!');
    }


    //  delete data
    public function delete(Request $request)
    {
        $id = $request->id;

        // Use the column name and value properly in the where clause
        DB::table('service')
            ->where('id', $id) // Assuming 'id' is the column name
            ->delete();

        return redirect('service')->with('success', 'Home information deleted successfully!');
    }

}
