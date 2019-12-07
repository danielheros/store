<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use Facades\App\Order;
use App\Constant;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


      $filter = $request->filter;

      $filterFormated = isset( $request->filter ) ? '%' . $request->filter . '%' : '%';


      $orders = Order::where( 'customer_name', 'LIKE', $filterFormated )
                          ->orWhere( 'customer_email', 'LIKE', $filterFormated )
                          ->orWhere( 'customer_mobile', 'LIKE', $filterFormated )
                          ->orderBy('created_at','DESC')
                          ->paginate(10);


      return view('admin.orders', compact('orders', 'filter') );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create-order', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      try {

        $fieldsRequest = $request->only(['customer_name', 'customer_email', 'customer_mobile']);

        $fieldsRequest['status'] = Constant::ORDER_STATUS_CREATED;

        // Se crea la orden
        $order =  new \App\Order;
        $order->fill( $fieldsRequest );
        $order->save();

        return redirect( 'orders' )->with( 'success', __('messages.saved_order_ok') );


      } catch (\Exception $e) {

        \Log::info( $e );
        return redirect( 'orders' )->with( 'warning', __('messages.generic_error') );

      }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}