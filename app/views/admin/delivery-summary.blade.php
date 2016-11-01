@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')
<style>
    .affix {
        top: 0px;
        background-color: #FFFFFF;
    }
</style>
@stop

@section('content')
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    {{ Form::open(array('url' => 'admin/send-summary-email', 'id' => 'summary-email-form', 'class' => 'form-inline')) }}
                    
                        <div class="x_title">
                            <div class = 'col-md-3'>
                                <h2>Scheduled Delivery <small></small></h2>
                            </div>
                            
                            <div class = 'col-md-9'>
                                <div class = 'pull-right'>
                                        <input type = 'hidden' id = 'vehicle_id' name = 'vehicle_id' value = '{{ $vehicle_id }}'>
                                        <input class = 'form-control' type = 'text' name = 'email_rcp' placeholder = 'Email Address' />
                                        <input id = 'send-summary-btn' class = 'btn-primary' type = 'submit' value = 'SEND' />
                                    
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                        </div>
                        
                        <div id="scheduled-delivery">
                            <div class="x_content" id = 'delivery-list-cntr'>
                                <table class="table table-bordered" id = 'delivery-list-cntr-tb'>
                                    <thead>
                                        <tr>
                                            <th> &nbsp; </th>
                                            <th>Customer</th>
                                            <th>Delivery Date</th>
                                            <th>Purchase Number</th>
                                            <th>Order Quantity</th>
                                            <th>Address</th>
                                            <th>Contact</th>
                                            <th>Delivery Times</th>
                                            <th>Comments</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach ($deliveries as $delivery)
                                        <tr>
                                            <td>
                                                @if(3 != $delivery->status)
                                                <input type = 'checkbox' name = 'delivery_ids[]' value = '{{ $delivery->vehicle_delivery_id }}'/>
                                                @else
                                                &nbsp;
                                                @endif
                                            </td>
                                            <td> {{ $delivery->company_info->company_name }} </td>
                                            <td> {{ date('d M', strtotime($delivery->delivery_date)) }}</td>
                                            <td> {{ $delivery->purchase_number }}</td>
                                            <td style = 'text-align: right;'> @if (STR_EMPTY != $delivery->volume_manual) {{ number_format($delivery->volume_manual, 2) }} @endif</td>
                                            <td> {{ $delivery->company_info->address.', '.$delivery->company_info->city.', '.$delivery->company_info->state }}</td>
                                            <td>
                                                <b>Contact Person: </b> {{ $delivery->company_info->contact_person }}<br/>
                                                <b>Contact No.: </b> {{ $delivery->company_info->contact_number }}<br/>
                                            </td>
                                            <td> {{ $delivery->company_info->delivery_time }} </td>
                                            <td>
                                                @if(3 != $delivery->status)
                                                <input type = 'text' class = 'delivery-remarks' data-delivery-id = '{{ $delivery->vehicle_delivery_id }}' name = 'delivery_remarks_{{ $delivery->vehicle_delivery_id }}' />
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
<script>
$(document).ready(function(){
    $('#summary-email-form').submit(function() {
        $('#send-summary-btn').prop('disabled', true).val('SENDING...');
        $.post($(this).attr('action'), $(this).serialize(), function() {
            $('#send-summary-btn').prop('disabled', false).val('SEND');
        });
        
        return false;
    });
    
    $('.delivery-remarks').change(function() {
        $.post(BaseURL+'/admin/save/delivery-remarks/'+$(this).data('delivery-id'), { 'remarks': $(this).val() }, function() {
            
        });
    });
    
});
</script>
@stop