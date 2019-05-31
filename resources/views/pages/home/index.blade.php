@extends('layouts.app')

@section('css')
<link href="{{asset('vendors/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style type="text/css">
.container-fluid .dataTables_wrapper {
	padding: 10px !important;
}
</style>

<link href="{{asset('vendors/css/daterangepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('vendors/css/gauge.min.css')}}" rel="stylesheet">
@endsection

@section('script')
<script src="{{asset('vendors/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript">
	$(function(){
		$('.datatable').DataTable();
// $('.datatable').css({'border-collapse':'collapse !important'});
$('.datatable').attr('style', 'border-collapse: collapse !important');
});

</script>
<!-- Plugins and scripts required by all views -->
<script src="{{asset('vendors/js/Chart.min.js')}}"></script>

 <!-- Plugins and scripts required by this views -->
<script src="{{asset('vendors/js/toastr.min.js')}}"></script>
<script src="{{asset('vendors/js/gauge.min.js')}}"></script>
<script src="{{asset('vendors/js/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/daterangepicker.min.js')}}"></script>

<script src="{{asset('js/main.js')}}"></script>
@endsection

@section('content')

<div class="row">
	<div class="col-sm-6 col-lg-3">
		<div class="card text-white bg-primary">
			<div class="card-body pb-0">
				<h4 class="mb-0">100,010</h4>
				<p>Members</p>
			</div>
			<div class="chart-wrapper px-3" style="height:70px;">
				<canvas id="card-chart1" class="chart" height="70"></canvas>
			</div>
		</div>
	</div>
	<!--/.col-->

	<div class="col-sm-6 col-lg-3">
		<div class="card text-white bg-info">
			<div class="card-body pb-0">
				<button type="button" class="btn btn-transparent p-0 float-right">
					<i class="icon-location-pin"></i>
				</button>
				<h4 class="mb-0">$240,000.0</h4>
				<p>Sales</p>
			</div>
			<div class="chart-wrapper px-3" style="height:70px;">
				<canvas id="card-chart2" class="chart" height="70"></canvas>
			</div>
		</div>
	</div>
	<!--/.col-->

	<div class="col-sm-6 col-lg-3">
		<div class="card text-white bg-warning">
			<div class="card-body pb-0">
				<h4 class="mb-0">87,000</h4>
				<p>Active Subscriptions</p>
			</div>
			<div class="chart-wrapper" style="height:70px;">
				<canvas id="card-chart3" class="chart" height="70"></canvas>
			</div>
		</div>
	</div>
	<!--/.col-->

	<div class="col-sm-6 col-lg-3">
		<div class="card text-white bg-danger">
			<div class="card-body pb-0">
				<h4 class="mb-0">300</h4>
				<p>Inactive Subscriptions</p>
			</div>
			<div class="chart-wrapper px-3" style="height:70px;">
				<canvas id="card-chart4" class="chart" height="70"></canvas>
			</div>
		</div>
	</div>
	<!--/.col-->
</div>
<!--/.row-->

<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-sm-5">
				<h4 class="card-title mb-0">Revenue</h4>
				<div class="small text-muted">{{date('F Y')}}</div>
			</div>
			<!--/.col-->
			<div class="col-sm-7 d-none d-md-block">
				<button type="button" class="btn btn-primary float-right"><i class="icon-cloud-download"></i></button>
				<div class="btn-group btn-group-toggle float-right mr-3" data-toggle="buttons">
					<label class="btn btn-outline-secondary">
						<input type="radio" name="options" id="option1" autocomplete="off"> Day
					</label>
					<label class="btn btn-outline-secondary active">
						<input type="radio" name="options" id="option2" autocomplete="off" checked=""> Month
					</label>
					<label class="btn btn-outline-secondary">
						<input type="radio" name="options" id="option3" autocomplete="off"> Year
					</label>
				</div>
			</div>
			<!--/.col-->
		</div>
		<!--/.row-->
		<div class="chart-wrapper" style="height:300px;margin-top:40px;">
			<canvas id="main-chart" class="chart" height="300"></canvas>
		</div>
	</div>
