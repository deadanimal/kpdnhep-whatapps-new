<style>
    th, td {
        text-align: left;
        font-size: 12px;
    }
</style>
<div class="row">
    <div style="text-align: center;">
        <h3 align="center">{{ $title }}</h3>
        <h3 align="center">{{ $date_start }} - {{ $date_end }}</h3>
    </div>
    <div class="">
        @include('laporan.feedback.r2.table')
    </div>
</div>
