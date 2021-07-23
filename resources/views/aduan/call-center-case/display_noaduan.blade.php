@extends('layouts.main')
<?php
use App\Ref;
use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDoc;
?>
@section('content')
<h2>Aduan Baru Call Center</h2>
<div class="ibox-content">
    <div class="panel-body" style="color: black; background-color: #e7eaec">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 widget blue-bg text-center">
                <div class="m-b-md">
                    <!--<i class="fa fa-thumbs-o-up fa-5x"></i>-->
                    <h3 class="font-bold no-margins">
                        No. Aduan Anda Telah Dijana
                    </h3>
                    <h1 class="m-xs">No. Aduan <strong>{{ $CA_CASEID }}</strong></h1>
                </div>
            </div>
        </div>
        <div class="row" align="center">
            Klik <u><a href="{{ url('call-center-case')}}">sini</a></u> untuk kembali ke senarai.
        </div>
        <?php
        ?>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
    
</script>
@stop
