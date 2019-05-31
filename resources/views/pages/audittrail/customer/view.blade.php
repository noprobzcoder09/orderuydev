@extends('layouts.app')

@if(Route::current()->getName() == 'dashboard' || Route::current()->getName() == 'dashboard.home')
    @section('breadcrumbs', Breadcrumbs::render('home'))
@else
    @section('breadcrumbs', Breadcrumbs::render('customer-audit-view'))
@endif

@section('css')

@endsection

@section('content')

<div class="m-content" ng-app="AuditTrail">

    <div view-audit-trail id="{{$id}}" customer-id="{{$customer_id}}"></div>

</div>

@endsection

@section('script')
	<script src="{{asset('/vendors/audit_trail/src/env/'.env('AT').'.js')}}"></script>
    <script src="{{asset('/vendors/audit_trail/dist/auditTrail.js')}}"></script>
@endsection


