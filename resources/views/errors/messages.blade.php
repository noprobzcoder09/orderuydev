@php(isset($removestyle) ? $removestyle : $removestyle = false)
<div id="{{isset($id) ? $id : 'alert-message'}}">
	<div class="alert {{$removestyle ? '' : 'alert-danger'}}" style="display: none;">
		Error!
	</div>
	<div class="alert {{$removestyle ? '' : 'alert-info'}}" style="display: none;">
		Info!
	</div>
	<div class="alert {{$removestyle ? '' : 'alert-success'}}" style="display: none;">
		Success!
	</div>
</div>
