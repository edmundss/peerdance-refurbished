@extends('layouts.fullwidthv1')

@section('content')
    <div class="row">
      <div class="col-xs-12">
        <div class="card card-data-tables">
          <header class="card-heading">
            <div class="card-search">
              <div id="productsTable_wrapper" class="form-group label-floating is-empty">
                <i class="zmdi zmdi-search search-icon-left"></i>
                <input type="text" class="form-control filter-input" placeholder="Search..." autocomplete="off">
                <a href="javascript:void(0)" class="close-search" data-card-search="close" data-toggle="tooltip" data-placement="top" title="Close"><i class="zmdi zmdi-close"></i></a>
              </div>
            </div>
            <ul class="card-actions icons right-top">
              <li>
                <a href="javascript:void(0)" data-card-search="open" data-toggle="tooltip" data-placement="top" data-original-title="Filter">
                  <i class="zmdi zmdi-filter-list"></i>
                </a>
              </li>
              <li class="dropdown" data-toggle="tooltip" data-placement="top" data-original-title="Show Entries">
                <a href="javascript:void(0)" data-toggle="dropdown">
                  <i class="zmdi zmdi-more-vert"></i>
                </a>
                <div id="dataTablesLength">
                </div>
              </li>
            </ul>
          </header>
          <div class="card-body p-0">
            <div class="alert alert-info m-20 hidden-md hidden-lg" role="alert">
              <p>
                Heads up! You can Swipe table Left to Right on Mobile devices.
              </p>
            </div>
            <div class="table-responsive">
              <table id="building-index" class="mdl-data-table product-table m-t-30" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="">Song</th>
                    <th class="">Artist</th>
                    <th class="">
                        Dances
                        <button class="btn btn-primary btn-fab  animate-fab" data-toggle="modal" data-target="#basic_modal"><i class="zmdi zmdi-plus"></i></button>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop

@section('scripts')

<script>
    var timeout = null;
    var formatted_address = null;
    var coordinates = null;

    $(function(){
        var oTable = $('#building-index').DataTable({
          	stateSave: true,
	        processing: true,
	        serverSide: true,
	        bfilter: false,
	        ajax: "{{route('songs.datatable')}}",
	        columns: [
                { data: 'name', name: 'songs.name'},
                { data: 'artist', name: 'artists.name'},
                { data: 'dances', name: 'dances.title'},
	        ]
        });
        $('.filter-input').keyup(function () {
      	    oTable.search($(this).val()).draw();
        });

      var $lengthSelect = $(".card.card-data-tables select.form-control.input-sm");
      var tableLength = $lengthSelect.detach();
      $('#dataTablesLength').append(tableLength);
      $(".card.card-data-tables .card-actions select.form-control.input-sm").dropdown({
          "optionClass": "withripple"
      });
      $('#dataTablesLength .dropdownjs .fakeinput').hide();
      $('#dataTablesLength .dropdownjs ul').addClass('dropdown-menu dropdown-menu-right');
    })
</script>
@stop


@section('modals')
    <div class="modal fade" id="basic_modal" tabindex="-1" role="dialog" aria-labelledby="basic_modal">
        <div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel-2">Add new Song</h4>
    				<ul class="card-actions icons right-top">
    					<a href="javascript:void(0)" data-dismiss="modal" class="text-white" aria-label="Close">
    		               <i class="zmdi zmdi-close"></i>
    				    </a>
    				</ul>
    			</div>
          {{Form::open(['url' => route('songs.store'), 'method' => 'POST'])}}
          <div class="modal-body">
				  </div>
  				<div class="modal-footer">
  					<button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
  					<input type="submit" class="btn btn-primary" value="Submit"/>
  				</div>
                {{Form::close()}}
    		</div>
    					<!-- modal-content -->
    	</div>
    				<!-- modal-dialog -->
    </div>
@stop
