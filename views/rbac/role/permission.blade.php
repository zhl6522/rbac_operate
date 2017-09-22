@extends('layouts.admin')

@section('top-rbac', 'active')
@section('left-rbac', 'display: block')
@section('role', 'active')
@section('role-list', 'active')


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

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">编辑[{{ $role->label }}]权限</h4>
                </div>

                <form action="" method="post" id="role-permissions-form">
                    <input type="hidden" name="id" value="{{ $role->id }}">
                    <div class="panel-body panel-body-nopadding">
                        @foreach($permissions as $permission)
                            <div class="top-permission col-md-12">
                                @if(in_array($permission['id'],array_keys($rolePermissions)))
                                    <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}"
                                           class="top-permission-checkbox" checked/>
                                @else
                                    <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}"
                                           class="top-permission-checkbox"/>
                                @endif
                                <label><h5>&nbsp;&nbsp;{{ $permission['label'] }}</h5></label>
                            </div>
                            @if(isset($permission['subPermission']) && count($permission['subPermission']))
                                <div class="sub-permissions col-md-11 col-md-offset-1">
                                    @foreach($permission['subPermission'] as $sub)
                                        <div class="col-md-12">
                                            @if($sub['is_menu'])
                                                <label><input type="checkbox" name="permissions[]"
                                                              value="{{ $sub['id'] }}"
                                                              class="sub-permission-checkbox" {{ in_array($sub['id'],array_keys($rolePermissions)) ? 'checked':'' }}/>&nbsp;&nbsp;<span
                                                            class="fa fa-bars"></span>{{ $sub['label'] }}
                                                </label>&nbsp;&nbsp;&nbsp;
                                                @if(isset($sub['SecondPermission']) && count($sub['SecondPermission']))
                                                    <div class="col-md-11 col-md-offset-1">
                                                        @foreach($sub['SecondPermission'] as $second)
                                                            <div class="third-permissions col-md-11">
                                                            <label><input type="checkbox" name="permissions[]"
                                                                          value="{{ $second['id'] }}"
                                                                          class="sub-permission-checkbox" {{ in_array($second['id'],array_keys($rolePermissions)) ? 'checked':'' }}/>&nbsp;&nbsp;
                                                                @if($second['is_menu'])
                                                                    <span class="fa fa-bars"></span>
                                                                @endif
                                                                {{ $second['label'] }}
                                                            </label>
                                                            @if(isset($second['ThirdPermission']) && count($second['ThirdPermission']))
                                                                <div class="four-permissions col-md-11 col-md-offset-1">
                                                                    @foreach($second['ThirdPermission'] as $third)
                                                                        <label><input type="checkbox" name="permissions[]"
                                                                                      value="{{ $third['id'] }}"
                                                                                      class="sub-permission-checkbox" {{ in_array($third['id'],array_keys($rolePermissions)) ? 'checked':'' }}/>&nbsp;&nbsp;{{ $third['label'] }}
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @else
                                                <div class="col-md-11 col-md-offset-1">
                                                    <label><input type="checkbox" name="permissions[]"
                                                                  value="{{ $sub['id'] }}"
                                                                  class="sub-permission-checkbox" {{ in_array($sub['id'],array_keys($rolePermissions)) ? 'checked':'' }}/>&nbsp;&nbsp;{{ $sub['label'] }}
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                        {{ csrf_field() }}
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <button class="btn btn-primary" id="save-role-permissions">保存</button>
                            </div>
                        </div>
                    </div><!-- panel-footer -->

                </form>

            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        $(".display-sub-permission-toggle").toggle(function () {
            $(this).children('span').removeClass('glyphicon-minus').addClass('glyphicon-plus')
                .parents('.top-permission').next('.sub-permissions').hide();
        }, function () {
            $(this).children('span').removeClass('glyphicon-plus').addClass('glyphicon-minus')
                .parents('.top-permission').next('.sub-permissions').show();
        });

        $(".top-permission-checkbox").change(function () {
            $(this).parents('.top-permission').next('.sub-permissions').find('input').prop('checked', $(this).prop('checked'));
        });

        $(".sub-permission-checkbox").change(function () {
            var bottomSub = $(this).parent().parent();
            if ($(this).prop('checked')) {
                if(bottomSub.parent().hasClass('sub-permissions')) {
                    if($(this).parent().next().children().find('div').hasClass('four-permissions')) {
                        $(this).parent().next().children().children('label').find('input').prop('checked', true);
                    }
                }
                if(bottomSub.hasClass('third-permissions')) {
                    console.log(bottomSub.parent().prev().html());
                    bottomSub.parent().prev().find('input').prop('checked', true);
                }
                if(bottomSub.hasClass('four-permissions')) {
                    bottomSub.prev().find('input').prop('checked', true);
                    bottomSub.parent().parent().prev().find('input').prop('checked', true);
                }
                $(this).parents('.sub-permissions').prev('.top-permission').find('.top-permission-checkbox').prop('checked', true);
            } else {
                if(bottomSub.parent().hasClass('sub-permissions')) {
                    bottomSub.find('input').prop('checked', false);
                }
                if(bottomSub.hasClass('third-permissions')) {
                    bottomSub.find('input').prop('checked', false);
                    var nextPermissions = bottomSub.parent().find('input').is(':checked');
                    if(!nextPermissions) {
                        bottomSub.parent().prev().find('input').prop('checked', false);
                        var subPermissions = bottomSub.parent().parent().parent().find('input').is(':checked');
                        if(!subPermissions) {
                            $(this).parents('.sub-permissions').prev('.top-permission').find('.top-permission-checkbox').prop('checked', false);
                        }
                    }
                }
                if(bottomSub.hasClass('four-permissions')) {
                    var nextPermissions = bottomSub.find('input').is(':checked');
                    if(!nextPermissions) {
                        bottomSub.prev().find('input').prop('checked', false);
                        var subPermissions = bottomSub.parent().parent().find('input').is(':checked');
                        if(!subPermissions) {
                            bottomSub.parent().parent().prev().find('input').prop('checked', false);
                            var permissions = bottomSub.parent().parent().parent().parent().find('input').is(':checked');
                            if(!permissions) {
                                $(this).parents('.sub-permissions').prev('.top-permission').find('.top-permission-checkbox').prop('checked', false);
                            }
                        }
                    }
                } else {

                }
            }
        });
    </script>
@endsection