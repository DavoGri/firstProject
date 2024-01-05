<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
       User::find();
    }


    public function store(Request $request)
    {


    }


    public function update(Request $request, $id)
    {


    }


    public function delete($id)
    {

    }


    public function show($id)
    {

    }
}