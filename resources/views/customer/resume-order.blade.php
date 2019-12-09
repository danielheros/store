@extends('adminlte::page')

@section('title', 'AdminLTE')


@section('content_header')
    <h1>{{ __('messages.resume_order') }}</h1>
@stop


@section('content')

  @component('components.success') @endcomponent
	@component('components.warnings') @endcomponent
  @component('components.errors') @endcomponent

  <div class="panel-body container-fluid">

    <div class="div-wrap">


      @component('components.table')

        @slot('columns')
          <tr>
            <th> {{ __('messages.customer_name') }}</th>
            <td> {{ $order->customer_name }} </td>
          </tr>

        @endslot
        <tr>
          <th>{{ __('messages.customer_email') }}</th>
          <td>{{ $order->customer_email }}</td>
        </tr>

        <tr>
          <th>{{ __('messages.customer_mobile') }}</th>
          <td>{{ $order->customer_mobile }}</td>
        </tr>

        <tr>
          <th>{{ __('messages.value') }}</th>
          <td>{{\App\Constant::ORDER_CURRENCY_DEFAULT .' '. App\Constant::ORDER_VALUE_DEFAULT}}</td>
        </tr>



      @endcomponent


      <form action="/orders/{{$order->id}}/payment" method="POST">
        @method('POST')
        @csrf
        <div class="form-group">

            <button type="submit" class="btn btn-primary">
              {{ __('messages.make_payment') }}
            </button>

        </div>
      </form>


    </div>

  </div>


@stop
