@extends('layouts.admin')

@section('top-rbac', 'active')
@section('left-rbac', 'display: block')
@section('permission', 'active')
@section('permission-list', 'active')

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
        权限管理列表
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
                    <th>显示名称</th>
                    <th>路由</th>
                    <th>说明</th>
                    <th>是否菜单</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $permission)
                    <tr class="{{ $permission->id }}">
                        <td>
                            <p class="text-info">
                            @if($permission->fid == 0 || (isset($permission->buttom) && $permission->buttom == 1))
                                <a href="{{ route('permission-id',['id'=>$permission->id]) }}">{{ $permission->label }}</a>
                            @else
                                {{ $permission->label }}
                            @endif
                            </p>
                        </td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->description }}</td>
                        <td>{!! $permission->is_menu ? '<span class="label label-danger">是</span>':'<span class="label label-default">否</span>' !!}</td>
                        <td>
                            @can('permission-edit')
                            <a href="{{ route('permission-edit',['id'=>$permission->id]) }}"
                               class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑</a>
                            @endcan
                            @can('permission-del')
                            <button class="btn btn-xs btn-danger switch-status role-delete" data-id="{{ $permission->id }}"><i class="fa fa-trash-o"></i><span>删除权限</span></button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
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
                    url : "/rbac/permission/permissiondel",
                    success : function(data) {
                        console.log(data);
                        if (data.status == 0) {
                            $('.'+id).remove();
                            alert('删除成功');
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            }
        });
    </script>
@endsection