</div>
<!--/.card-->

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-5">
						<h3 class="card-title clearfix mb-0">Deliveries Timing</h3>
					</div>
					<div class="col-sm-7">
						<button type="button" class="btn btn-outline-primary float-right ml-3"><i class="icon-cloud-download"></i> &nbsp; Download</button>
						<fieldset class="form-group float-right">
							<div class="input-group float-right" style="width:240px;">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input name="daterange" class="form-control date-picker" type="text">
							</div>
						</fieldset>
					</div>
				</div>
				<hr class="m-0">
				<div class="row">
					<div class="col-sm-12 col-lg-4">
						<div class="row">
							<div class="col-sm-6">
								<div class="callout callout-info">
									<small class="text-muted">New Clients</small>
									<br>
									<strong class="h4">9,123</strong>
									<div class="chart-wrapper">
										<canvas id="sparkline-chart-1" width="100" height="30"></canvas>
									</div>
								</div>
							</div>
							<!--/.col-->
							<div class="col-sm-6">
								<div class="callout callout-danger">
									<small class="text-muted">Recuring Clients</small>
									<br>
									<strong class="h4">22,643</strong>
									<div class="chart-wrapper">
										<canvas id="sparkline-chart-2" width="100" height="30"></canvas>
									</div>
								</div>
							</div>
							<!--/.col-->
						</div>
						<!--/.row-->
						<hr class="mt-0">
						<ul class="horizontal-bars">
							<li>
								<div class="title">
									Monday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 34%" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<div class="title">
									Tuesday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 56%" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 94%" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<div class="title">
									Wednesday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<div class="title">
									Thursday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 43%" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 91%" aria-valuenow="91" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<div class="title">
									Friday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 22%" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 73%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<div class="title">
									Saturday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 53%" aria-valuenow="53" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 82%" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<div class="title">
									Sunday
								</div>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-info" role="progressbar" style="width: 9%" aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="progress progress-xs">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 69%" aria-valuenow="69" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li class="legend">
								<span class="badge badge-pill badge-info"></span>
								<small>New clients</small> &nbsp;
								<span class="badge badge-pill badge-danger"></span>
								<small>Recurring clients</small>
							</li>
						</ul>
					</div>
					<!--/.col-->
					<div class="col-sm-6 col-lg-4">
						<div class="row">
							<div class="col-sm-6">
								<div class="callout callout-warning">
									<small class="text-muted">Active Subscriptions</small>
									<br>
									<strong class="h4">78,623</strong>
									<div class="chart-wrapper">
										<canvas id="sparkline-chart-3" width="100" height="30"></canvas>
									</div>
								</div>
							</div>
							<!--/.col-->
							<div class="col-sm-6">
								<div class="callout callout-success">
									<small class="text-muted">Cancelled</small>
									<br>
									<strong class="h4">809</strong>
									<div class="chart-wrapper">
										<canvas id="sparkline-chart-4" width="100" height="30"></canvas>
									</div>
								</div>
							</div>
							<!--/.col-->
						</div>
						<!--/.row-->
						<hr class="mt-0">
						<ul class="horizontal-bars type-2">
							<li>
								<span class="title">Plan A</span>
								<span class="value">191,235
									<span class="text-muted small">(56%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 56%" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Plan B</span>
								<span class="value">51,223
									<span class="text-muted small">(15%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Plan C</span>
								<span class="value">37,564
									<span class="text-muted small">(11%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 11%" aria-valuenow="11" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Plan D</span>
								<span class="value">27,319
									<span class="text-muted small">(8%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Plan E</span>
								<span class="value">1,319
									<span class="text-muted small">(8%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Plan F</span>
								<span class="value">9,319
									<span class="text-muted small">(8%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li>
								<span class="title">Plan G</span>
								<span class="value">12,319
									<span class="text-muted small">(8%)</span>
								</span>
								<div class="bars">
									<div class="progress progress-xs">
										<div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</li>
							<li class="divider text-center">
								<button type="button" class="btn btn-sm btn-link text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="show more"><i class="icon-options"></i></button>
							</li>
						</ul>
					</div>
					<!--/.col-->
					<div class="col-sm-6 col-lg-4">
						<div class="row">
							<div class="col-sm-6">
								<div class="callout callout-primary">
									<small class="text-muted">Bounce Rate</small>
									<br>
									<strong class="h4">5%</strong>
									<div class="chart-wrapper">
										<canvas id="sparkline-chart-6" width="100" height="30"></canvas>
									</div>
								</div>
							</div>
							<!--/.col-->
						</div>
						<!--/.row-->
						<hr class="mt-0">
						<ul class="icons-list">
							<li>
								<i class="icon-social-spotify bg-primary"></i>
								<div class="desc">
									<div class="title">Meal A</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Sold this week</div>
									<strong>1.924</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li>
								<i class="icon-social-spotify bg-info"></i>
								<div class="desc">
									<div class="title">Meal B</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Sold this week</div>
									<strong>1.224</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li>
								<i class="icon-social-spotify bg-warning"></i>
								<div class="desc">
									<div class="title">Meal C</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Sold this week</div>
									<strong>1.163</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li>
								<i class="icon-social-spotify bg-danger"></i>
								<div class="desc">
									<div class="title">Meal D</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Sold this week</div>
									<strong>928</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li>
								<i class="icon-social-spotify bg-success"></i>
								<div class="desc">
									<div class="title">Meal E</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Sold this week</div>
									<strong>893</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li>
								<i class="icon-social-spotify bg-danger"></i>
								<div class="desc">
									<div class="title">Meal F</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Downloads</div>
									<strong>121.924</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li>
								<i class="icon-social-spotify bg-warning"></i>
								<div class="desc">
									<div class="title">Meal G</div>
									<small>Lorem ipsum dolor sit amet</small>
								</div>
								<div class="value">
									<div class="small text-muted">Uploaded</div>
									<strong>12.125</strong>
								</div>
								<div class="actions">
									<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
								</div>
							</li>
							<li class="divider text-center">
								<button type="button" class="btn btn-sm btn-link text-muted" data-toggle="tooltip" data-placement="top" title="show more"><i class="icon-options"></i></button>
							</li>
						</ul>
					</div>
					<!--/.col-->
				</div>
				<!--/.row-->
				<br>
				<table class="table table-responsive-sm table-hover table-outline mb-0">
					<thead class="thead-light">
						<tr>
							<th class="text-center"><i class="icon-people"></i></th>
							<th>Customer</th>
							<th class="text-center">Delivery Zone</th>
							<th>Delivery Timing</th>
							<th class="text-center">Payment Method</th>
							<th>Email</th>
							<th class="text-center">Phone</th>
							<th class="text-center"><i class="icon-settings"></i></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">
								<div class="avatar">
									<img src="{{asset('images/avatars/1.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
									<span class="avatar-status badge-success"></span>
								</div>
							</td>
							<td>
								<div>Yiorgos Avraamu</div>
								<div class="small text-muted">
									<span>New</span> | Registered: Jan 1, 2015
								</div>
							</td>
							<td class="text-center">
								Melbourne
							</td>
							<td>
								Thursday
							</td>
							<td class="text-center">
								<i class="fa fa-cc-mastercard" style="font-size:24px"></i>
							</td>
							<td>
								avraamu@gmail.com
							</td>
							<td class="text-center">
								(03) 9646 1960
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
							</td>
						</tr>
						<tr>
							<td class="text-center">
								<div class="avatar">
									<img src="{{asset('images/avatars/2.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
									<span class="avatar-status badge-danger"></span>
								</div>
							</td>
							<td>
								<div>Avram Tarasios</div>
								<div class="small text-muted">

									<span>Recurring</span> | Registered: Jan 1, 2015
								</div>
							</td>
							<td class="text-center">
								Canberra
							</td>
							<td>
								Thursday
							</td>
							<td class="text-center">
								<i class="fa fa-cc-mastercard" style="font-size:24px"></i>
							</td>
							<td>
								tarasios@gmail.com
							</td>
							<td class="text-center">
								(03) 9646 2653
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
							</td>
						</tr>
						<tr>
							<td class="text-center">
								<div class="avatar">
									<img src="{{asset('images/avatars/3.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
									<span class="avatar-status badge-warning"></span>
								</div>
							</td>
							<td>
								<div>Quintin Ed</div>
								<div class="small text-muted">
									<span>New</span> | Registered: Jan 1, 2015
								</div>
							</td>
							<td class="text-center">
								Central Coast
							</td>
							<td>
								Thursday
							</td>
							<td class="text-center">
								<i class="fa fa-cc-mastercard" style="font-size:24px"></i>
							</td>
							<td>
								quintin@gmail.com
							</td>
							<td class="text-center">
								(03) 0739 8612
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
							</td>
						</tr>
						<tr>
							<td class="text-center">
								<div class="avatar">
									<img src="{{asset('images/avatars/4.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
									<span class="avatar-status badge-dark"></span>
								</div>
							</td>
							<td>
								<div>Enéas Kwadwo</div>
								<div class="small text-muted">
									<span>New</span> | Registered: Jan 1, 2015
								</div>
							</td>
							<td class="text-center">
								Newcastle
							</td>
							<td>
								Sunday
							</td>
							<td class="text-center">
								<i class="fa fa-cc-mastercard" style="font-size:24px"></i>
							</td>
							<td>
								kwadwo@gmail.com
							</td>
							<td class="text-center">
								(03) 0739 2625
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
							</td>
						</tr>
						<tr>
							<td class="text-center">
								<div class="avatar">
									<img src="{{asset('images/avatars/5.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
									<span class="avatar-status badge-success"></span>
								</div>
							</td>
							<td>
								<div>Agapetus Tadeáš</div>
								<div class="small text-muted">
									<span>New</span> | Registered: Jan 1, 2015
								</div>
							</td>
							<td class="text-center">
								Sydney
							</td>
							<td>
								Sunday
							</td>
							<td class="text-center">
								<i class="fa fa-cc-mastercard" style="font-size:24px"></i>
							</td>
							<td>
								agapetus@gmail.com
							</td>
							<td class="text-center">
								(03) 8912 6777
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
							</td>
						</tr>
						<tr>
							<td class="text-center">
								<div class="avatar">
									<img src="{{asset('images/avatars/6.jpg')}}" class="img-avatar" alt="admin@bootstrapmaster.com">
									<span class="avatar-status badge-danger"></span>
								</div>
							</td>
							<td>
								<div>Friderik Dávid</div>
								<div class="small text-muted">
									<span>New</span> | Registered: Jan 1, 2015
								</div>
							</td>
							<td class="text-center">
								Wollongong
							</td>
							<td>
								Sunday
							</td>
							<td class="text-center">
								<i class="fa fa-cc-mastercard" style="font-size:24px"></i>
							</td>
							<td>
								friderik@gmail.com
							</td>
							<td class="text-center">
								(03) 7889 3444
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-link text-muted"><i class="icon-settings"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!--/.col-->
</div>
<!--/.row-->
@endsection