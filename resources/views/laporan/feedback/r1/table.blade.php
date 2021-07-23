<table class="table table-striped table-bordered table-hover" style="width: 100%">
    <thead>
    <tr>
        <th width="25%">Kategori</th>
        @for($i = 1; $i < 13; $i++)
            <th>
                @if($gen=='w')
                    <a href="{{route('laporan.feedback.r3.index')}}?year={{$year}}&month={{$i}}"
                       style="color:white">{{Carbon\Carbon::createFromFormat('m', $i)->format('M')}}</a>
                @else
                    {{Carbon\Carbon::createFromFormat('m', $i)->format('M')}}
                @endif
            </th>
        @endfor
        <th width="10%">Jumlah</th>
        <th width="10%">Peratusan (%)</th>
    </tr>
    </thead>
    <tbody>
    <tr style="background-color: lightblue">
        <th>Aduan KPDNHEP</th>
        @foreach($data_final_case_info as $datum)
            <th>{{$datum}}</th>
        @endforeach
        <th>{{ $data_final_pct['case_info']['total']['total'] }}</th>
    </tr>
    <tr>
        <td>Aduan KPDNHEP</td>
        @foreach($data_final_case_info as $datum)
            <td>{{$datum}}</td>
        @endforeach
        <td></td>
    </tr>
    @foreach($data_final as $key => $data_final_datum)
        <tr style="background-color: lightblue">
            <th>{{ $category_name[$key] }}</th>
            @foreach($data_final_datum['total'] as $datum)
                <th>{{$datum}}</th>
            @endforeach
            <th>{{ $data_final_pct[$key]['total']['total'] }}</th>
        </tr>
        @foreach($data_final_datum['child'] as $k => $datum_child)
            <tr>
                <td>{{ $template_by_title[$k] }}</td>
                @foreach($datum_child as $datum)
                    <td>{{$datum}}</td>
                @endforeach
                <td></td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
    <tfoot>
    <tr style="background-color: lightblue">
        <th>Jumlah Maklumbalas</th>
        @foreach($data_final_total['total'] as $datum)
            <td><b>{{$datum}}</b></td>
        @endforeach
        <td><b>100.00</b></td>
    </tr>
    </tfoot>
</table>

