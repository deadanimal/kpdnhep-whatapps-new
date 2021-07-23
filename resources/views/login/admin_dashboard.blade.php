@extends('layouts.main')
<?php
    use App\Dashboard;
    use App\Ref;
    use App\Articles;
    use Illuminate\Support\Facades\Session;
    use App\Branch;
?>
@section('content')

@if(Auth::user()->brn_cd == 'WHQR2')
    <div class="row">
        <div class="col-lg-4 col-md-3 col-sm-2"></div>
        <div class="col-lg-4 col-md-6 col-sm-8">
            <!--<a href="{{-- url('tutup') --}}">-->
            <a href="{{ url('pertanyaan-admin') }}">
                <div class="widget style1 blue-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-book fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <h3 class="font-bold"> Pertanyaan/Cadangan </h3>
                            <h2 class="font-bold">
                                <!--{{-- Dashboard::CountSiasatan() --}}-->
                                {{ Dashboard::CountPertanyaanDiterima() }}
                            </h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-2"></div>
    </div>
@else
    @if(Auth::user()->role == '700')
    @else
        <!-- {{-- @if(
            Auth::user()->role->role_code != '191'
            ||
            Auth::user()->role->role_code != '192'
            ||
            Auth::user()->role->role_code != '193'
        ) --}} -->
        @if(!in_array(Auth::user()->role->role_code,['191', '192', '193']))
        <div class="row">
            <div class="col-sm-4">
                @if(Dashboard::GetMenuText('penugasan'))
                <a href="{{ url('tugas') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <!--<i class="fa fa-random fa-5x"></i>-->
                                <img alt="Tugasan Baru" src="{{ url('img/TugasBaru.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Tugasan Baru </h4>
                                <h2 class="font-bold">{{ Dashboard::CountTugasan() }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-sm-4">
                @if(Dashboard::GetMenuText('penyiasatan'))
                <a href="{{ url('siasat') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <!--<i class="fa fa-book fa-5x"></i>-->
                                <img alt="Dalam Siasatan" src="{{ url('img/DalamSiasatan.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Dalam Siasatan </h4>
                                <h2 class="font-bold">{{ Dashboard::CountSiasatan() }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-sm-4">
                @if(Dashboard::GetMenuText('penutupan'))
                <a href="{{ url('tutup') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <!--<i class="fa fa-file-archive-o fa-5x"></i>-->
                                <img alt="Menunggu Pengesahan Penutupan" src="{{ url('img/PengesahanPenutupan.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Menunggu Pengesahan Penutupan </h4>
                                <h2 class="font-bold">{{ Dashboard::CountPenutupan() }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @endif
        <!-- {{-- @if(
            Auth::user()->role->role_code == '800'
            ||
            Auth::user()->role->role_code == '191'
            ||
            Auth::user()->role->role_code == '192'
            ||
            Auth::user()->role->role_code == '193'
        ) --}} -->
        @if(in_array(Auth::user()->role->role_code,['800', '191', '192', '193']))
        <div class="row">
            <div class="col-sm-4">
                @if(Dashboard::GetMenuText('penugasan'))
                <a href="{{ url('integrititugas') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <!--<i class="fa fa-random fa-5x"></i>-->
                                <img alt="Tugasan Baru" src="{{ url('img/TugasBaru.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Tugasan Baru </h4>
                                <h4>(Integriti)</h4>
                                <h2 class="font-bold">{{ Dashboard::CountTugasanIntegriti() }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-sm-4">
                @if(Dashboard::GetMenuText('penyiasatan'))
                <a href="{{ url('integritisiasat') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <!--<i class="fa fa-book fa-5x"></i>-->
                                <img alt="Dalam Siasatan" src="{{ url('img/DalamSiasatan.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Dalam Siasatan </h4>
                                <h4>(Integriti)</h4>
                                <h2 class="font-bold">{{ Dashboard::CountSiasatanIntegriti() }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-sm-4">
                @if(Dashboard::GetMenuText('penutupan'))
                <a href="{{ url('integrititutup') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <!--<i class="fa fa-file-archive-o fa-5x"></i>-->
                                <img alt="Menunggu Pengesahan Penutupan" src="{{ url('img/PengesahanPenutupan.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Menunggu Pengesahan Penutupan </h4>
                                <h4>(Integriti)</h4>
                                <h2 class="font-bold">{{ Dashboard::CountPenutupanIntegriti() }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @endif
        {{-- @if(!in_array(Auth::user()->role->role_code,['191', '192', '193']))
        <div class="row">
            <div class="col-lg-4">
                @if(Dashboard::GetMenuText('penugasan'))
                <a href="{{ url('caseenquirypaper/assignments') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <img alt="Tugasan Baru" src="{{ url('img/TugasBaru.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Tugasan Baru </h4>
                                <h4>(EP)</h4>
                                <h2 class="font-bold">{{ $countCollections['epAssignment'] }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-lg-4">
                @if(Dashboard::GetMenuText('penyiasatan'))
                <a href="{{ url('caseenquirypaper/investigations') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <img alt="Dalam Siasatan" src="{{ url('img/DalamSiasatan.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Dalam Siasatan </h4>
                                <h4>(EP)</h4>
                                <h2 class="font-bold">{{ $countCollections['epInvestigation'] }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            <div class="col-lg-4">
                @if(Dashboard::GetMenuText('penutupan'))
                <a href="{{ url('caseenquirypaper/closures') }}">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-2">
                                <img alt="Menunggu Pengesahan Penutupan" src="{{ url('img/PengesahanPenutupan.png') }}" style="width: 45px; height: 45px;"/>
                            </div>
                            <div class="col-xs-10 text-right">
                                <h4 class="font-bold" style="font-weight: 900"> Menunggu Pengesahan Penutupan </h4>
                                <h4>(EP)</h4>
                                <h2 class="font-bold">{{ $countCollections['epClosure'] }}</h2>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @endif --}}
    @endif
@endif
@if(substr(Auth::user()->role->role_code, 1, 1) == 2 || Auth::user()->role->role_code == '800')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title blue-bg">
                    <h5>Paparan Semua Aduan Mengikut Bahagian (<?php echo date('Y'); ?>)</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" id="search-form2" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-lg-10">
                                {{ Form::label('state', 'Negeri', ['class' => 'col-lg-4 control-label']) }}
                                <div class="col-lg-8">
                                    @if(substr(Auth::user()->Role->role_code, 0, 1) == 2 || substr(Auth::user()->Role->role_code, 0, 1) == 3)
                                        {{ Form::select('state_disabled', Ref::GetList('17', true), Auth::user()->state_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                                        {{ Form::hidden('state', Auth::user()->state_cd, ['class' => 'form-control input-sm', 'id' => 'state']) }}
                                    @else
                                        {{ Form::select('state', Ref::GetList('17', true), '', ['class' => 'form-control input-sm', 'id' => 'state']) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                {{ Form::label('branch', 'Bahagian / Cawangan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    @if(substr(Auth::user()->Role->role_code, 0, 1) == 2)
                                        {{ Form::select('branch', Branch::GetListByState(Auth::user()->state_cd), null, ['class' => 'form-control input-sm', 'id' => 'branch']) }}
                                    @elseif(substr(Auth::user()->Role->role_code, 0, 1) == 3)
                                        {{ Form::select('branch_disabled', Branch::GetListByState(Auth::user()->state_cd), Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                                        {{ Form::hidden('branch', Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'id' => 'branch']) }}
                                    @else
                                        {{ Form::select('branch', ['' => '-- SILA PILIH --'], '', ['class' => 'form-control input-sm']) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                {{ Form::label('CA_INVSTS', 'Status Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('CA_INVSTS', Dashboard::getStatusKesBelumSelesai() , '', ['class' => 'form-control input-sm', 'id' => 'CA_INVSTS']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                {{ Form::label('tempoh_aduan', 'Tempoh Aduan', ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8">
                                    {{ Form::select('tempoh_aduan', [''=>'-- SILA PILIH --','0'=>'Baru','1'=>'Lebih daripada 7 hari','2'=>'Lebih daripada 16 hari','3'=>'Lebih daripada 21 hari'] , '', ['class' => 'form-control input-sm', 'id' => 'tempoh_aduan']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-sm">Carian</button>
                            <a class="btn btn-default btn-sm" href="{{ url('dashboard')}}">Semula</a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Hari</th>
                                    <th>No. Aduan</th>
                                    <th>Aduan</th>
                                    <th>Nama Pengadu</th>
                                    <th>Nama Premis Yang Diadu</th>
                                    <th>Nama Penyiasat</th>
                                    <th>Status Aduan</th>
                                    <th>Tarikh Penerimaan</th>
                                    <th>Bahagian / Cawangan</th>
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
@endif

<div class="row">
    <div class="ibox-content" style="padding-bottom: 15px !important">
        <div class="row">
            {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
            <div class="col-sm-3">
                <div class="form-group">
                    {{ Form::label('Tahun', 'Tahun', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        {{ Form::select('Tahun', Dashboard::GetListYear(false), date('Y'), ['class' => 'form-control input-sm', 'id' => 'Tahun']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {{ Form::label('Negeri', 'Negeri', ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        @if(substr(Auth::user()->Role->role_code, 0, 1) == 2 || substr(Auth::user()->Role->role_code, 0, 1) == 3)
                            {{ Form::select('state_cd_disabled', Ref::GetList('17', true), Auth::user()->state_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                            {{ Form::hidden('Negeri', Auth::user()->state_cd, ['class' => 'form-control input-sm', 'id' => 'state_cd']) }}
                        @else
                            {{ Form::select('Negeri', Ref::GetList('17', true), '', ['class' => 'form-control input-sm']) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    {{ Form::label('BahagianCawangan', 'Bahagian/Cawangan', ['class' => 'col-sm-4 control-label']) }}
                    <div class="col-sm-8">
                        @if(substr(Auth::user()->Role->role_code, 0, 1) == 2)
                        {{ Form::select('BahagianCawangan', Branch::GetListByState(Auth::user()->state_cd), null, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                        @elseif(substr(Auth::user()->Role->role_code, 0, 1) == 3)
                        {{ Form::select('brn_cd_disabled', Branch::GetListByState(Auth::user()->state_cd), Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'disabled'=>'disabled']) }}
                        {{ Form::hidden('BahagianCawangan', Auth::user()->brn_cd, ['class' => 'form-control input-sm', 'id' => 'brn_cd']) }}
                        @else
                        {{ Form::select('BahagianCawangan', ['' => '-- SILA PILIH --'], '', ['class' => 'form-control input-sm']) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group" align="center">
                    {{ Form::button('Carian', ['class' => 'btn btn-primary btn-sm', 'id' => 'btnsubmit']) }}
                    {{ link_to('dashboard', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div id="container" style="height: 500px; margin: 0 auto"></div>
    </div>
</div>
<!-- Modal Create Attachment Start -->
@if (session('status'))
<div class="modal inmodal fade in" id="modal-announcement" role="dialog" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='modalEditContent' style="border-radius: 30px;">
            <div class="modal-header" style="background: #1c84c6; color: white; text-align: center;">
                <strong>PENGUMUMAN</strong>
            </div>
            <div class="modal-body" style="background: white; ">
                @if(Articles::GetAnnouncement())
                    @foreach(Articles::GetAnnouncement() as $data)
                    <div class="alert alert-{{ $data->hits }}">
                        <h3>{{ $data->title_my }}</h3>
                        {{ $data->content_my }}
                    </div>
                    @endforeach
                    <?php // Session::put('announcement', 1); ?>
                @endif
            </div>
            <div class="modal-footer" style="background: #1c84c6;">
                <p class="text-center">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Tutup</button>
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Start -->
<div id="modal-show-summary" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowSummary'></div>
    </div>
</div>
<div id="modal-show-invby" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='ModalShowInvBy'></div>
    </div>
</div>
<!-- Modal End -->

@endsection

@section('script_datatable')
<script>
    $(document).ready(function(){
        passwordind = <?php echo  Auth::user()->password_ind; ?>;
        profileind = <?php echo  Auth::user()->profile_ind; ?>;
        userid = <?php echo  Auth::user()->id; ?>;
        if(passwordind === 0) {
            swal({
                title: "",
                text: "Sila kemaskini kata laluan dengan menekan butang Tukar Kata Laluan dibawah",
                type: "warning",
                timer: 600000,
                allowEscapeKey: false,
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Tukar Kata Laluan",
            }, function () {
                window.location = "/user/changepassword/" + userid;
            });
        }
        
        if(profileind === 0) {
            swal({
                title: "",
                text: "Sila kemaskini profil dengan menekan butang Kemaskini Profil dibawah",
                type: "warning",
                timer: 600000,
                allowEscapeKey: false,
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Kemaskini Profil",
            }, function () {
                window.location = "/editprofile/" + userid;
            });
        }
        
        var data = <?php echo count(Articles::GetAnnouncement()); ?>;
//        var announ = <?php // echo Session::get('announcement'); ?>;
        if(data > 0) {
            $('#modal-announcement').modal("show");
        }
        return false;
    });
    
    $('#Negeri').on('change', function (e) {
        var state_cd = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('user/getbrnlist') }}" + "/" + state_cd,
            dataType: "json",
            success:function(data){
                $('select[name="BahagianCawangan"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="BahagianCawangan"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        }); 
    });
    
    function visitorData (data, year = (new Date()).getFullYear()) {
        var title = 'Statistik Aduan Mengikut Bulan (Tahun ' + year + ')';
        $('#container').highcharts({
            chart: {
                type: 'column',
                options3d: {
                enabled: true,
                alpha: 15,
                beta: 15,
                depth: 100,
                viewDistance: 0
            }
            },
             plotOptions: {
                column: {
                    depth: 25
                }
            },
            title: {
                text: title
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Bilangan Aduan'
                }

            },
            legend: {
                enabled: false
            },
            series: [{
                name: 'Jumlah Aduan',
                colorByPoint: true,
                data: data,
                dataLabels: {
                    enabled: true,
//                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'center',
                    format: '{point.y:.0f}', // one decimal
//                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }],
        });
    }
    
    $(document).ready(function() {
        var $form = $('#search-form');
        var formData = {};

        $form.find(':input').each(function() {
            formData[ $(this).attr('name') ] = $(this).val();
        });
        $.ajax({
            url: "{{ url('dashboard/aduan-by-mnth') }}",
            type: 'GET',
            async: true,
            dataType: "json",
            data: formData,
            success: function (data) {
                visitorData(data);
            }
        });
    });
    
    $('#btnsubmit').click(function(e) {
        e.preventDefault();
        var $form = $('#search-form');
        var formData = {};

        $form.find(':input').each(function() {
            formData[ $(this).attr('name') ] = $(this).val();
        });
        var Year = formData.Tahun;
        $.ajax({
            type: 'GET',
            url: "{{ url('dashboard/aduan-by-mnth') }}",
            dataType: "json",
            data: formData,
            success:function(data){
                visitorData(data, Year);
            }
        });
    });
    
    function ShowSummary(CASEID)
    {
        $('#modal-show-summary').modal("show").find("#ModalShowSummary").load("{{ route('tugas.showsummary','') }}" + "/" + CASEID);
    }
    
    function ShowInvBy(id)
    {
        $('#modal-show-invby').modal("show").find("#ModalShowInvBy").load("{{ route('carian.showinvby','') }}" + "/" + id);
    }

    $(function() {
        var currentYear = new Date();
        var oTable = $('#users-table').DataTable({
            scrollY: 500,
            scrollX: true,
            responsive: true,
            processing: true,
            serverSide: true,
            bFilter: false,
            // aaSorting: [],
            order : [[ 8, 'desc' ]],
            pageLength: 50,
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-6'i><'col-sm-6 html5buttons'B<'pull-right'l>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>",
            language: {
                lengthMenu: 'Memaparkan _MENU_ rekod',
                zeroRecords: 'Tiada rekod ditemui',
                info: 'Memaparkan _START_-_END_ daripada _TOTAL_ rekod',
                infoEmpty: 'Tiada rekod ditemui',
                infoFiltered: '',
                paginate: {
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    first: 'Pertama',
                    last: 'Terakhir',
                },
            },
            ajax: {
                url: "{{ url('carian/getdatatablepegawai')}}",
                data: function (d) {
                    d.CA_INVSTS = $('#CA_INVSTS').val();
                    d.state = $('#state').val();
                    d.branch = $('#branch').val();
                    d.tempoh_aduan = $('#tempoh_aduan').val();
                }
            },
            columns: [
                {data: 'DT_Row_Index', name: 'id', 'width': '5%', searchable: false, orderable: false},
                {data: 'tempoh', name: 'tempoh', searchable: false, orderable: false},
                { data: 'CA_CASEID', render: function (data, type, row) {
                    return type === 'export' ? "' " + data : data;
                }, orderable: false},
                {data: 'CA_SUMMARY', name: 'CA_SUMMARY'},
                {data: 'CA_NAME', name: 'CA_NAME'},
                {data: 'CA_AGAINSTNM', name: 'CA_AGAINSTNM'},
                {data: 'CA_INVBY', name: 'CA_INVBY', searchable: false, orderable: false},
                {data: 'CA_INVSTS', name: 'CA_INVSTS'},
                {data: 'CA_RCVDT', name: 'CA_RCVDT'},
                {data: 'CA_BRNCD', name: 'CA_BRNCD'}
            ],
            buttons: [
                {
                    extend: 'excel',
                    title: 'Paparan Semua Aduan Mengikut Bahagian ('+currentYear.getFullYear()+')',
                    exportOptions: {
                        orthogonal: 'export'
                    }
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    exportOptions: { 
                        orthogonal: 'export' ,
                        columns: ':visible'
                    }
                },
                {extend: 'print',text: 'Cetak',customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ],

        });

        $('#search-form2').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    
    $('#state').on('change', function (e) {
        var state_cd = $(this).val();
        $.ajax({
            type:'GET',
            url:"{{ url('user/getbrnlist') }}" + "/" + state_cd,
            dataType: "json",
            success:function(data){
                $('select[name="branch"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="branch"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        }); 
    });

</script>

@stop
