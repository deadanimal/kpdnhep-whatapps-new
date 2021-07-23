<style>
    th, td {
        text-align: center;
    }
</style>
<table class="table table-striped table-bordered table-hover" style="width: 100%" border="1">
	<thead>
		<tr>
			<th>#</th>
			<th>Bahagian / Cawangan / Agensi KPDNHEP</th>
			<th>Jumlah</th>
		</tr>
	</thead>
	<tbody>
		@foreach($vars['query'] as $key => $data)
			<tr>
				<td>{{ $key + 1 }}</td>
				<td>{{ $data->branch_agency_name }}</td>
				<td>{{ $data->total }}</td>
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th>Jumlah</th>
			<th>{{ $vars['dataCountTotal'] }}</th>
		</tr>
	</tfoot>
</table>
