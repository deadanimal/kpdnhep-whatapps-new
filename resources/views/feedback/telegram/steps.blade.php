<div class="row">
    <div class="col-md-12">
        <div id="step_header" class="step-bg">
            <a href="{{route('telegram.index')}}" style="width: 33%">
                <div id="step_header_1"
                     class="step_header_item step-col {{$step == 1 ? 'active' : ''}}">
                    <div class="step-title uppercase font-grey-cascade"><i class="fa fa-exclamation-triangle"></i> Aktif
                    </div>
                </div>
            </a>
            <a href="{{route('telegram.mytask.index')}}" style="width: 33%">
                <div id="step_header_2"
                     class="step_header_item step-col {{$step == 2 ? 'active' : ''}}">
                    <div class="step-title uppercase font-grey-cascade"><i class="fa fa-inbox"></i> Tugasan</div>
                </div>
            </a>
            <a href="{{route('telegram.all.index')}}" style="width: 33%">
                <div id="step_header_3"
                     class="step_header_item step-col {{$step == 3 ? 'active' : ''}}">
                    <div class="step-title uppercase font-grey-cascade"><i class="fa fa-inbox"></i> Semua</div>
                </div>
            </a>
        </div>
    </div>
</div>
