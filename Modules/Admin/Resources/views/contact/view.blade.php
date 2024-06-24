@extends('admin::layouts.main')

@section('breadcrumb')
<li> <a href="{{ Route('admin-contact') }}">Contact Us</a></li>
<li class="active">View</li>
@stop

@section('content')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-eye font-green-haze"></i>
            <span class="caption-subject font-green-haze bold uppercase">View Message Details</span>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Name:</label>
                        <div class="col-md-9">
                            <p class="form-control-static"> {{ (isset($model->name) && $model->name != null) ? $model->name : "Not Given" }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Email :</label>
                        <div class="col-md-9">
                            <p class="form-control-static"> {{ ($model->email != '') ? $model->email : 'Not Given' }} </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Phone :</label>
                        <div class="col-md-9">
                            <p class="form-control-static"> {{ (isset($model->phone_no) && $model->phone_no != null) ? $model->phone_no : "Not Given" }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3"> Message :</label>
                        <div class="col-md-9">
                            <p class="form-control-static"> {{ (isset($model->message) && $model->message != null) ?  $model->message : "Not Given" }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-3">Status:</label>
                    <div class="col-md-9">
                        <p class="form-control-static"> {{ ($model->reply_status == '0') ? 'Not replied' : 'Replied' }} </p>
                    </div>
                </div>
            </div>
            @if($model->reply_status == '1')
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-3">Reply Message:</label>
                    <div class="col-md-9">
                        <p class="form-control-static"> {{ (isset($model->reply_message) && $model->reply_message != null) ?  $model->reply_message : "Not Given" }} </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <br>
        <hr>
        <?php if ($model->reply_status == '0') { ?>
            <form class="form-horizontal form-row-seperated" action="{{ Route('send-reply', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">Reply to user:</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="reply"  rows="5" placeholder="Type your reply message" ></textarea>
                                <div class="help-block" style="color: red;">{{ $errors->first('reply') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <button type="submit" class="btn green">Send Reply</button>
                        <a href="{{ Route('admin-contact') }}" class="btn default">Back</a>
                    </div>
                </div>
            </form>
        <?php } ?>
        <br>
    </div>
</div>
@stop