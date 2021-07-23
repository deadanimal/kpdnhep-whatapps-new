@extends('layouts.main')
<?php
    use App\Ref;
    use App\Laporan\ReportLainlain;
?>
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <h2>LAPORAN MENGIKUT WARGANEGARA DAN KATEGORI ADUAN</h2>
            <div class="ibox-content">
                <div class="row">
                    {!! Form::open(['url' => 'laporanlainlain/laporanwarganegara', 'id' => 'search-form', 'class'=>'form-horizontal', 'method' => 'GET']) !!}
                        <div class="form-group"  id="data_5">
                            {{ Form::label('CA_RCVDT', 'Tarikh Dari', ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-sm-8">
                        <div class="input-daterange input-group" id="datepicker">
                                                        {{ Form::text('CA_RCVDT_dri',date('d-m-Y', strtotime($CA_RCVDT_dri)), ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_dri']) }}
                                                        <span class="input-group-addon">hingga</span>
                                                        {{ Form::text('CA_RCVDT_lst', date('d-m-Y', strtotime($CA_RCVDT_lst)), ['class' => 'form-control input-sm', 'id' => 'CA_RCVDT_lst']) }}
                        </div>
                        </div>
                        </div>
                      <div class="col-sm-6 ">
                         <div class="form-group">
                                {{ Form::label('CA_AGAINST_STATECD','Negeri', ['class' => 'col-md-3 control-label']) }}
                                <div class="col-sm-8">
                                   {{ Form::select('CA_AGAINST_STATECD', ReportLainlain::GetRef('17', 'semua'),null, ['class' => 'form-control input-sm', 'id'=>'CA_AGAINST_STATECD']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 ">
                                    
                        <select name="CA_BRNCD[]" class="form-control dual_select" multiple>
                                        @foreach ($CA_CMPLCAT as $cat)
                                        <option value="{{ $cat->code }}">{{ $cat->descr }}</option>
                                        @endforeach
                                    </select>
                  
                                </div>
                            </div>
                        </div>
                   <div class="col-sm-6 ">
                             <div class="form-group">
                            {{ Form::label('CA_DEPTCD', 'Bahagian', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                {{ Form::select('CA_DEPTCD', ReportLainlain::GetRef('315', 'semua'), null, ['class' => 'form-control input-sm', 'id' => 'CA_DEPTCD']) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                                
                                        <select name="CA_CMPLCAT[]" class="form-control dual_select" multiple>
                                             @foreach ($listcats as $cats)
                                        <option value="{{ $cats->code }}">{{ $cats->descr }}</option>
                                        @endforeach
                                        </select>
                             
                        </div>
                        </div>
                    <div class="col-sm-6">
                       <div class="form-group">
                            {{ Form::label('CA_NATCD', 'Warganegara', ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" name="CA_NATCD" value="1"  checked="checked" >
                                    <label for="Lelaki"> WARGANEGARA </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" name="CA_NATCD" value="2"  >
                                    <label for="Perempuan"> BUKAN WARGANEGARA </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm', 'name' => 'cari']) }}
                            <a href="{{ url('laporanlainlain\printexcel') }}" class = 'btn btn-success btn-sm' data-toggle="tooltip" data-placement="right">Muat Turun Excel</a>
                            <a class="btn btn-default btn-sm" href="{{ url('laporanlainlain/laporanjantina')}}">Semula</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="row"><center>
                        <h3><center>LAPORAN MENGIKUT WARGANEGARA DAN KATEGORI ADUAN BAGI TAHUN LAPORAN SUMBER ADUAN BAGI TAHUN {{ date('d-m-Y', strtotime($CA_RCVDT_dri)) }} HINGGA {{ date('d-m-Y', strtotime($CA_RCVDT_lst)) }}<br/>
                                  @if ($CA_DEPTCD != '')
                                  {{ Ref::GetDescr('315',$CA_DEPTCD,'ms') }}
                                  @else
                                  SEMUA BAHAGIAN
                                  @endif <br>
                            </center></h3>
                                   
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" style="width: 100%">
                        <thead>
                            <tr>
                                <th rowspan="3">Bil.</th>
                                <th rowspan="3">Kategori Aduan</th>
                                <th colspan="8">Status Aduan</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Dalam Siasatan</th>
                                <th rowspan="2">Diselesaikan</th>
                                <th rowspan="2">Ditutup</th>
                                <th rowspan="2">Jumlah</th>
                            </tr>
                          
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>
@stop
@section('script_datatable')
<script type="text/javascript">
       $(function () {

        $('#CA_AGAINST_STATECD').on('change', function (e) {
            var CA_AGAINST_STATECD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('laporanlainlain/getcawangan') }}" + "/" + CA_AGAINST_STATECD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_BRNCD[]"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_BRNCD[]"]').append('<option value="' + key + '">' + value + '</option>');
                        $('.dual_select').bootstrapDualListbox('refresh');
                    });
                }
            });
        });

  $('#CA_DEPTCD').on('change', function (e) {
            var CA_DEPTCD = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ url('laporanlainlain/getcatlist') }}" + "/" + CA_DEPTCD,
                dataType: "json",
                success: function (data) {
                    $('select[name="CA_CMPLCAT[]"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="CA_CMPLCAT[]"]').append('<option value="' + key + '">' + value + '</option>');
                        $('.dual_select').bootstrapDualListbox('refresh');
                    });
                }
            });
        });

        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 260
        });
        
    $('#data_5 .input-daterange').datepicker({
                format: 'dd-mm-yyyy',
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });
        });
</script>
@stop