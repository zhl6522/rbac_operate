@extends('layouts.admin')

@section('top-rbac', 'active')
@section('left-rbac', 'display: block')
@section('user', 'active')
@section('rbac-list', 'active')

@section('css')
<style>
    #sendTime {
        display: none;
    }
</style>
@endsection

@section('path')
<h1>
    权限管理
    <small>
        <i class="fa fa-fw fa-angle-double-right"></i>
        用户列表
    </small>
    <small>
        <i class="fa fa-fw fa-angle-double-right"></i>
        {{ $t }}
    </small>
</h1>
@endsection

@section('main')
    <div class="box bq-box-black">
        <div class="box-header">
        </div>
        <div class="box-body">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th>用户名</th>
                    <th>账号</th>
                    <th>超级管理员</th>
                    <th>所属角色</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="tableBannerList">
                @forelse ($data as $user)
                    <tr class="{{ $user->id }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->cellphone }}</td>
                        <td>{!! ($user->rid == 1) ? '<span class="label label-danger">是</span>':'<span class="label label-default">否</span>' !!}</td>
                        <td>
                            @if($user->rname)
                                <span class="badge badge-info">{{ $user->rname }}</span>
                            @else
                                <span class="badge">无</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            @can('user-edit')
                            <a href="{{ route('user-edit',['id'=>$user->id]) }}"
                               class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                            @endcan
                            @can('user-del')
                            <button class="btn btn-xs btn-danger switch-status" data-id="{{ $user->id }}"><i class="fa fa-trash-o"></i><span>删除用户</span></button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">暂无数据</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info">
                        总数: {{ $data->total() }}
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers">
                        {!! $data->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        $('.switch-status').click(function () {
            var id = $(this).data('id');

            if(confirm('确定删除用户?')) {
                $.ajax({
                    type : "post",
                    data: {'id': id, '_token': '{{ csrf_token() }}'},
                    url : "/rbac/user/userdel",
                    success : function(data) {
                        $('.'+id).remove();
                        alert('删除成功');
                    }
                });
            }
        });
    </script>
@endsection