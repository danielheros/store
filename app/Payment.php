<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Constant;

class Payment extends Model
{

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
   'order_id',
   'amount',
   'currency',
   'status',
   'platform_status',
   'ip',
   'payment_code',
   'payment_date',
   'payment_response',
   'process_url',
  ];



  /**
  * Obtiene el pedido al que pertenece en pago
  */
  public function order()
  {
    return $this->belongsTo('App\Order');
  }


  /**
  * Crea el intento de pago en la pasarela de pagos
  */
  public function processPayment($order, $request)
  {

    try {

      //Se guarda el registro con el intento de pago
      $payment = new Payment();
      $payment->order_id = $order->id;
      $payment->amount = Constant::ORDER_VALUE_DEFAULT;
      $payment->currency = Constant::ORDER_CURRENCY_DEFAULT;
      $payment->platform_status = Constant::PAYMENT_STATUS_PENDING;
      $payment->ip = $request->ip();

      if($payment->save()){

        /**
        * Se crea la instancia para la conexión con
        * Place to Pay
        */
        $placetopay = new \Dnetix\Redirection\PlacetoPay([
          'login' => env('P2P_LOGIN'),
          'tranKey' => env('P2P_TRANKEY'),
          'url' => env('P2P_SERVICE_URL'),
        ]);


        //Se define la información necesaria para enviar la petición
        $request = [
            'payment' => [
                'reference' => $payment->order_id,
                'description' =>'Compra en tienda en línea - Store SAS',
                'amount' => [
                    'currency' => $payment->currency,
                    'total' => $payment->amount,
                ],
            ],
            'buyer' => [
                'name' => $payment->order->customer_name,
                'email' => $payment->order->customer_email,
                'mobile' => $payment->order->customer_mobile,
            ],
            'expiration' => date('c', strtotime('+1 hour')),
            'returnUrl' =>  env('APP_URL') . '/orders/' . $payment->order_id . '/checkPayment',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];


        //Se envía la petición
        $response = $placetopay->request($request);


        // El intento de pago se registró correctamente
        if ($response->isSuccessful()) {

          //Se obtienen los datos de la respuesta
          $requestResponse['status'] = true;
          $requestResponse['process_url'] = $response->processUrl();
          $requestResponse['payment_code'] = $response->requestId();
          $requestResponse['message'] = $response->status()->message();

          //Se actualizan los campos del pago
          $payment->payment_code = $response->requestId();
          $payment->status = 1;
          $payment->process_url = $response->processUrl();
          $payment->save();


        } else {

          $requestResponse['status'] = false;

        }


      }


    } catch (\Exception $e) {
      \Log::info( $e );
      $requestResponse['status'] = false;
    }

    return $requestResponse;





  }


  /**
  * Consulta el estado de un pago
  * @param object $payment
  */
  public function checkPayment($payment)
  {

      try {

        $paymentStatus = Constant::PAYMENT_STATUS_PENDING;


        /**
        * Se crea la instancia para la conexión con
        * Place to Pay
        */
        $placetopay = new \Dnetix\Redirection\PlacetoPay([
          'login' => env('P2P_LOGIN'),
          'tranKey' => env('P2P_TRANKEY'),
          'url' => env('P2P_SERVICE_URL'),
        ]);

        //Se envía la petición
        $response = $placetopay->query($payment->payment_code);


        // La consulta se realizó correctamente
        if ($response->isSuccessful()) {


          if($response->payment){

            // El pago se encuentra aprobado
            if ($response->status()->isApproved()){

              // Se actualiza la información del pago
              $payment->platform_status = $response->status()->status();
              $payment->payment_date = date("Y-m-d H:i:s", strtotime($response->status()->date()) );
              $payment->payment_response = json_encode($response);
              $payment->save();

              $paymentStatus = Constant::PAYMENT_STATUS_APPROVED;

            }


            //Se actualiza el estado de la orden
            switch ($response->status()->status()) {

              case Constant::PAYMENT_STATUS_APPROVED:
                  $payment->order->status = Constant::ORDER_STATUS_PAYED;
                  $payment->order->save();
                break;

              case Constant::PAYMENT_STATUS_REJECTED:
                  $payment->order->save();
                  $payment->platform_status = $response->status()->status();
                  $payment->save();

                  $paymentStatus = Constant::PAYMENT_STATUS_REJECTED;
                break;

            }


          }


        }

        return $paymentStatus;


      } catch (\Exception $e) {
        \Log::info( $e );
        return false;
      }



    }


    /**
    * Consulta el estado de los pagos pendientes
    */
    public function checkPendingPayments()
    {

      try {

        // Se obtienen los pagos pendientes
        $pendingPayments = Payment::where('platform_status', Constant::PAYMENT_STATUS_PENDING)
                                  ->whereNotNull('payment_code')
                                  ->get();


        if($pendingPayments->count()){

          // Se recorren los pagos consultando su estado
          foreach ($pendingPayments as $pendingPayment) {
            Payment::checkPayment($pendingPayment);
          }

        }

      } catch (\Exception $e) {
        \Log::info( $e );
      }


    }


}
