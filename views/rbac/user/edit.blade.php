@extends('layouts.admin')

@section('top-rbac', 'active')
@section('left-rbac', 'display: block')
@section('user', 'active')
@section('rbac-list', 'active')


@section('css')
    <style>
        ul,li,ol {
            list-style: none;
            margin: 0 !important;
            padding: 0;
        }

        .form-group {
            padding: 15px 0;
            border-top: 1px solid #d3d7db;
        }

        .type-video-list {
            overflow: auto;
            margin-top: 15px;
            height: 214px;
            padding: 5px;
            border: 1px solid #D2D6E0;
        }
        .type-video-item {
            overflow: hidden;
            position: relative;
            float: left;
            width: 200px;
            height: 200px;
            padding: 2px;
            margin-right: 10px !important;
            margin-bottom: 10px !important;
        }
        .type-video-item p {
            margin: 0;
        }
        .video-info, .video-img {
            position: absolute;
            width: 100%;
        }
        .type-video-item .video-info {
            color: white;
            font-size: 12px;
            background: rgba(0,0,0,0.7);
        }
        .play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -15px;
            margin-left: -15px;
            font-size: 40px;
        }
        .play-btn a {
            color: #fff;
        }

        #typeVideo {
            position: relative;
        }

        /* 根据用户输入让用户 选择影片名start */
        .film-name-list {
            max-height: 233px;
            overflow: auto;
            position: absolute;
            top: 58;
            width: 100%;
            z-index: 8888;
            border: 1px solid #e2e2e4;
            background: #fff;
        }

        .film-name-list > ul {
            background: white;
            cursor: pointer;
        }

        .film-name-list > ul li {
            list-style-type: none;
            padding: 5px 5px;
        }

        .film-name-list > ul li:hover{
            background-color: #f7f7f7;
        }
        /* 当选择视屏播放时 根据用户输入让用户 end */
    </style>
@endsection

@section('path')
    <h1>
        权限管理
        <small>
            <i class="fa fa-fw fa-angle-double-right"></i>
            {{ $t }}
        </small>
         <small>
            <i class="fa fa-fw fa-angle-double-right"></i>
            <a href="javascript:history.go(-1);">返回</a>
        </small>
    </h1>
@endsection

@section('main')
    <div class="box bq-box-black">
        <div class="box-body">

            <form class="form-horizontal form-bordered" action="" method="POST"  enctype="multipart/form-data">

                <div class="panel-body panel-body-nopadding">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="checkbox">所属角色组</label>
                        <div class="col-sm-6" style="padding-top: 6px;">
                            @foreach ($role as $k => $v)
                                <input type="radio" name="rid" class="rid" value="{{ $v->id }}"  <?php if($v->id == $data->rid) { echo 'checked '; } ?> >&nbsp;{{ $v->name }} &nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">用户名 <span class="asterisk">*</span></label>

                        <div class="col-sm-6">
                            <input type="text"  data-toggle="tooltip" name="name"
                                   data-trigger="hover" class="form-control tooltips"
                                   data-original-title="不可重复" value="{{ $data->name }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">账号(必须手机号) <span class="asterisk">*</span></label>

                        <div class="col-sm-6">
                            <input type="text"  data-toggle="tooltip" name="cellphone"
                                   data-trigger="hover" class="form-control tooltips"
                                   data-original-title="不可重复" value="{{ $data->cellphone }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">密码 <span class="asterisk">*</span></label>

                        <div class="col-sm-6">
                            <input type="password"  data-toggle="tooltip" name="password"
                                   data-trigger="hover" class="form-control tooltips"
                                   placeholder="请输入密码"
                                   data-original-title="请输入密码" >
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    {{ csrf_field() }}
                </div><!-- panel-body -->

                <div class="box-footer">
                    <button type="submit" class="btn bq-btn-black">确认修改</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.bq-btn-black').click(function () {
                var val=$('input:radio[name="rid"]:checked').val();
                if(val == null) {
                    $('.alert-danger').remove();
                    $('.content').prepend('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>error!</strong> 所属角色组是必选的</div>');
                    return false;
                }
            });
        });
    </script>
@endsection

