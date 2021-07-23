<style>
    .navbar-kiri {
        padding-bottom: 10px;
        float: left;
    }

    .navbar-kiri .navbar-brand {
        padding-top: 10px;
        background: #115272;
    }

    .navbar-kanan {
        padding-top: 10px;
        float: right;
    }

    .navbar-kanan .nav .bahasa {
        background: none;
        color: #fff;
        display: inline-block;
        padding: 5px;
    }

    .navbar-right .dropdown-menu {
        right: 0;
        left: auto;
    }
</style>
<div class="row border-bottom white-bg">
    <nav class="navbar navbar-static-top" role="navigation"
         style="margin-bottom: 0; background: #115272; border-bottom: 3px solid #fac626;">
        <div class="navbar-kiri">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <img style="max-height:40px;min-height:40px;" src="{{ url('/img/logo.jpg') }}" alt="eAduanV2">
            </a>
        </div>
        <div class="navbar-kanan">
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a href="{{ url('user/language', 'en') }}" class="bahasa">
                        <img src="{{ url('/img/United-Kingdom.png') }}">
                    </a>
                    <a href="{{ url('user/language', 'ms') }}" class="bahasa">
                        <img src="{{ url('/img/Malaysia.png') }}">
                    </a>
                </li>
                <li>
                    <span class="m-r-sm text-muted welcome-message"
                          style="color:#fff;">@lang('public-profile.dashboard.welcome') {{ Auth::user()->name }}</span>
                </li>
                <li>
                    <div class="dropdown profile-element">
                        <span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                @if(Auth::user()->user_photo != '')
                                    <img alt="image" class="img-circle"
                                         src="{{ Storage::url('profile/'.Auth::user()->user_photo) }}"
                                         style="width: 45px; height: 45px"/>
                                @else
                                    <img alt="image" class="img-circle" src="{{ url('img/default.jpg') }}"
                                         style="width: 45px"/>
                                @endif
                            </a>
                            <ul class="dropdown-menu animated fadeInRight">
                                <li>
                                    <a href="{{ url('user/pubeditprofile') }}">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;{{__('public-profile.dashboard.profil')}}</a>
                                </li>
                                <li>
                                    <a href="{{ url('user/pubchangepassword', Auth::user()->id) }}">
                                        <i class="fa fa-key"></i>&nbsp;&nbsp;{{__('public-profile.dashboard.changepass')}}
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
                                            <i class="fa fa-unlock-alt"></i> {{__('public-profile.dashboard.logout')}}
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
        </div>
    </nav>
</div>
