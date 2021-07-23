<?php
use App\Menu;
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header" style="background-image: url({{ url('images/header-profile-skin-1.png') }}) !important;">
                <div class="dropdown profile-element" align='center'>
                    <a href="{{ route('dashboard') }}">
                        <span>
                            <img alt="image" class="img-md" style="width: 100% !important;" src="{{ url('/img/logo2_0.png') }}" />
                        </span>
                    </a>
<!--                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong>
                            </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">Log out
                            </a>
                        </li>
                    </ul>-->
                </div>
<!--                <div class="logo-element">
                    IN+
                </div>-->
            </li>
            {{-- Request::segment(1) --}}
            {{-- dd(Menu::GetMainMenu()) --}}
            {{-- dd(count(Menu::GetSubManuArray(1))) --}}
            @if(Auth::user()->password_ind == '1' && Auth::user()->profile_ind == '1')
            @foreach (Menu::GetMainMenu() as $mainmenu)
                @if($mainmenu->sort == '100')
                    <p style="color: #e1e4ea; background: -moz-linear-gradient(to right, #115272 , #f3f3f4); margin-bottom: 0px; padding-top: 5px; height: 30px;">
                            <strong>&nbsp;&nbsp;&nbsp;ADUAN KEPENGGUNAAN</strong>
                    </p>
                    <!--<hr style="width:100%;border-top: 1px solid #226383;margin:0;padding:0;">-->
                @endif
                @if($mainmenu->sort == '451')
                    <p style="color: #e1e4ea; background: -moz-linear-gradient(to right, #115272 , #f3f3f4); margin-bottom: 0px; padding-top: 5px; height: 30px;">
                        <strong>&nbsp;&nbsp;&nbsp;ADUAN INTEGRITI</strong>
                    </p>
                @endif
                @if(count(Menu::GetSubManuArray($mainmenu->id)) > 0)
                {{-- $mainmenu->sort --}}
                @if($mainmenu->sort == '200' || $mainmenu->sort == '300' || $mainmenu->sort == '301' || $mainmenu->sort == '302'|| $mainmenu->sort == '303' ||
                $mainmenu->sort == '400' || $mainmenu->sort == '405' || $mainmenu->sort == '500' || $mainmenu->sort == '501' || $mainmenu->sort == '502'
                || $mainmenu->sort == '503' || $mainmenu->sort == 420)
                <!--<div class="hr-line-solid" style="color: white;"></div>-->
                <!--<hr style="width:100%;border-top: 1px solid #226383;margin:0;padding:0;">-->
                    @if($mainmenu->sort == '400')
                        <p style="color: #e1e4ea; background: -moz-linear-gradient(to right, #115272 , #f3f3f4); margin-bottom: 0px; padding-top: 5px; height: 30px;">&nbsp;&nbsp;&nbsp;
                        <strong>LAPORAN</strong>
                        <hr style="width:100%;border-top: 1px solid #226383;margin:0;padding:0;">
                    @elseif($mainmenu->sort == '405')
                        <p style="color: #e1e4ea; background: -moz-linear-gradient(to right, #115272 , #f3f3f4); margin-bottom: 0px; padding-top: 5px; height: 30px;">&nbsp;&nbsp;&nbsp;
                        <strong>LAPORAN SAS</strong>
                        <hr style="width:100%;border-top: 1px solid #226383;margin:0;padding:0;">
                    @else
                        {{-- <strong>{{ strtoupper($mainmenu->menu_txt) }}</strong></p> --}}
                    @endif
                @endif
                    <li class="{{ in_array(Request::segment(1), Menu::GetSubManuArray($mainmenu->id))? 'active':'' }}">
                        @if(count(Menu::GetSubMenu($mainmenu->id)) > 0)
                        <a href="{{ $mainmenu->menu_loc }}"><i class="fa {{ $mainmenu->icon_name != ''? $mainmenu->icon_name : 'fa-exclamation-triangle' }}"></i> <span class="nav-label">{{ $mainmenu->menu_txt }}</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            @foreach (Menu::GetSubMenu($mainmenu->id) as $submenu)
                                @if($submenu->menu_loc != '')
                                <!--<ul class="nav nav-second-level">-->
                                    <li class="{{ Request::segment(1) == $submenu->menu_loc ? 'active' : 'no' }}"><a href="{{ url($submenu->menu_loc) }}">{{ $submenu->menu_txt }}</a></li>
                                <!--</ul>-->
                                @else
                                <!--<ul class="nav nav-second-level">-->
                                    <li><a href="#">{{ $submenu->menu_txt }}</a></li>
                                <!--</ul>-->
                                @endif
                            @endforeach
                            </ul>
                        @else
                        <a href="{{ $mainmenu->menu_loc }}"><i class="fa {{ $mainmenu->icon_name != ''? $mainmenu->icon_name : 'fa-exclamation-triangle' }}"></i> <span class="nav-label">{{ $mainmenu->menu_txt }}</a>
                        @endif
                    </li>
                @else
                    @if($mainmenu->menu_loc != '')
                    {{-- $mainmenu->sort --}}
                    @if($mainmenu->sort == '200' || $mainmenu->sort == '300' || $mainmenu->sort == '400' || $mainmenu->sort == '500')
                    <div class="hr-line-solid" style="color: white;"></div>
                    @endif
                        <li class="{{ Request::segment(1) ==  $mainmenu->menu_loc? 'active':'' }}">
                            <a href="{{ url($mainmenu->menu_loc) }}"><i class="fa {{ $mainmenu->icon_name != ''? $mainmenu->icon_name : 'fa-exclamation-triangle' }}"></i> <span class="nav-label">{{ $mainmenu->menu_txt }}</a>
                        </li>
                    @else
                    {{-- $mainmenu->sort --}}
                    @if($mainmenu->sort == '200' || $mainmenu->sort == '300' || $mainmenu->sort == '400' || $mainmenu->sort == '500')
                    <div class="hr-line-solid" style="color: white;"></div>
                    @endif
                        <li>
                            <a href="/"><i class="fa {{ $mainmenu->icon_name != ''? $mainmenu->icon_name : 'fa-exclamation-triangle' }}"></i> <span class="nav-label">{{ $mainmenu->menu_txt }}</a>
                        </li>
                    @endif
                @endif
            @endforeach
            @endif
    </div>
</nav>