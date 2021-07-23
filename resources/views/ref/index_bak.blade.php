@extends('layouts.app')
@section('content')
  <h2 class="sub-header">Simple DataTables in laravel 5.4</h2>
  <div class="row">
    <div class="col-md-9">
      <a href="{{ url('admin/posts/new-post')}}" class="btn btn-primary btn-sm">Add New Post</a>
    </div>
<br>
  </div>
  <div class="table-responsive">
    <table class="table table-striped" id="allposts">
      <thead>
      <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Description</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
  </div>
@stop

@push('scripts')
<script src="https://datatables.yajrabox.com/js/jquery.min.js"></script>
<script src="https://datatables.yajrabox.com/js/bootstrap.min.js"></script>
<script src="https://datatables.yajrabox.com/js/jquery.dataTables.min.js"></script>
<script src="https://datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
<script src="https://datatables.yajrabox.com/js/handlebars.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.0.0/jquery.mark.min.js"></script>
<script type="text/javascript">
  $(function(){
    $('#allposts').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{!! URL::asset('ref/postsdata') !!}',
      columns : [
        { data: 'id', name: 'id' },
        { data: 'title', name: 'title' },
        { data: 'description', name: 'description' },
        { data: 'updated_at', name: 'updated_at' }
      ]
    });
  });
</script>
@endpush