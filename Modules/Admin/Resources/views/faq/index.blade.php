@extends('admin::layouts.main')

@section('breadcrumb')
<li class="active">Faqs</li>
@stop

@section('content')
<h3 class="page-title">Faqs
    <small>Manage all the faqs of the site from here</small>
</h3>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-question font-green-haze" aria-hidden="true"></i>
                    <span class="caption-subject font-green-haze bold uppercase">Faqs</span>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ Route('admin-createfaq') }}"><i class="fa fa-plus" aria-hidden="true"></i> Create</a>&nbsp;
                    <a class="btn btn-info" href="javascript:;" onclick="$('#search_filter').toggle();"><i class="fa fa-search" aria-hidden="true"></i> Filter</a>&nbsp;
                    <a class="btn btn-info" href="{{ Route('admin-faqs') }}"><i class="glyphicon glyphicon-repeat"></i></i> Reset</a>
                </div>
            </div>
            <div class="portlet-body form" id="search_filter" style="display: {{ ($search_filter == 1) ? 'block;' : 'none;' }}">
                <!-- BEGIN FORM-->
                <form class="form-horizontal" action="" method="GET">
                    <input type="hidden" name="search_filter" value="1"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Question</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="question" placeholder="Question" value="{{ (isset($question) && $question != '') ? $question : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Status</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="status">
                                            <option value="">Select Status</option>
                                            <option <?= (isset($status) && $status == 'all') ? 'selected' : ''; ?> value="all">Show All</option>
                                            <option <?= (isset($status) && $status == 'inactive') ? 'selected' : ''; ?> value="inactive">Inactive</option>
                                            <option <?= (isset($status) && $status == 'active') ? 'selected' : ''; ?> value="active">Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn green">Search</button>               
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                            <tr>
                                <th class="bold"> # </th>
                                <th class="bold"> Question </th>
                                <th class="bold">Added On </th>
                                <th class="bold"> Status </th>
                                <th class="bold" width="22%"> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($model as $key => $val)
                            <tr>
                                <td> {{ $key + 1 }} </td>
                                <td> {{ (isset($val->question) && $val->question != '') ? $val->question : "Not Given" }} </td>
                                <td> {{ (isset($val->created_at) && $val->created_at != '') ? date('jS M Y, g:i A', strtotime($val->created_at)) : "Not Found" }} </td>
                                <td> 
                                    @if($val->status == '0')
                                    <span class="label label-sm label-warning"> Inactive </span>
                                    @elseif($val->status == '1')
                                    <span class="label label-sm label-success"> Active </span>
                                    @else
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ Route('admin-viewfaq', ['id' => $val->id]) }}" class="btn btn-outline btn-circle btn-sm blue" data-toggle="tooltip" title="View">
                                        <i class="fa fa-eye"></i> 
                                    </a>
                                    <a href="{{ Route('admin-updatefaq', ['id' => $val->id]) }}" class="btn btn-outline btn-circle btn-sm purple" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-edit"></i> 
                                    </a>
                                    <a href="javascript:;" onclick="deleteFaq(this);" data-id="{{ $val->id }}" class="btn btn-outline btn-circle btn-sm dark" data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" style="text-align: center;">No Record Found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $model->appends(request()->all())->render() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="deletefaqModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #17C4BB;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color: white;">Delete Faq</h4>
            </div>
            <div class="modal-body">
                <h4>Do you want to delete this faq?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a id="deletefaqButton" class="btn btn-success" style="background-color: #17C4BB;">Yes</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@stop

@section('js')
<script>
    function deleteFaq(obj) {
        var id = $(obj).attr("data-id");
        var url = full_path + 'admin-deletefaq?id=' + id;
        $("#deletefaqButton").attr("href", url);
        $("#deletefaqModal").modal('show');
    }
</script>
@stop