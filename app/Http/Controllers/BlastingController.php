<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlastingController extends Controller
{
    public function index()
    {
        return view('blasting.index');
    }

    public function editIndex()
    {
        return view('blasting.edit-index');
    }

    public function create()
    {
        return view('blasting.add-edit');
    }

    public function store(Request $request)
    {
        //
    }

    public function edit()
    {
        return view('blasting.add-edit');        
    }

    public function update(Request $request)
    {
        //
    }

}
