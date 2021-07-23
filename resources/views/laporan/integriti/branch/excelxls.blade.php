@php
    $filename = $vars['titleshort'].date(' YmdHis').'.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);
    $fp = fopen('php://output', 'w');
@endphp
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<table>
	    <tr>
	        <td colspan="4"><strong>{{ $vars['title'] }}</strong></td>
	    </tr>
	    <tr>
	        <td colspan="4">
	        	Tarikh Penerimaan Aduan : Dari 
                {{ date('d-m-Y', strtotime($vars['datestart'])) }}
                Hingga 
                {{ date('d-m-Y', strtotime($vars['dateend'])) }}
	        </td>
	    </tr>
	    <tr>
	        <td colspan="4">
        		Lokasi Pihak Yang Diadu (PYDA) : {{ $vars['locationname'] }}
	        </td>
	    </tr>
	    <tr></tr>
	</table>
	@include('laporan.integriti.branch.table')
</html>
@php
    exit;
    fclose($fp);
@endphp
