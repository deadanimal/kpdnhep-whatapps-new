<style>
    th, td {
        font-size: 12px;
    }
    div {
        text-align: center;
        font-size: 12px;
    }
</style>
<div>
    {{ $title }}
</div>
<div>
    Tarikh Penerimaan Aduan : Dari
    {{ date('d-m-Y', strtotime($date_start)) }}
    Hingga
    {{ date('d-m-Y', strtotime($date_end)) }}
</div>
@include('laporan.ad52.report1.table')
