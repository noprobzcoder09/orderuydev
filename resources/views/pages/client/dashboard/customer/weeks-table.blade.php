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
        <tr>
        	<td style="border-bottom: 1px solid #a4b7c1 !important;" colspan="2">
                <a href="javascript:;" class="view-menus-control btn btn-sm" data-id="{{$row->id}}">
                    <i class="fa fa-plus"></i>
                </a>
                 {{$row->week}} 
            </td>
            <td class="text-left"></td>
           
        </tr>
        @endforeach
    </tbody>
</table>
