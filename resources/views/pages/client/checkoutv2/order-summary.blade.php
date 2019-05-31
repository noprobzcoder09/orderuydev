<div class="row">
    <div class="col-md-12">
        <div class="width-50">
            <div class="newline">&nbsp;</div>
            <table class="" id="table-order-summary">
                <tbody>
                    <tr>
                        <td colspan="2"><h2 class="text-center"><span class="bold" style="color: #00692b">ORDER SUMMARY</span></h2></td>
                    </tr>
                    <tr>
                        <td>PRODUCT</td>
                        <td class="text-right">TOTAL</td>
                    </tr>
                    @if(!empty($order))
                        @foreach($order as $key => $row)
                        <tr>
                            <td class="text-indent">
                                <a href="javascript:;" onclick="Checkout.removeOrder(this, {{$key}})"><i class="fa fa-times text-danger"></i></a>
                                {{$row['name']}} x {{$row['quantity']}}
                            </td>
                            <td class="text-right">${{number_format($row['quantity'] * $row['price'], 2)}}/ Week</td>
                        </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td>SUBTOTAL</td>
                        <td class="text-right">${{number_format($worker->total(), 2)}}/ Week</td>
                    </tr>
                    @if(!empty($coupons) && count($order) > 0)
                    <tr>
                        <td>DISCOUNTS</td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach($coupons as $row)
                    <tr>
                        <td class="text-indent">
                            @php($str = '')
                            <a onclick="Coupon.removePrommo(this, '{{$row['code']}}')" href="javascript:;"><i class="fa fa-times text-danger"></i></a>
                            {!!$row['code'].' '.($row['recur'] != 1 ? '<small>one-time</small>' : '')!!}
                            @if(count($row['products']) > 0)
                            @foreach($row['products'] as $plan)
                                @foreach($order as $key => $p)
                                @if($key == $plan)
                                @php($str .= $order[$key]['name'].', ')
                                @endif
                                @endforeach
                            @endforeach 
                            @endif
                            @if(!empty($str))
                            <p style="padding-left: 15px;margin: 0;"><small><i>({{ __(substr($str, 0 , -2)) }})</i></small></p>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($row['isfixed'])
                                {{__('config.currency').__(number_format($row['discount'], 2))}}
                            @else
                                {{__($row['discount']).__('config.percent')}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @if(!empty($worker->getTotalDiscount()))
                    <tr>
                        <td>TOTAL DISCOUNTS</td>
                        <td class="text-right">${{number_format($worker->getTotalDiscount(), 2)}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL THIS WEEK</td>
                        <td class="text-right">${{number_format($worker->getTotalThisWeek(), 2)}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL AFTER THIS WEEK</td>
                        <td class="text-right">${{number_format($worker->getTotalAfterThisWeek(), 2)}}/ Week</td>
                    </tr>
                    @else
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right">${{number_format($worker->getGrandTotal(), 2)}}/ Week</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>