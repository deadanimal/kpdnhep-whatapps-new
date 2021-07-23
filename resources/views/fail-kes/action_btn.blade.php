@if($FailKes->status == '1')
    <a href="{{ route('fail-kes.checkfile',$FailKes->id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Semak Fail"><i class="fa fa-file-o"></i></a>
@elseif($FailKes->status == '2' && $FailKes->migrated_ind == '0')
    <a href="{{ route('fail-kes.migrate',$FailKes->id) }}" onclick="return confirm('Anda pasti untuk pindah data?')" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="right" title="Pindah"><i class="fa fa-database"></i></a>
@endif
    <button class="btn btn-xs btn-danger" onclick="return confirm('Anda pasti untuk hapuskan rekod ini?')" data-toggle="tooltip" data-placement="right" title="Hapus"><i class="fa fa-trash"></i></button>