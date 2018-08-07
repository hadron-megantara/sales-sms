@if(isset($user) && $user != null)

<div class="header navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="header-seperation" style="height:75px">
            <ul class="nav pull-left notifcation-center visible-xs visible-sm" style="display:none">
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
                <?php echo $menu; ?>
            </ul>
        </div>
    </div>

@endif
