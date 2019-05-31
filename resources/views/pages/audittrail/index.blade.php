@extends('layouts.app')

@if(Route::current()->getName() == 'dashboard' || Route::current()->getName() == 'dashboard.home')
    @section('breadcrumbs', Breadcrumbs::render('home'))
@else
    @section('breadcrumbs', Breadcrumbs::render('audit'))
@endif

@section('css')
<link rel="stylesheet" href="{{ asset('/template/custom/datatables/datatables.bundle.css') }}">

<style type="text/css">
    #table-customer-filter-container {
        display: none;
    }
</style>
@endsection


@section('content')

<div class="m-content" ng-app="AuditTrail">

    <div audit-trail></div>

</div>

@endsection

@section('script')
	<script src="{{asset('/vendors/audit_trail/src/env/'.env('AT').'.js')}}"></script>
    <script src="{{asset('/vendors/audit_trail/dist/auditTrail.js')}}"></script>
@endsection


