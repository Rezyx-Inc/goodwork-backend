@extends('admin::layouts.master')

@section('content')

<!-- page content -->
<!-- <div class="">
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
                <h2>Keywords</small></h2>
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
                                    <a class="btn btn-success" href="{{ route('keywords.create') }}">Add New</a>
                                    <a class="btn btn-danger" href="javascript:void(0);" id="delete" type="button">Delete</a>
                                </div>
                            </div>
                            <table id="listing-table" class="table table-striped table-bordered bulk_action text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check_all" >
                                        </th>
                                        <th>Title</th>
                                        <th>Filter</th>
                                        <th>Added by</th>
                                        {{-- <th>Description</th> --}}
                                        <th>Status</th>
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

</div> -->


<div class="ss-dsh-workr-pg-mn-dv ss-admin-keybrd-pg-mn-dv">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h4>Keywords</h4>
            </div>

            <div class="col-lg-8">
                <div class="ss-role-pg-hed-btndv">
                    <ul>
                        <li>
                            <div class="ss-dash-serch-bx">
          <div class="input-group">
  <div class="form-outline">
    <input type="search" id="form1" class="form-control placeholder-active" placeholder="Search anything...">
  <div class="form-notch"><div class="form-notch-leading" style="width: 9px;"></div><div class="form-notch-middle" style="width: 0px;"></div><div class="form-notch-trailing"></div></div></div>
  <button type="button" class="ssbtn">
    <i class="fa fa-search" aria-hidden="true"></i>
  </button>
</div>
        </div>
                        </li>
                        <li>
                            <button class="ss-export-btn-sec"><img src="{{URL::asset('backend/assets/images/filter-img.png')}}">Filter</button>
                        </li>
                        
                        <li><button class="ss-add-role-btn" data-toggle="modal" data-target="#exampleModal">Add Keyword</button></li>
                        <li><button class="ss-rol-delete-btn">Delete</button></li>
                    </ul>
                </div>
            </div>
        </div>



        <!----------->

        <div class="ss-pg-role-table-dv">
            <div class="row">
                <div class="col-md-12">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <p>Title </p></div></th>
      <th scope="col">Filter</th>
      <th scope="col">Status</th>
        <th scope="col">Rate<span>($/hr)</span></th>
        <th scope="col">Added on</th>
         <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

      <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

       <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

      <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

       <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

       <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

<tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

      <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

       <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>

      <tr>
      <td>
        <div class="ss-rol-tbl-chck"><input class="form-check-input" type="checkbox"> <a href="#">I have to have</a></div></td>
      <td>401k</td>
      <td><button class="ss-key-pg-actv-btn">Active</button></td>
      <td><p class="ss-admin-jb-rate">$26</p></td>
   <td>10/10/2023 07:37 AM</td>
   <td><button class="ss-admin-job-tbl-ed-btn">Edit</button></td>
    </tr>
    
  </tbody>
</table>
</div>
            </div>
        </div>

    </div>

</div>






<!-- Modal -->
<div class="modal fade ss-add-wrkr-popup-mn-dv ss-admin-pop-mn-dv" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-hed">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div>
          <h4>Add New Worker</h4>
          <form>
              
               <div class="ss-form-group">
                      <label>Title<span>*</span></label>
                      <input type="text" name="f-namr" placeholder="Enter Title">
                  </div>
                   <div class="ss-form-group">
                <label>Filter <span>*</span></label>
                 <select name="cars" id="cars">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select>
              </div>

              <div class="ss-form-group">
                <label>Description <span>*</span></label>
                 <textarea id="w3review" name="w3review" rows="4" cols="50" placeholder="Write Description...">
</textarea>
              </div>

              
             

              


<div class="ss-rrol-pop-swtch-dv">
    <ul>
    <li><p>Status</p></li>
    <li><input type="checkbox" hidden="hidden" id="username">
<label class="switch" for="username"></label></li>
</ul>
</div>

<div class="ss-rrol-pop-btns-dv">
    <ul>
        <li>
            <button class="ss-rol-cancl-btn">Cancel</button>
        </li>
         <li>
            <button class="ss-rol-submit-btn">Submit</button>
        </li>
    </ul>
</div>

            </div>
          </form>
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
            aaSorting: [[2,"ASC"]],
            bSortable: true,
            bRetrieve: true,
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0,1,2,3,6] },
            { "bSearchable": false, "aTargets": [ 0,6] }
            ],
            ajax: "{!! route('keywords.index') !!}",
            columns: [
                { data: 'checkbox', name: 'checkbox',orderable:false, sortable:false},
                // { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false},
                { data: 'title', name: 'title' },
                { data: 'filter', name: 'filter' },
                { data: 'user_name', name: 'user_name' },
                // { data: 'description', name: 'description' },
                { data: 'active', name: 'active',  render: function (data, type, row) {
                            if (data == '0') {
                                return '<span class="label label-sm text-warning">Inactive</span>';
                            } else if (data == '1') {
                                return '<span class="label label-sm text-success">Active</span>';
                            }  else {
                                return '';
                            }
                        }
                    },
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
                    title: 'Delete Keyword',
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
                                    url: '{{route("delete-keyword")}}',
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
