<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Machine;
use App\Product;

class ProductController extends Controller
{

    public function getProducts()
    {
        return Product::all();
    }












}
