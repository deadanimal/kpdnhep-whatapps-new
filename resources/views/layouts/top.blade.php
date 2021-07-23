<div class="row border-bottom white-bg">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-success " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">Selamat Datang {{ Auth::user()->name }}</span>
            </li>
            <li>
                <div class="dropdown profile-element">
                    <span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            @if(Auth::user()->user_photo != '')
                                <img alt="image" class="img-circle"
                                     src="{{ Storage::url('profile/'.Auth::user()->user_photo) }}"
                                     style="width: 48px; height: 48px"/>
                            @else
                                <img alt="image" class="img-circle" src="{{ url('img/default.jpg') }}"
                                     style="width: 48px"/>
                            @endif
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li>
                                <a href="{{ url('editprofile', Auth::user()->id) }}">
                                    <i class="fa fa-user"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('user/changepassword', Auth::user()->id) }}">
                                    <i class="fa fa-key"></i> Tukar Kata Laluan
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                @impersonating
                                    <a href="#" onclick="event.preventDefault();
                                                        document.getElementById('impersonate-leave-form').submit();">
                                        <i class="fa fa-sign-out"></i> {{ __('button.stop_impersonate') }}
                                    </a>
                                    <form id="impersonate-leave-form"
                                          action="{{ route('admin.users.impersonateLeave') }}"
                                          method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                @else
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                        <i class="fa fa-unlock-alt"></i> Log Keluar
                                    </a>
                                    <form id="logout-form"
                                          action="{{ route('logout') }}"
                                          method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                @endImpersonating
                            </li>
                        </ul>
                    </span>
                </div>
            </li>
        </ul>
    </nav>
</div>
