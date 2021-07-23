<?php
    use App\Ref;
    use App\Laporan\BandingAduan;
?>
<style>
    .text-align-center{
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                </div>
                <div class="table-responsive">
                    <h3 align="center">
                        LAPORAN KATEGORI TAHUNAN 
                        @if(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM < $CA_RCVDT_YEAR_TO))
                            DARI {{ $CA_RCVDT_YEAR_FROM }} HINGGA {{ $CA_RCVDT_YEAR_TO }}
                        @elseif(($CA_RCVDT_YEAR_FROM) && ($CA_RCVDT_YEAR_TO) && ($CA_RCVDT_YEAR_FROM == $CA_RCVDT_YEAR_TO))
                            {{ $CA_RCVDT_YEAR_TO }}
                        @endif
                    </h3>
                    <h3 align="center">
                        @if($CA_STATECD != '0')
                            {{ Ref::GetDescr('17', $CA_STATECD, 'ms') }}
                        @else
                            SEMUA NEGERI
                        @endif
                    </h3>
                    <h3 align="center">
                        @if($CA_DEPTCD != '0')
                            {{ Ref::GetDescr('315', $CA_DEPTCD, 'ms') }}
                        @else
                            SEMUA BAHAGIAN
                        @endif
                    </h3>
                    <table class="table table-striped table-bordered table-hover" style="width: 100%; border:1px solid; border-collapse: collapse" border="1">
                        <thead>
                            <tr>
                                <th>Bil.</th>
                                <th>Kategori</th>
                                @for($year=$CA_RCVDT_YEAR_FROM; $year<=$CA_RCVDT_YEAR_TO; $year++)
                                    <th>{{ $year }}</th>
                                @endfor
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mRefCategory as $category)
                                <tr>
                                    <td class="text-align-center">{{ $loop->iteration }}</td>
                                    <td>{{ $category->descr }}</td>
                                    @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                                        <td class="text-align-center">{{ BandingAduan::jumlahkategoritahun($category->code, $CA_RCVDT_YEAR, $CA_STATECD, $CA_DEPTCD) }}</td>
                                    @endfor
                                    <td class="text-align-center">{{ BandingAduan::jumlahkategoritahunsemuatahun($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $category->code, $CA_STATECD, $CA_DEPTCD) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-align-center">Jumlah</td>
                                @for($CA_RCVDT_YEAR=$CA_RCVDT_YEAR_FROM; $CA_RCVDT_YEAR<=$CA_RCVDT_YEAR_TO; $CA_RCVDT_YEAR++)
                                    <td class="text-align-center">{{ BandingAduan::jumlahkategoritahunsemuakategori($CA_RCVDT_YEAR, $CA_STATECD, $CA_DEPTCD) }}</td>
                                @endfor
                                <td class="text-align-center">{{ BandingAduan::jumlahkategoritahunsemua($CA_RCVDT_YEAR_FROM, $CA_RCVDT_YEAR_TO, $CA_STATECD, $CA_DEPTCD) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>