
<div class="row">

  <div class="col-xs-12 col-lg-12">


    <div class="data">

    	<h4 class="data-title">
    		@if ( isset($title) )
    			{{ $title }}
    		@endif
    	</h4>


      <div class="data table-responsive">

        <table class="table table-bordered table-striped table-hover"
        @if ( isset($idTable) )	id="{{ $idTable }}" @endif >

          <thead>
            <tr>
            	{{ $columns }}
            </tr>
          </thead>

          <tbody>
          	{{ $slot }}
          </tbody>

        </table>

      </div>

    </div>

  </div>

</div>
