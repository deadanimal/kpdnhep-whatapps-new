<table class="table table-striped table-bordered table-hover" style="width: 100%">
    <thead>
        <tr>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($case_infos as $datum)
            <tr>
                @foreach($headers as $header)
                    <td>{{$datum[$header]}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
        </tr>
    </tfoot>
</table>