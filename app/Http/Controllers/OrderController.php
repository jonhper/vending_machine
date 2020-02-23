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



}
