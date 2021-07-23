<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
<style>
    h5, td {
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="table-responsive">
                    <h5>LAPORAN JUMLAH KERUGIAN MENGIKUT BULAN & KATEGORI ADUAN</h5>
                    <h5>
                        {{ $CA_RCVDT_YEAR }}
                    </h5>
                    <table class="table table-striped table-bordered table-hover" style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
                        @if($action != '')
                            <thead>
                                <tr>
                                    <th>Bil.</th>
                                    <th>Kategori Aduan</th>
                                    @foreach ($mRefMonth as $month)
                                        <th>{{ $month->descr }}</th>
                                    @endforeach
                                        <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataFinal as $key => $value)
                                    <tr>
                                        <td>{{ $bil++ }}</td>
                                        <td>{{ $categoryList[$key] }}</td>
                                        @foreach ($mRefMonth as $month)
                                            <td>{{ number_format($value[$month->code],2) }}</td>
                                        @endforeach
                                            <td>{{ number_format($value['13'],2) }}</td>
                                    </tr>
                                    <?php
                                        $countmonth1total += $value['1'];
                                        $countmonth2total += $value['2'];
                                        $countmonth3total += $value['3'];
                                        $countmonth4total += $value['4'];
                                        $countmonth5total += $value['5'];
                                        $countmonth6total += $value['6'];
                                        $countmonth7total += $value['7'];
                                        $countmonth8total += $value['8'];
                                        $countmonth9total += $value['9'];
                                        $countmonth10total += $value['10'];
                                        $countmonth11total += $value['11'];
                                        $countmonth12total += $value['12'];
                                        $countmonth13total += $value['13'];
                                    ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td>Jumlah</td>
                                    @foreach ($mRefMonth as $month)
                                        <td>{{ number_format(${'countmonth'.$month->code.'total'},2) }}</td>
                                    @endforeach
                                        <td>{{ number_format(${'countmonth13total'},2) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>