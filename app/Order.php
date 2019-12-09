<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Facades\App\Payment;
use App\Constant;

class Order extends Model
{

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
   'customer_name',
   'customer_email',
   'customer_mobile',
   'status',
  ];


  /**
  * Retorna la referencoa de pago del pedido
  * en caso de que el pedido tengo un pago aprobado
  */
  public function getPaymentReference(){

    $paymentReference = '';

    $approvedPayment = Payment::where('order_id', $this->id)
                              ->where('platform_status', Constant::PAYMENT_STATUS_APPROVED)
                              ->first();

    if($approvedPayment){
      $paymentReference = $approvedPayment->payment_code;
    }

    return $paymentReference;


  }


  /**
  * Retorna la fecha de pago del pedido
  * en caso de que el pedido tengo un pago aprobado
  */
  public function getPaymentDate(){

    $paymentDate = '';

    $approvedPayment = Payment::where('order_id', $this->id)
                              ->where('platform_status', Constant::PAYMENT_STATUS_APPROVED)
                              ->first();

    if($approvedPayment){
      $paymentDate = $approvedPayment->payment_date;
    }

    return $paymentDate;


  }


  /**
  * Retorna la url para continuar con el pago
  */
  public function getProcessUrlPendingPayment(){

    $processUrl = false;

    $pendingPayment = Payment::where('order_id', $this->id)
                              ->where('platform_status', Constant::PAYMENT_STATUS_PENDING)
                              ->whereNotNull('payment_code')
                              ->whereNotNull('process_url')
                              ->first();

    if($pendingPayment){
      $processUrl = $pendingPayment->process_url;
    }

    return $processUrl;


  }






}
