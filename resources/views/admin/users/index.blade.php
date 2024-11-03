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
              <table id="index-table" class="mdl-data-table product-table m-t-30" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="">Name</th>
                    <th class="">Email</th>
                    <th class="">Roles
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

    $(function(){
        var oTable = $('#index-table').DataTable({
          	stateSave: true,
	        processing: true,
	        serverSide: true,
	        bfilter: false,
	        ajax: "{{route('admin.user.datatable')}}",
	        columns: [
                { data: 'name', name: 'users.name'},
                { data: 'email', name: 'users.email'},
                { data: 'roles', name: 'roles.display_name'},
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
