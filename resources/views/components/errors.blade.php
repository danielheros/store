@if ($errors->any())
 <div class="row row-lg">
   <div class="col-xs-12 col-md-12 col-lg-12">
     <div class="alert dark alert-danger alert-dismissible" role="alert">
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">×</span>
       </button>
       <ul>
           @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
           @endforeach
       </ul>
     </div>
   </div>
 </div>
@endif
