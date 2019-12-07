
 @if (session('success'))

  <div class="row row-lg">
    <div class="col-xs-12 col-md-12 col-lg-12">
      <div class="alert dark alert-icon alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <i class="icon wb-check" aria-hidden="true"></i>
        {{ session('success') }}
      </div>
    </div>
  </div>
@endif
