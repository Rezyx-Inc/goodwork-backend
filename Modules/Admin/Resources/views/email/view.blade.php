@extends('admin::layouts.main')
@section('css')
<style>
    .form-control-static h3 {
        margin-top: 0px;
    }
</style>
@stop
@section('breadcrumb')
<li> <a href="{{ Route('admin-emails') }}">Emails</a></li>
<li class="active">View</li>
@stop

@section('content')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-eye font-green-haze"></i>
            <span class="caption-subject font-green-haze bold uppercase">Viewing details of {{ $model->about }}</span>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form class="form-horizontal">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">About:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"> {{ (isset($model->about) && $model->about != null) ? $model->about : "Not Given" }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Subject:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"> {{ (isset($model->subject) && $model->subject != null) ? $model->subject : "Not Given" }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Body:</label>
                            <div class="col-md-9 form-control-static">
                                {!! (isset($model->body) && $model->body != null) ? $model->body : "Not Given" !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right">
                <a href="{{ Route('admin-updateemail', ['id' => $model->id]) }}" class="btn green">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <a href="{{ Route('admin-emails') }}" class="btn default">Back</a>
            </div>
        </form>
        <!-- END FORM-->
    </div>
</div>
@stop