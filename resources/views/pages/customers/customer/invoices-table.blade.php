<style type="text/css">
	#table-active-subs-invoices tr td {
		border-bottom: 1px solid #ccc !important;
	}
     #table-active-subs-invoices thead tr th{
        border: 0 none !important;
    }
</style>
<?php
    $invoiceDownloadUrl = strtolower(env('APP_ENV')) == 'live' ? env('LIVE_INFS_APP_URL') : env('INFS_APP_URL');
?>
<table cellpadding="5" cellspacing="0" border="0" style="padding: 0;margin: 9; width: 100%;border: 0px solid #ccc !important;" class="" id="table-active-subs-invoices">
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Invoice Date</th>
            <th class="text-right">Amount</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    	@foreach($data as $row)
        <tr>
        	<td>{{$row->id}}</td>
            <td>{{$row->date}}</td>
            <td class="text-right">{{__('config.currency').number_format($row->amount, 2)}}</td>
        	<td class="text-right">
        		<a href="{{url($invoiceDownloadUrl.'?orderid='.$row->id)}}" target="_blank" class="download-invoice-control btn bg-gray-100 btn-sm">
        			<i class="fa fa-download"></i>
        			View Invoice	
        		</a>
        	</td>
        </tr>
        @endforeach
    </tbody>
</table>
