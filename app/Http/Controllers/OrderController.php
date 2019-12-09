<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use Facades\App\Order;
Use Facades\App\Payment;
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
     * Show resume of the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resume(Request $request, $id)
    {

        try {

          $order = Order::find($id);

          return view('customer.resume-order', compact('order') );

        } catch (\Exception $e) {

          \Log::info( $e );
          return redirect( 'orders' )->with( 'warning', __('messages.generic_error') );

        }

    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request, $id)
    {

        try {

          $order = Order::find($id);

          $response = Payment::processPayment($order, $request);

          if($response['status']){

            // Se redirecciona al cliente a la pasarela de pagos
            return redirect()->to($response['process_url'])->send();

          }else{
            return redirect( 'orders' )->with( 'warning', __('messages.generic_error') );
          }


        } catch (\Exception $e) {

          \Log::info( $e );
          return redirect( 'orders' )->with( 'warning', __('messages.generic_error') );

        }

    }



    /**
     * Check payment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkPayment(Request $request, $id)
    {

        try {

            // Se obtiene el pago a consultar
            $payment = Payment::where('order_id', $id)
                              ->where('platform_status', Constant::PAYMENT_STATUS_PENDING)
                              ->whereNotNull('payment_code')
                              ->first();

            if($payment){

                // Se consulta el estado de pago en la pasarela de pagos
                $response = Payment::checkPayment($payment);

                if($response){
                    return redirect( 'orders' )->with( 'success', __('messages.approved_payment_info') );
                }else{
                    return redirect( 'orders' )->with( 'warning', __('messages.pending_payment_info') );
                }


              }


        } catch (\Exception $e) {
            \Log::info( $e );
            return redirect( 'orders' )->with( 'warning', __('messages.pending_payment_info') );
        }


    }



}
