@extends('admin::layouts.main')

@section('breadcrumb')
<li class="active">Contact Us</li>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-phone font-green-haze" aria-hidden="true"></i>
                    <span class="caption-subject font-green-haze bold uppercase">Contact Us</span>
                </div>
                <div class="pull-right">
                    <a class="btn btn-info" href="javascript:;" onclick="$('#search_filter').toggle();"><i class="fa fa-search" aria-hidden="true"></i> Filter</a>&nbsp;
                    <a class="btn btn-info" href="{{ Route('admin-contact') }}"><i class="glyphicon glyphicon-repeat"></i></i> Reset</a>
                </div>
            </div>
            <div class="portlet-body form" id="search_filter" style="display: {{ ($search_filter == 1) ? 'block;' : 'none;' }}">
                <form class="form-horizontal" action="" method="GET">
                    <input type="hidden" name="search_filter" value="1"/>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="name" placeholder="Full name" value="{{ (isset($name) && $name != '') ? $name : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email</label>
                                    <div class="col-md-8">
                                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ (isset($email) && $email != '') ? $email : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Phone</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{ (isset($phone) && $phone != '') ? $phone : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Status</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="status">
                                            <option value="">Select All</option>
                                            <option value="0" {{ (isset($status) && $status != '') ? (($status === '0') ? 'selected' : '') : '' }}>Not Replied</option>
                                            <option value="1" {{ (isset($status) && $status != '') ? (($status === '1') ? 'selected' : '') : '' }}>Replied</option>
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
                                <th class="bold"> Name </th>
                                <th class="bold"> Email </th>
                                <th class="bold"> Phone</th>
                                <th class="bold"> Added Date </th>
                                <th class="bold"> Status </th>
                                <th class="bold" width="10%"> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($model) > 0)
                            @foreach ($model as $key => $val)
                            <tr>
                                <td> {{ $key + 1 }} </td>
                                <td> {{ (isset($val->name) && $val->name != '') ? $val->name : "Not Given" }} </td>
                                <td> {{ $val->email }} </td>
                                <td> {{ (isset($val->phone_no) && $val->phone_no != '') ? $val->phone_no : "Not Given" }} </td>
                                <td> {{ (isset($val->created_at) && $val->created_at != '') ? date('jS F Y', strtotime($val->created_at)) : "Not Found" }} </td>
                                <td> 
                                    @if($val->reply_status == '0')
                                    <span class="label label-sm label-warning"> Not Replied </span>
                                    @else
                                    <span class="label label-sm label-success"> Replied </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ Route('admin-viewcontact', ['id' => $val->id]) }}" style="text-decoration: none;" title="View Contact Details">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7" style="text-align: center;">No Record Found!</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $model->links() }}
            </div>
        </div>
    </div>
</div>
@stop