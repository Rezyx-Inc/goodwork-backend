@extends('admin::layouts.master')

@section('content')

<!-- page content -->
<div class="">
    {{-- <div class="page-title">
        <div class="title_left">
            <h3>Nurses <small></small></h3>
        </div>

        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="clearfix"></div>

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Permissions</small></h2>
                {{-- <ul class="nav navbar-right panel_toolbox">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                        </div>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul> --}}
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <div class="row float-right">
                                <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                    <a class="btn btn-success" href="{{ route('permissions.create') }}">Add New</a>
                                    <a class="btn btn-info" href="javascript:void(0);" type="button">Export (csv)</a>
                                    <a class="btn btn-danger" href="javascript:void(0);" id="delete" type="button">Delete</a>
                                </div>
                            </div>
                            <table id="listing-table" class="table table-striped table-bordered bulk_action text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check_all" >
                                        </th>
                                        <th>#</th>
                                        <th>name</th>
                                        <th>Added on</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /page content -->
@stop
@section('js')
<!-- Datatable -->
<script>
    $(function() {
        $('#listing-table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: false,
            bSortable: true,
            bRetrieve: true,
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0,1,2,4] },
            { "bSearchable": false, "aTargets": [ 0,1,4] }
            ],
            ajax: "{!! route('permissions.index') !!}",
            columns: [
                { data: 'checkbox', name: 'checkbox',orderable:false, sortable:false},
                { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false},
                { data: 'name', name: 'name' },
                // { data: 'guard_name', name: 'guard_name'},
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action' ,orderable:false},
            ]
        });
    });
    </script>
    <script>
    // check all
    $("#check_all").click(function(){
      if($("#check_all").prop("checked")){
        $("input[type=checkbox]").prop("checked",true);
        //or $(":checkbox").prop("checked",true);
      }else{
        $("input[type=checkbox]").prop("checked",false);
      }
    })
    </script>

    <!-- deleting job  -->
    <script>
    $(document).ready(function(){
        $('#delete').click(function(){
            var input = [];
            $(".ids:checked").each(function(){
                input.push($(this).val());
            });
            if(input.length){
                $.confirm({
                    title: 'Delete Permission',
                    content: 'Are you sure?',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        confirm: {
                            text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                            btnClass: 'btn-red',
                            action: function () {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    url: '{{route("delete-permission")}}',
                                    // url:url,
                                    type: 'POST',
                                    dataType: 'json',
                                    // processData: false,
                                    // contentType: false,
                                    data: {
                                        ids: input
                                    },
                                    success: function (data) {
                                        console.log(data);
                                        if (data.success) {
                                            notie.alert({
                                                type: 'success',
                                                text: '<i class="fa fa-check"></i> ' + data.msg,
                                                time: 3
                                            });
                                            $('#listing-table').DataTable().ajax.reload(null, false);
                                        }else{
                                            notie.alert({
                                                type: 'error',
                                                text: '<i class="fa fa-check"></i> '+data.msg,
                                                time: 5
                                            });
                                        }
                                    },
                                    error: function (resp) {
                                        console.log(resp);
                                    }
                                });
                            }
                        },
                        cancel: function () {}
                    }
                })
            }else{
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Please select at least one record.',
                    time: 5
                });
            }
        });

    });
    </script>

@stop
