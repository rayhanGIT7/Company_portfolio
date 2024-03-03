<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FontendContriller extends Controller
{
public function show(){
    $home = DB::table('home')->get();
    $about = DB::table('about')->get();
    $service = DB::table('service')->get();
    $project = DB::table('project')->get();
    $blog = DB::table('blog')->get();

    return view('index', compact('home', 'about','service','project','blog'));

}
public function store(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);

    // Insert data using the query builder
    DB::table('contact')->insert([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'subject' => $validatedData['subject'],
        'massage' => $validatedData['message'],

    ]);

    // Redirect back with success message
    return redirect()->back()->with('success', 'Message sent successfully!');
}
}
