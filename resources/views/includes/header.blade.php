@if(isset($user) && $user != null)
<?php
    dd($menu);
?>
<div class="header navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="header-seperation">
            <ul class="nav pull-left notifcation-center visible-xs visible-sm">
                <li class="dropdown">
                    <a href="#main-menu" data-webarch="toggle-left-side">
                        <div class="iconset top-menu-toggle-white"></div>
                    </a>
                </li>
            </ul>

            <div class="text-center"></div>

            <a href="#" class="text-center">
                <img src="/img/logo/logo-white.png" class="logo text-center text-center" alt="" data-src="/img/logo/logo-white.png" style="width:85%"/>
            </a>
        </div>

        <div class="header-quick-nav">
            <div class="pull-left">
                <ul class="nav quick-section">
                    <li class="quicklinks">
                        <a href="#" class="" id="layout-condensed-toggle">
                            <div class="iconset top-menu-toggle-dark"></div>
                        </a>
                    </li>
                </ul>

                <ul class="nav quick-section">
                    <li class="quicklinks">
                        <a href="#" class="">
                            <div class="iconset top-reload"></div>
                        </a>
                    </li>

                    <li class="quicklinks"><span class="h-seperate"></span></li>

                    <li class="quicklinks">
                        <a href="#" class="">
                            <div class="iconset top-tiles"></div>
                        </a>
                    </li>

                    <li class="m-r-10 input-prepend inside search-form no-boarder">
                        <span class="add-on"><span class="iconset top-search"></span></span>
                        <input name="" type="text" class="no-boarder" placeholder="Search Dashboard" style="width:250px;">
                    </li>
                </ul>
            </div>

            <div class="pull-right" style="margin-top: 3px;margin-right: 20px">
                <div class="chat-toggler dropdown" style="min-width: 110px">
                    <div class="profile-pic">
                        <img src="/img/avatar.jpg" alt="" data-src="/img/avatar.jpg" width="35" height="35" />
                    </div>

                    <a href="#" class="dropdown-toggle pull-right" data-toggle="dropdown" style="margin-left: 5px">
                        <div class="user-details">
                            <div class="username">
                                {{$user->name}}
                            </div>
                        </div>

                        <div class="iconset top-down-arrow"></div>
                    </a>

                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
                          <li><a href="#"><i class="fa fa-user"></i>&nbsp;&nbsp;Profil</a></li>
                          <li><a href="#"><i class="fa fa-key"></i>&nbsp;&nbsp;Ubah Password</a></li>
                          <li class="divider"></li>

                          <li>
                              <a href="#" style="display: inline-block;">
                                  <form action="/logout" method="post">
                                      {!! csrf_field() !!}
                                      <button type="submit" class="btn-ref text-left" style="width: 150%"><i class="fa fa-power-off"></i>&nbsp;&nbsp; Keluar</button>
                                  </form>
                              </a>
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>
</div>

<div class="page-container row-fluid">
    <div class="page-sidebar" id="main-menu">
        <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
            <!-- BEGIN MINI-PROFILE -->
            <div class="user-info-wrapper" style="margin-top: 5px;">
                <div class="user-info" style="margin-top: 15px;">
                    <div class="greeting" style="font-size:16px">Hai,</div>
                    <div class="username" style="font-size:16px">{{$user->name}}</div>
                </div>
            </div>

            <hr style="margin-top:10px"></hr>

            <ul>
                <li @if(\Request::is('/')) class="active" @endif>
                   <a href="/">
                       <i class="fa fa-home"></i>
                       <span class="title">Dashboard</span>
                   </a>
                </li>

                <li class="">
                    <a href="javascript:;">
                        <i class="fa fa-cogs"></i>
                        <span class="title">Setup</span>
                        @if(\Request::is('config') || \Request::is('config/*'))
                            <span class="arrow open"></span>
                        @else
                            <span class="arrow"></span>
                        @endif
                    </a>

                    <ul class="sub-menu" @if(\Request::is('config') || \Request::is('config/*')) style="display: block;" @endif>
                        <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">User</a></li>
                        <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">Roles</a></li>
                        <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">Permissions</a></li>
                        <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">Menu</a></li>
                    </ul>
                </li>

                @for($i=0;$i<count($menu);$i++)
                    <li class="">
                        <a href="javascript:;">
                            <i class="fa fa-cogs"></i>
                            <span class="title">Setup</span>
                            @if(\Request::is('config') || \Request::is('config/*'))
                                <span class="arrow open"></span>
                            @else
                                <span class="arrow"></span>
                            @endif
                        </a>

                        <ul class="sub-menu" @if(\Request::is('config') || \Request::is('config/*')) style="display: block;" @endif>
                            <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">User</a></li>
                            <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">Roles</a></li>
                            <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">Permissions</a></li>
                            <li @if(\Request::is('config/info') || \Request::is('config/info/*')) class="active" @endif><a href="/config/info">Menu</a></li>
                        </ul>
                    </li>
                @endfor
            </ul>
        </div>
    </div>

@endif
