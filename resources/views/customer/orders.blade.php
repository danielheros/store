@extends('adminlte::page')

@section('title', 'AdminLTE')


@section('content_header')
    <h1>{{ __('messages.orders') }}</h1>
@stop


@section('content')

  @component('components.success') @endcomponent
	@component('components.warnings') @endcomponent
  @component('components.errors') @endcomponent

    <a href="/orders/create">
      <button type="button" class="btn btn-primary">
        {{ __('messages.order_create') }}
      </button>
    </a>
    <br><br>

    <form class="form-inline">
      <div class="form-group">
        <input type="text" class="form-control"
        id="filter" name="filter" value="{{ isset($filter) ? $filter : "" }}" placeholder="{{ __('messages.search') }}" autocomplete="off">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
      </div>
    </form>


  @component('components.table')

    @slot('columns')
      <th> {{ __('messages.order_id') }}</th>
      <th> {{ __('messages.status') }} </th>
      <th> {{ __('messages.order_date') }}</th>
      <th> {{ __('messages.payment_reference') }}</th>
      <th> {{ __('messages.payment_date') }}</th>
      <th class="text-nowrap">{{ __('messages.actions') }}</th>
    @endslot

    @foreach ($orders as $order)

      <tr>

        <td>{{ $order->id }}</td>
        <td>{{ $order->status }}</td>
        <td>{{ $order->created_at }}</td>

        <td>{{ $order->getPaymentReference() }}</td>
        <td>{{ $order->getPaymentDate() }}</td>

        <td>


          @if( $order->status == \App\Constant::ORDER_STATUS_CREATED)

            <form action="/orders/{{$order->id}}/resume">
              <div class="form-group">
                  <button type="submit" class="btn btn-primary">
                    {{ __('messages.pay') }}
                  </button>
              </div>
            </form>

          @elseif( $order->getProcessUrlPendingPayment() )

            <a href="{{ $order->getProcessUrlPendingPayment() }}">
              <button type="button" class="btn btn-primary">
                {{ __('messages.return_to_payment') }}
              </button>
            </a>

          @endif


        </td>

      </tr>

    @endforeach

  @endcomponent


  @component('components.pagination')
		{{ $orders->appends([['filter' => isset($filter) ? $filter : "" ]])->links() }}
	@endcomponent

@stop
