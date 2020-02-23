<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Machine;
use App\Order;

class OrderController extends Controller
{

    public function getOrder($idOrder)
    {
        return Order::find($idOrder);
    }


    public function addOrder(Request $request)
    {
        $order = new Order();
        $order->productName = $request->post('productName');
        $order->status = $request->post('status');
        $order->save();
        if(!$order->id){
            App::abort(HTTP_INTERNAL_SERVER_ERROR, 'Error saving order');
        }

        // Set response
        return array(
                      'idOrder' => $order->id,
                      'statusMachine' => Machine::statusMachine($order->id)
                    );

    }

    public function paymentOrder($idOrder, Request $request)
    {

      $productName = $request->post('productName');
      $orderChangeCoins = "";
      // Check product
      $product = Product::where('name',$productName)->first();
      if(!$product){
        return array(
                      'idOrder' => $idOrder,
                      'status'  => 'Error',
                      'message' => 'Product not found',
                      'statusMachine' => Machine::statusMachine($idOrder)
                    );
      }
      // Check product available
      if($product['items'] < 1){
        return array(
                      'idOrder' => $idOrder,
                      'status'  => 'Error',
                      'message' => 'Product not available',
                      'statusMachine' => Machine::statusMachine($idOrder)
                    );
      }

      // Check orderCoins
      $orderCoinsSum = OrderCoins::where('idOrder',$idOrder)->get()->sum("coin");
      if(!$orderCoinsSum){
        return array(
                      'idOrder' => $idOrder,
                      'status'  => 'Error',
                      'message' => 'No inserted coins',
                      'statusMachine' => Machine::statusMachine($idOrder)
                    );
      }

      // Check current balance
      if($orderCoinsSum < $product['price']){
        return array(
                      'idOrder' => $idOrder,
                      'status'  => 'Error',
                      'message' => 'Insufficient balance',
                      'statusMachine' => Machine::statusMachine($idOrder)
                    );
      }

      // Add new coins
      $orderCoins = OrderCoins::where('idOrder',$idOrder)->get();
      foreach ($orderCoins as $key => $orderCoin) {
        $coin = Coin::where('coin', $orderCoin['coin'])->first();
        $coin->items = $coin['items'] + 1;
        $coin->save();
      }

      // Check chargeback
      $change = number_format($orderCoinsSum - $product['price'],2);
      // Need chargeback
      if($change > 0){
        // Check return coins available
        $orderChangeCoins = $this->getOrderChange($change);
        // No coins for changeback
        if(empty($orderChangeCoins)){
          // restore coins available
          foreach ($orderCoins as $key => $orderCoin) {
            $coin = Coin::where('coin', $orderCoin['coin'])->first();
            $coin->items = $coin['items'] - 1;
            $coin->save();
          }
          // Response error
          return array(
                        'idOrder' => $idOrder,
                        'status'  => 'Error',
                        'message' => 'No coins for changeback',
                        'statusMachine' => Machine::statusMachine($idOrder)
                      );
        }
      }

      // Remove one item from product
      $product->items = $product['items'] - 1;
      $product->save();

      // Create new order
      $order = new Order();
      $order->status = 'New';
      $order->save();

      // Set response
      return array(
                    'idOrder' => $order->id,
                    'status'  => 'Success',
                    'message' => $product['name'],
                    'change'  => $change,
                    'changeCoins' => $orderChangeCoins,
                    'statusMachine' => Machine::statusMachine($order->id)
                  );
    }

    public function getOrderChange($change)
    {
      $result = array();
      $currentChange = $change;
      $counterChange = 0;
      // Get all coins available
      $coins = Coin::where('items','>', 0)->orderBy('coin', 'desc')->get();
      foreach ($coins as $key => $coin) {
        // Check if current coin can be use
        $coinDivider = $currentChange/$coin['coin'];
        if($coinDivider >= 1){
          // Get coins number
          $integerValue = explode(".", $coinDivider);
          $coinsNumber = $integerValue[0];
          // Coins available for change
          if($coinsNumber <= $coin['items']){
            // Set new coins available
            $setCoin = Coin::where('coin', $coin['coin'])->first();
            $setCoin->items = $coin['items'] - $coinsNumber;
            $setCoin->save();
            // Update current change
            $currentChange = number_format($currentChange - ($coin['coin'] * $coinsNumber),2);
            // Set counter change for check
            $counterChange = $counterChange + ($coin['coin'] * $coinsNumber);
            // Add result coins
            $result[] = array('coin' => $coin['coin'], 'coinsNumber'=>$coinsNumber, 'counterChange'=>$counterChange);
          }
        }
      }
      // Check return coins available
      if($counterChange < $change){
        return false;
      }
      // Return change coins
      return $result;
    }



}
