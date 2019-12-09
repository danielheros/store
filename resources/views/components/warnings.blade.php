
 @if (session('warning'))

  <div class="row row-lg">
    <div class="col-xs-12 col-md-12 col-lg-12">
      <div class="alert dark alert-icon alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <i class="icon wb-close" aria-hidden="true"></i>
        {{ session('warning') }}
      </div>
    </div>
  </div>
@endif
