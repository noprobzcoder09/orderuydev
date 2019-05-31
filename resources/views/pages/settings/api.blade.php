@extends('layouts.app')

@if(Route::current()->getName() == 'dashboard' || Route::current()->getName() == 'dashboard.home')
    @section('breadcrumbs', Breadcrumbs::render('home'))
@else
    @section('breadcrumbs', Breadcrumbs::render('api'))
@endif

@section('css')

@endsection


@section('content')

<div class="m-content" ng-app="ApiSetting">

    <div api-setting env-type="{{env('APP_ENV')}}" reauth-status="{{session('status_code')}}"></div>

</div>

@endsection

@section('script')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

	<script src="{{asset('/vendors/audit_trail/src/env/'.env('AT').'.js')}}"></script>
    <script src="{{asset('/vendors/api_settings/ApiSetting.js')}}"></script>
@endsection


