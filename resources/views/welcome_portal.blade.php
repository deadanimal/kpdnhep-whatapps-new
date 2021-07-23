@extends('layouts.main_portal')
<?php
?>
@section('content')
    <div class="ibox floating-container">
        <div class="ibox-content" style="padding: 0px !important;">
            <div class="container mt-4 mb-4 shadow-sm p-3 mb-5 bg-white rounded checkcase"
                 style="background-color: white; border: 1px solid #ccc;">
                <div class="panel-header" style="margin-top: 10px;padding-left:50px;">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="help pad-btm" align="left">
                            <div style="min-height:345px; padding-left:50px;padding-right:50px;">
                                @foreach($mArticles as $mArticle)
                                    {!! app()->getLocale() == 'en' ? $mArticle->content_en : $mArticle->content_my !!}
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
