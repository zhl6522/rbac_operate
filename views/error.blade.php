@extends('layouts.admin')
@section('top-index', 'active')
@section('left-index', 'display: block')
@section('console', 'active')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">友情提示</div>

                    <div class="panel-body">
                        无权限!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
