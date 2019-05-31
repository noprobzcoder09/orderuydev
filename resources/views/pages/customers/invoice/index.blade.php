@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('customer-invoice', $id))

@section('css')
<link href="{{asset('vendors/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript">


</script>
@endsection

@section('content')
@include($view.'content')
</div>
</div>
<!--/.row-->
@endsection