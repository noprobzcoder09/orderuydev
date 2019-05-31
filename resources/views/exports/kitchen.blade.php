<table>
    <thead>
    <tr>
        <th><b>Number</b></th>
        <th><b>SKU</b></th>
        <th><b>Meal Name</b></th>
        <th><b>QTY Paid</b></th>
        <th><b>QTY Billing Issue</b></th>
        <th><b>QTY Total</b></th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; $totalPaid = 0; $totalBillingIssue = 0; $qtyTotal = 0; $qtyGrandTotal = 0;?>
  	@if(!empty($meals))
      @foreach($meals as $row)
      <?php $totalPaid += $row->Qty_Paid; $totalBillingIssue += $row->Qty_Unpaid; $qtyTotal = $row->Qty_Paid + $row->Qty_Unpaid; $qtyGrandTotal += $qtyTotal; ?>
      <tr>
        <td>{{$i++}}</td>
        <td>{{$row->sku}}</td>
        <td>{{$row->name}}</td>
        <td style="text-align: center;">{{$row->Qty_Paid}}</td>
          <td style="text-align: center;">{{$row->Qty_Unpaid}}</td>
          <td style="text-align: center;">{{$qtyTotal}}</td>
      </tr>
      @endforeach
    @endif
    </tbody>
    <tfoot>
      <tr>
        <td>&nbsp;</td>
        <td>Total</td>
        <td>&nbsp;</td>
        <td>{{$totalPaid}}</td>
        <td>{{$totalBillingIssue}}</td>
        <td>{{$qtyGrandTotal}}</td>
      </tr>
    </tfoot>
</table>