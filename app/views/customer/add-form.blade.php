@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Customer Information <small></small></h2>
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                    <div class = 'col-md-12'>
                        <div class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                    </div>
                    {{ Form::open(array('url' => 'admin/submit/customer-information', 'id' => 'customer-information', 'class' => 'form-horizontal form-label-left')) }}
            
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('company_name', $tank_info->company_name, array('class' => 'form-control', 'placeholder' => "Customer Name")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::select('business_days', 
                                array(
                                    '' => 'Choose here',
                                    1 => '5 Days a week',
                                    2 => '7 Days a week',
                                ),
                                $tank->business_days,
                                array('class' => 'form-control', 'placeholder' => "Business Days")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('delivery_time', $tank_info->delivery_time, array('class' => 'form-control', 'placeholder' => "Delivery Time")) }}
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Location</h4>
                    
                    <div class = 'form-group form-inline'>
                        <div class = 'col-md-12'>
                            {{ Form::text('address', $tank_info->address, array('class' => 'form-control', 'placeholder' => "Address")) }}
                            {{ Form::text('city', $tank_info->city, array('class' => 'form-control', 'placeholder' => "City")) }}
                            {{ Form::text('state', $tank_info->state, array('class' => 'form-control', 'placeholder' => "State")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group form-inline'>
                        <div class = 'col-md-12'>
                            {{ Form::text('latitude', $tank_info->latitude, array('class' => 'form-control', 'placeholder' => "Latitude")) }}
                            {{ Form::text('longtitude', $tank_info->longtitude, array('class' => 'form-control', 'placeholder' => "Longtitude")) }}
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Contact Information</h4>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('contact_person', $tank_info->contact_person, array('class' => 'form-control', 'placeholder' => "Contact Person")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('contact_number', $tank_info->contact_number, array('class' => 'form-control', 'placeholder' => "Contact Number")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('contact_email', $tank_info->contact_email, array('class' => 'form-control', 'placeholder' => "Email Address")) }}
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Tank Information</h4>
                    
                    <div class = 'form-group form-inline'>
                        <div class = 'col-md-12'>
                            {{ Form::text('maximum_capacity', $tank->maximum_capacity, array('class' => 'form-control', 'placeholder' => "Tank Size")) }}
                            {{ Form::text('safety_limit', $tank->safety_limit, array('class' => 'form-control', 'placeholder' => "Safety Fill")) }}
                            {{ Form::text('sump_level', $tank->sump_level, array('class' => 'form-control', 'placeholder' => "Sump Level")) }}
                            {{ Form::text('estimated_usage', $tank->estimated_usage, array('class' => 'form-control', 'placeholder' => "Estimated Usage")) }}
                        </div>
                    </div>
                    
                    <h4>Estimated Usage</h4>
                    
                    <div class = 'form-group form-inline'>
                        <div class = 'col-md-12'>
                            {{ Form::text('estimated_usage', $tank->estimated_usage, array('class' => 'form-control est-usage-fld', 'placeholder' => "Daily Usage")) }}
                            {{ Form::text('monthly_usage', $tank->monthly_usage, array('class' => 'form-control est-usage-fld', 'placeholder' => "Monthly Usage")) }}
                            {{ Form::text('annual_usage', $tank->annual_usage, array('class' => 'form-control est-usage-fld', 'placeholder' => "Annual Usage")) }}
                            
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <div class = 'form-group'>
                        @if (isset($tank_id))
                        <center>{{ Form::submit('Update Customer', array('class' => 'btn btn-large btn-primary', 'id' => 'customer-submit-btn')) }}</center>
                        @else
                        <center>{{ Form::submit('Add Customer', array('class' => 'btn btn-large btn-primary', 'id' => 'customer-submit-btn')) }}</center>
                        @endif
                    </div>
                    
                    @if (isset($tank_id))
                    {{ Form::hidden('tank_id', $tank_id) }}
                    @else
                    {{ Form::hidden('tank_id', '0') }}
                    @endif
                    {{ Form::close() }}
                </div>
            </div>
            
        </div>
    </div>
    
@stop

@section('javascript')
<script>
    $(document).ready(function() {
        
        var srv_message = '';
        var error_cntr  = $('.error-msg');
        var submit_btn  = $('#customer-submit-btn');
        var form = $('#customer-information');
        
        form.submit(function() {
            
            srv_message = '';
            submit_btn.prop('disabled', true).text('Saving...');
        
            $.post($(this).attr('action'), form.serialize(), function(srv_resp) {
                if (1 == srv_resp.sts) {
                    //$('#historical-cntr').load(BaseURL+' #historical-tb');
                    window.location.replace(BaseURL+'/admin/customer/'+srv_resp.tank_id);
                }
                else {
                    /**	Formats the warnings received from the Server	*/
                    for (idx = 0; idx < srv_resp.messages.length; idx++) {
                        srv_message += srv_resp.messages[idx] +'<br/>';
                    }
                    
                    /**	Display the warning								*/
                    error_cntr.show();
                    error_cntr.html(srv_message);
                }
                
                /** Resets the Save button when there are errors    */
                submit_btn.prop('disabled', false).text('Submit');
            }, 'json');
            
            return false; 
        });
        
        $('.est-usage-fld').change(function() {
            var daily   = 0;
            var monthly = 0;
            var annual  = 0;
            
            if ('estimated_usage' == $(this).attr('name')) {
                daily   = parseInt($(this).val());
                monthly = parseInt($(this).val()) * 30;
                annual  = parseInt($(this).val()) * 365;
            }
            else if ('monthly_usage' == $(this).attr('name')) {
                daily   = parseInt($(this).val()) / 30;
                monthly = parseInt($(this).val());
                annual  = parseInt($(this).val()) * 12;
            }
            else if ('annual_usage' == $(this).attr('name')) {
                daily   = parseInt($(this).val()) / 365;
                monthly = parseInt($(this).val()) / 12;
                annual  = parseInt($(this).val());
            }
            
            $('input[name="estimated_usage"]').val(daily);
            $('input[name="monthly_usage"]').val(monthly);
            $('input[name="annual_usage"]').val(annual);
        });
    });
</script>
@stop