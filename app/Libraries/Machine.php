<?php
namespace App\Libraries;

use App\Product;
use App\Coin;
use App\OrderCoins;

class Machine
{
  public static function statusMachine($idOrder = false)
  {
    // Get products
    $products = Product::all();

    // Get coins
    $coins = Coin::all();

    // Get coins inserted
    $orderCoins = OrderCoins::where('idOrder',$idOrder)->get();

    // Set response
    return array(
                  'products'     => $products,
                  'coins'       => $coins,
                  'orderCoins'  => $orderCoins
                );
  }

}
