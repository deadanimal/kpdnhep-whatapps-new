<style>
    th, td {
        text-align: left;
        font-size: 12px;
    }
</style>
<div class="row">
    <div style="text-align: center;">
        <h3 align="center">{{ $title }}</h3>
        <h3 align="center">
            {{ $year }}
        </h3>
    </div>
    <div class="">
        @include('laporan.feedback.r1.table')
    </div>
</div>
