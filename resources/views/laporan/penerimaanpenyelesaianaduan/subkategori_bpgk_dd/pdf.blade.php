<style>
    th, td {
        text-align: left;
        font-size: 12px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td colspan="18">
            <div style="text-align: center;"><h3>LAPORAN MENGIKUT STATUS DAN KATEGORI ADUAN BAGI</h3></div>
        </td>
    </tr>
</table>
@if($search)
    <div class="row">
        <div style="text-align: center;">
            <h3>
                <div style="text-align: center;">
                    LAPORAN MENGIKUT STATUS DAN KATEGORI ADUAN BAGI
                    TAHUN {{ date('d-m-Y', strtotime($dateStart)) }}
                    SEHINGGA {{ date('d-m-Y', strtotime($dateEnd)) }}<br>
                    {{ $departmentDesc }} dan
                    Negeri {{ $stateDesc }}
                </div>
            </h3>
        </div>
        <div class="table-responsive">
            <table id="state-table" width="950" class="table table-striped table-bordered table-hover dataTables-example"
                   style="overflow-wrap: break-word; width: 100%; font-size: 10px; border:1px solid; border-collapse: collapse;" border="1">
                <thead>
                <tr>
                    <th rowspan="3">Bil.</th>
                    <th rowspan="3">Kategori Aduan</th>
                    <th colspan="12">Status Aduan</th>
                </tr>
                <tr>
                    <th rowspan="2">Diterima</th>
                    <th rowspan="2">Belum Diagih</th>
                    <th rowspan="2">Dalam Siasatan</th>
                    <th colspan="7">Tindakan Aduan</th>
                    <th rowspan="2">Belum Selesai</th>
                    <!--                                <th rowspan="2">Tertunggak<br>(Belum Selesai + Tertunggak -1)</th>-->
                </tr>
                <tr>
                    <th>Diselesaikan</th>
                    <th>Ditutup</th>
                    <th>Agensi Lain</th>
                    <th>Tribunal</th>
                    <th>Pertanyaan</th>
                    <th>Maklumat Tidak Lengkap</th>
                    <th>Luar Bidang Kuasa</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                @foreach($dataFinal as $key => $datum)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$subdepartmentList[$key]}}</td>
                        <td>{{$datum['selesai']}}</td>
                        <td>{{$datum['belum agih']}}</td>
                        <td>{{$datum['dalam siasatan']}}</td>
                        <td>{{$datum['selesai']}}</td>
                        <td>{{$datum['tutup']}}</td>
                        <td>{{$datum['agensi lain']}}</td>
                        <td>{{$datum['tribunal']}}</td>
                        <td>{{$datum['pertanyaan']}}</td>
                        <td>{{$datum['maklumat tak lengkap']}}</td>
                        <td>{{$datum['luar bidang']}}</td>
                        <td>{{$datum['total']}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfooter>
                    <tr>
                        <th></th>
                        <th>Jumlah</th>
                        <th>{{$dataCounter['selesai']}}</th>
                        <th>{{$dataCounter['belum agih']}}</th>
                        <th>{{$dataCounter['dalam siasatan']}}</th>
                        <th>{{$dataCounter['selesai']}}</th>
                        <th>{{$dataCounter['tutup']}}</th>
                        <th>{{$dataCounter['agensi lain']}}</th>
                        <th>{{$dataCounter['tribunal']}}</th>
                        <th>{{$dataCounter['pertanyaan']}}</th>
                        <th>{{$dataCounter['maklumat tak lengkap']}}</th>
                        <th>{{$dataCounter['luar bidang']}}</th>
                        <th>{{$dataCounter['total']}}</th>
                    </tr>
                </tfooter>
            </table>
        </div>
    </div>
    <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
    </div>
@endif