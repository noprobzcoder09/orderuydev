<style type="text/css">
	#table-active-subs-week tr td {
		border-bottom: 1px solid #ccc !important;
	}

    #table-active-subs-week  tr:last-child td {
        border-bottom: 0 none !important;
    }

    #table-active-subs-week tr td {
        border-top: 0 none !important;
    }


    #table-active-subs-week thead tr th{
        border: 0 none !important;
    }
</style>
<table cellpadding="5" cellspacing="0" border="0" style="margin: 0 !important;padding: 0px !important; width: 100%;border: 0 solid #ccc !important;" class="" id="table-active-subs-week">
    <tbody>
    	@foreach($data as $row)
        <tr id="weeks-subscription-{{ $row->id }}">
        	<td style="border-bottom: 1px solid #a4b7c1 !important;" colspan="2">
                <a href="javascript:;" class="view-menus-control btn btn-sm" data-id="{{$row->id}}">
                    <i class="fa fa-plus"></i>
                </a>
                 {{$row->week}} 
            </td>
            
            <td class="text-left">{{$row->status.(strtolower($row->status) == 'paid' ? '#'.$row->ins_invoice_id : '')}}</td>
            
            @if(auth()->user()->role != 'customer')
            <td colspan="2" class="text-center">
                @if(strtolower($row->status) !== 'pending')
                <div class="input-group">
                    <select class="form-control newstatus" name="newstatus" onchange="Customer.modifyStatus(this, {{$row->user_id}}, {{$row->id}}, $(this).parent().find('.newstatus option:selected').val())">
                        <option value="">Modify Status</option>

                        <option value="pending">Pending</option>
                        
                        @if(strtolower($row->status) !== 'paid')
                        <option value="paid">Paid</option>
                        @endif
                        
                        @if(strtolower($row->status) !== 'cancelled')
                        <option value="cancelled">Cancelled</option>
                        @endif
                        
                        @if(strtolower($row->status) !== 'paused')
                        <option value="paused">Paused</option>
                        @endif

                        @if(strtolower($row->status) !== 'refunded')
                        <option value="refunded">Refunded</option>
                        @endif
                        
                    </select>
                </div>
                @else
                &nbsp;
                @endif
            </td>
            @endif

        </tr>
        @endforeach
    </tbody>
</table>
