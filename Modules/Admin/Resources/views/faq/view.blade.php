@extends('admin::layouts.main')

@section('breadcrumb')
<li> <a href="{{ Route('admin-faqs') }}">Faqs</a></li>
<li class="active">View</li>
@stop

@section('content')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-eye font-green-haze"></i>
            <span class="caption-subject font-green-haze bold uppercase">Viewing details of {{ $model->question }}</span>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form class="form-horizontal">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Question:</label>
                            <div class="col-md-9">
                                <p class="form-control-static"> {{ (isset($model->question) && $model->question != null) ? $model->question : "Not Given" }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Answer:</label>
                            <div class="col-md-9">
                                <div class="form-control-static"> {!! (isset($model->answer) && $model->answer != null) ? $model->answer : "Not Given" !!} </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status:</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    @if($model->status == '0')
                                    Inactive
                                    @elseif($model->status == '1')
                                    Active
                                    @else
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right">
                <a href="{{ Route('admin-updatefaq', ['id' => $model->id]) }}" class="btn green">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <a href="{{ Route('admin-faqs') }}" class="btn default">Back</a>
            </div>
        </form>
        <!-- END FORM-->
    </div>
</div>
@stop