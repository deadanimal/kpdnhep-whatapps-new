@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Note</div>

                <div class="panel-body">
                    @if (session()->has('success'))
                    {{ session()->get('success') }}
                    @endif
                    <!--You have successfully registered. An email is sent to you for verification.-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection