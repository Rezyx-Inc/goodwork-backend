@extends('admin::layouts.main')

@section('breadcrumb')
<li> <a href="{{ Route('admin-emails') }}">Emails</a></li>
<li> <a href="{{ Route('admin-viewemail', ['id' => $model->id]) }}">{{ $model->about }}</a></li>
<li class="active">Update</li>
@stop

@section('content')
<div class="emails-update">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-edit font-green-haze" aria-hidden="true"></i>
                <span class="caption-subject font-green-haze bold uppercase">Updating details of {{ $model->about }}</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal form-row-seperated" action="{{ Route('admin-updateemail', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-body">
                    <div class="form-group {{ $errors->has('about') ? ' has-error' : '' }}">
                        <label class="col-md-2 control-label">About <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="about" value="{{ (old('about') != "") ? old('about') : $model->about }}" placeholder="About">
                            @if ($errors->has('about'))
                            <div class="help-block">{{ $errors->first('about') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
                        <label class="col-md-2 control-label">Subject <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="subject" value="{{ (old('subject') != "") ? old('subject') : $model->subject }}" placeholder="Subject">
                            @if ($errors->has('subject'))
                            <div class="help-block">{{ $errors->first('subject') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"></label>
                        <div class="col-md-9">
                            <div class="alert alert-info">
                                {!! $model->variable !!}
                                <span class="required">
                                    **Please don't change above variable in the body section
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('body') ? ' has-error' : '' }}">
                        <label class="col-md-2 control-label">Body <span class="required">*</span></label>
                        <div class="col-md-9">
                            <textarea class="form-control ckeditor" name="body" placeholder="Body">{{ (old('body') != "") ? old('body') : $model->body }}</textarea>
                            @if ($errors->has('body'))
                            <div class="help-block">{{ $errors->first('body') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <button type="submit" class="btn green">Update</button>
                            <a href="{{ Route('admin-viewemail', ['id' => $model->id]) }}" class="btn default">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop