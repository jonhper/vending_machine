<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coin;

class CoinController extends Controller
{

    public function getCoins()
    {
        
        return Coin::all();
    }












}
