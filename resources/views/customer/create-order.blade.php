@extends('adminlte::page')

@section('title', 'AdminLTE')


@section('content_header')
    <h1>{{ __('messages.order_create') }}</h1>
@stop


@section('content')

  @component('components.success') @endcomponent
	@component('components.warnings') @endcomponent
  @component('components.errors') @endcomponent

  <div class="panel-body container-fluid">

    <div class="div-wrap">


        <form action="/orders" method="POST">

          @method('POST')
          @csrf


          <div class="row">

            <div class="form-group col-xs-12 col-md-3">
              @component('components.forms.form-item-text')
                @slot('title') {{ __('messages.customer_name') }} *@endslot
                @slot('placeholder') {{ __('messages.customer_name') }} @endslot
                @slot('name') customer_name @endslot
                @slot('value') {{old('customer_name')}} @endslot
                @slot('required') true @endslot
              @endcomponent
            </div>

          </div>


          <div class="row">

            <div class="form-group col-xs-12 col-md-3">
              @component('components.forms.form-item-email')
                @slot('title') {{ __('messages.customer_email') }} *@endslot
                @slot('placeholder') {{ __('messages.customer_email') }} @endslot
                @slot('name') customer_email @endslot
                @slot('value') {{old('customer_email')}} @endslot
                @slot('required') true @endslot
              @endcomponent
            </div>

          </div>


          <div class="row">

            <div class="form-group col-xs-12 col-md-3">
              @component('components.forms.form-item-number')
                @slot('title') {{ __('messages.customer_mobile') }} *@endslot
                @slot('placeholder') {{ __('messages.customer_mobile') }} @endslot
                @slot('name') customer_mobile @endslot
                @slot('value') {{old('customer_mobile')}} @endslot
                @slot('required') true @endslot
              @endcomponent
            </div>

          </div>



          <div class="row">
            <div class="form-group col-xs-12 col-md-4 offset-md-0">
              <button type="submit" class="btn btn-primary">{{ __('messages.order_create') }}</button>
            </div>
          </div>


        </form>



    </div>

  </div>


@stop
