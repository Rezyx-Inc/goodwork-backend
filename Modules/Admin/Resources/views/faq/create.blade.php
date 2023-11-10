@extends('admin::layouts.main')

@section('breadcrumb')
<li> <a href="{{ Route('admin-faqs') }}">Faqs</a></li>
<li class="active">Create</li>
@stop

@section('content')
<div class="faqs-update">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-edit font-green-haze" aria-hidden="true"></i>
                <span class="caption-subject font-green-haze bold uppercase">Create Faq</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal form-row-seperated" action="{{ Route('admin-createfaq') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-body">
                    <div class="form-group {{ $errors->has('question') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label">Question <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="question" value="{{ (old('question') != "") ? old('question') : "" }}" placeholder="Question">
                            @if ($errors->has('question'))
                            <div class="help-block">{{ $errors->first('question') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('answer') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label">Answer <span class="required">*</span></label>
                        <div class="col-md-9">
                            <textarea class="form-control ckeditor" name="answer" placeholder="Answer" rows="10">{{ (old('answer') != "") ? old('answer') : "" }}</textarea>
                            @if ($errors->has('answer'))
                            <div class="help-block">{{ $errors->first('answer') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <button type="submit" class="btn green">Create</button>
                            <a href="{{ Route('admin-faqs') }}" class="btn default">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop