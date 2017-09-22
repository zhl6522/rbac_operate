<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>运营后台</title>
  <link rel="shortcut icon" href="">
  <!-- Tell the browser to be responsive to screen width -->
  <!--meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"-->
  <!-- Bootstrap 3.3.6 -->
  <!--此处忽略CSS、无关JS-->

</head>
<body class="hold-transition skin-blue sidebar-mini skin-red fixed">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <strong class="logo-mini">运营</strong>
      <!-- logo for regular state and mobile devices -->
      <!-- <span class="logo-lg"><b>Admin</b>LTE</span> -->
      <strong class="logo-lg">运营管理后台</strong>
    </a>
    <!-- Header Navbar: style  can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
      </a>

      <!-- 顶部导航的左边 -->
      <ul class="nav navbar-nav navbar-left">
          <li class="@yield('top-index')"><a href="/">首页</a></li>
          @foreach($topnav as $value)
              <li class="@yield('top-".{{$value->yield}}."')"><a href="{{ route($value->name) }}">{{ $value->label }}</a></li>
          @endforeach
      </ul>

      <!-- 顶部导航的右边 -->
      <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <span class="hidden-xs">Hi! {{ Auth::user()->name }}</span>
                  </a>
              </li>
              <li>
                  <a href="/logout">注销</a>
              </li>
              <!-- <li>
                  <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li> -->
          </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- 侧边导航 - 开始 -->

        @foreach($permissions as $permission)

            <ul class="sidebar-menu sidebar-menu-left" style="@yield('left-'.e($permission['yield']))">
                @if(isset($permission['subPermission']))
                    @foreach($permission['subPermission'] as $sub)
                        @can(e($sub['name']))
                            @if($sub['name'] == 'permission-list-left')
                                <li class="@yield(e($sub['yield']))" style="display: none;">
                            @else
                                <li class="@yield(e($sub['yield']))">
                            @endif
                            <a href="{{ route($sub['name']) }}"><i class="fa fa-circle-o"></i> {{ $sub['label'] }}
                                <span class="pull-right-container">
                            @if(isset($sub['SecondPermission']))
                                        <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                                <ul class="treeview-menu">
                                @foreach($sub['SecondPermission'] as $second)
                                    @can(e($second['name']))
                                        <li class="@yield(e($second['yield']))">
                                            <a href="{{ route($second['name']) }}"><i class="fa fa-caret-right"></i>{{ $second['label'] }}</a>
                                        </li>
                                    @endcan
                                @endforeach
                                </ul>
                            @else
                                </span>
                            </a>
                            @endif

                        </li>
                        @endcan
                    @endforeach
                @endif
            </ul>
        @endforeach
	<!-- 侧边导航 - 结束 -->
    </section>
    <!-- /.sidebar -->
  </aside>

  
  <!--此处忽略无关代码-->
  
</body>
</html>
