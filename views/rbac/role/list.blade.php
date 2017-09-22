@extends('layouts.admin')

@section('top-rbac', 'active')
@section('left-rbac', 'display: block')
@section('role', 'active')
@section('role-list', 'active')

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
                    <th>标识</th>
                    <th>角色名</th>
                    <th>说明</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $role)
                    <tr class="{{ $role->id }}">
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->label }}</td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $role->created_at }}</td>
                        <td>
                            @can('role-edit')
                            <a href="{{ route('role-edit',['id'=>$role->id]) }}"
                               class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                            @endcan
                            @can('role-permission')
                            <a href="{{ route('role-permission',['id'=>$role->id]) }}"
                               class="btn btn-info btn-xs role-permissions"><i class="fa fa-wrench"></i> 权限</a>
                            @endcan
                            @can('role-del')
                            <button class="btn btn-xs btn-danger switch-status role-delete" data-id="{{ $role->id }}"><i class="fa fa-trash-o"></i><span>删除角色</span></button>
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
                    url : "/rbac/role/roledel",
                    success : function(data) {
                        $('.'+id).remove();
                        alert('删除成功');
                    }
                });
            }
        });
    </script>
@endsection