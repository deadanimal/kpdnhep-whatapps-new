@if(count($Carian->InvBy) == '1')
<a onclick="ShowInvBy('{{$Carian->InvBy->id}}')">{{$Carian->InvBy->name}}</a>
@endif