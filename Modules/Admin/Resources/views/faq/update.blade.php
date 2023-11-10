@extends('admin::layouts.main')

@section('breadcrumb')
<li> <a href="{{ Route('admin-faqs') }}">Faqs</a></li>
<li> <a href="{{ Route('admin-viewfaq', ['id' => $model->id]) }}">{{ $model->question }}</a></li>
<li class="active">Update</li>
@stop

@section('content')
<div class="users-update">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-edit font-green-haze" aria-hidden="true"></i>
                <span class="caption-subject font-green-haze bold uppercase">Updating details of {{ $model->question }}</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal form-row-seperated" action="{{ Route('admin-updatefaq', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-body">
                    <div class="form-group {{ $errors->has('question') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label">Question <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="question" value="{{ (old('question') != "") ? old('question') : $model->question }}" placeholder="Question">
                            @if ($errors->has('question'))
                            <div class="help-block">{{ $errors->first('question') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('answer') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label">Answer <span class="required">*</span></label>
                        <div class="col-md-9">
                            <textarea class="form-control ckeditor" name="answer" placeholder="Answer" rows="10">{{ (old('answer') != "") ? old('answer') : $model->answer }}</textarea>
                            @if ($errors->has('answer'))
                            <div class="help-block">{{ $errors->first('answer') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Status <span class="required">*</span></label>
                        <div class="col-md-5">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" {{ ($model->status == '1') ? 'checked' : '' }}> Active
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="0" {{ ($model->status == '0') ? 'checked' : '' }}> Inactive
                                </label>
                                @if ($errors->has('status'))
                                <div class="help-block">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <button type="submit" class="btn green">Update</button>
                            <a href="{{ Route('admin-viewfaq', ['id' => $model->id]) }}" class="btn default">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop