<style>
    table, th, td {
        border: 1px solid; 
        border-collapse: collapse;
    }
</style>
<div class="row">
    <div style="text-align: center;">
        <h4>{{ $vars['title'] }}</h4>
        <h4>
            Tarikh Penerimaan Aduan : Dari 
            {{ date('d-m-Y', strtotime($vars['datestart'])) }}
            Hingga 
            {{ date('d-m-Y', strtotime($vars['dateend'])) }}
        </h4>
        <h4>
            Lokasi Pihak Yang Diadu (PYDA) : {{ $vars['locationname'] }}
        </h4>
    </div>
    <div>
        @include('laporan.integriti.branch.table')
    </div>
</div>
