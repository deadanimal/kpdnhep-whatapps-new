<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th colspan="4">
            Cawangan:{{ $branchName }}<br/>
            Tarikh:{{ $dateStart->format('d-m-Y') }} hingga {{ $dateEnd->format('d-m-Y') }}
        </th>
    </tr>
    <tr>
        <th style="border: 1px solid #000; background: #f3f3f3;" class="col-lg-1">Bil.</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Penyiasat</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Unit</th>
        <th style="border: 1px solid #000; background: #f3f3f3;">Jumlah Aduan</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total=0;
    @endphp
    @foreach ($dataFinal as $key => $datum)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $datum->investigator_name }}</td>
            <td>{{ $datum->branch_name }}</td>
            <td>{{ $datum->total }}</td>
        </tr>
        @php
            $total += $datum->total;
        @endphp
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th></th>
        <th>Jumlah</th>
        <th>{{$total}}</th>
    </tr>
    </tfoot>
</table>
</html>

