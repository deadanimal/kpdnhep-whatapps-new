@php
    $filename = env('APP_NAME', 'eAduan 2.0') . ' ' . $title . date(' YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
@endphp
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<table>
	    <tr>
	        <td colspan="{{ $countCaseReasonTemplate > 0 ? $countCaseReasonTemplate + 3 : 4 }}">
		        {{ $title }}
		    </td>
	    </tr>
	    <tr>
	        <td colspan="{{ $countCaseReasonTemplate > 0 ? $countCaseReasonTemplate + 3 : 4 }}">
	        	Tarikh Penerimaan Aduan : Dari 
                {{ date('d-m-Y', strtotime($date_start)) }}
                Hingga 
                {{ date('d-m-Y', strtotime($date_end)) }}
	        </td>
	    </tr>
	</table>
	@include('laporan.ad51.report2.table')
</html>
@php
    exit;
    fclose($fp);
@endphp
