<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Machine;
use App\OrderCoins;

class OrderCoinController extends Controller
{

    public function getOrderCoins($idOrder)
    {
        return OrderCoins::where('idOrder',$idOrder)->get();
    }

    public function addOrderCoins($idOrder, Request $request)
    {
        $orderCoin = new OrderCoins();
        $orderCoin->idOrder = $idOrder;
        $orderCoin->coin = $request->post('coin');
        if(!$orderCoin->save()){
            App::abort(HTTP_INTERNAL_SERVER_ERROR, 'Error adding coins');
        }

        // Set response
        return array(
                      'idOrder' => $idOrder,
                      'statusMachine' => Machine::statusMachine($idOrder)
                    );
    }

    public function deleteOrderCoins($idOrder)
    {
        // Get coins inserted
        $orderCoinsReturn = OrderCoins::where('idOrder',$idOrder)->get();
        // Delete coins inserted
        $orderCoin = OrderCoins::where('idOrder',$idOrder);
        $orderCoin->delete();

        // Set response
        return array(
                      'idOrder' => $idOrder,
                      'orderCoinsReturn' => $orderCoinsReturn,
                      'statusMachine' => Machine::statusMachine($idOrder)
                    );
    }












}
