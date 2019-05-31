<table class="table table-hover table-align-middle mb-0 datatable">
    <thead>
        <tr>
            <th>Card Number</th>
            <th width='2%'>Default</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cardList as $row)
        <tr>
            <td>************{{$row['last4']}}</td>
            <td class="text-center"><input type="radio" onclick="BillingIssue.updateCardDefault(this, '{{$row['id']}}')" name="card-default" {{$row['default'] ? 'checked' : ''}}></td>
        </tr>
        @endforeach
    </tbody>
</table>