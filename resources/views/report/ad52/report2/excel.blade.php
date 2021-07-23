<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<table>
		<thead>
		    <tr>
	        	<td colspan="{{ $data['countacttemplatecolumn'] ?? 1 }}" style="text-align:center">
		        	{{ $data['appname'] ?? '' }}
		        </td>
		    </tr>
		    <tr>
	        	<td colspan="{{ $data['countacttemplatecolumn'] ?? 1 }}" style="text-align:center">
		        	{{ $data['title'] ?? '' }}
		        </td>
		    </tr>
		    <tr>
		        <td colspan="{{ $data['countacttemplatecolumn'] ?? 1 }}" style="text-align:center">
		        	{{ $data['datetext'] ?? '' }}
		        </td>
		    </tr>
		    <tr>
		        <td colspan="{{ $data['countacttemplatecolumn'] ?? 1 }}" style="text-align:center">
		        	{{ $data['statetext'] ?? '' }}
		        </td>
		    </tr>
		</thead>
	</table>
	@includeIf('report.ad52.report2.table')
</html>
