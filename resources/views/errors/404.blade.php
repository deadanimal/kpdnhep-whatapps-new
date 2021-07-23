@extends('layouts.error')

@section('content')
<h1>404</h1>
<h3 class="font-bold">Page Not Found</h3>

<div class="error-desc">
    The server encountered something unexpected that didn't allow it to complete the request. We apologize.<br/>
    You can go back to main page: 
    <br/><a href="{{ route('dashboard') }}" class="btn btn-primary m-t">Dashboard</a>
    <br/><a href="{{ URL::previous() }}" class="btn btn-primary m-t">Back</a>
</div>
@stop