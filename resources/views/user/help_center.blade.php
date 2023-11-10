@extends('layouts.main') 
@section('mytitle', ' Help center page | Saas')

@section('content')
<div class="clearfix">
    <div class="main_page">
        <div class="row">
            <div class="col-md-6">
                <div class="main_page_box">
                    <div class="page_box_header border-bottom">
                        <h2>Help Center</h2>
                    </div>
                    <div class="page_box_body p-2">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>
                        
                    </div>
                    <div class="edit_pro_footer m-0">
                        <button class="btn_save">Send <i class="icofont-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop