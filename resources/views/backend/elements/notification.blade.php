@if (Session::has('success'))
    <div class="row">
	    <div class="col-md-12 alert alert-<?=Session::get('mode');?> alert-dismissible" role="alert">
		    {{ Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
    </div>
@endif 
<!-- 
@if (Session::has('success'))
    <div class="row">
        <div class="col-md-12 alert alert-success alert-dismissible" role="alert">
            <i class="fa fa-check-circle"></i> {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@elseif (Session::has('error'))
    <div class="row">
        <div class="col-md-12 alert alert-danger alert-dismissible" role="alert">
            <i class="fa fa-exclamation-circle"></i> {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
 -->