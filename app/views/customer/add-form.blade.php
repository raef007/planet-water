@extends('layouts.master')

@section('header')
	@parent
	<title>Tank Level Tracker</title>
@stop

@section('style')
<style>
    .error-msg-row {
        color: red;
        font-size: 11px;
        display: none;
    }
</style>
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
                        {{ Form::label('company_name', 'Company Name', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('company_name', $tank_info->company_name, array('class' => 'form-control')) }}
                            <span id = 'err-company_name' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('address', 'Address', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('address', $tank_info->address, array('class' => 'form-control', 'id' => 'address-gautocomp')) }}
                            <span id = 'err-address' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('city', 'Suburb', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('city', $tank_info->city, array('class' => 'form-control')) }}
                            <span id = 'err-city' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('state', 'State', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('state', $tank_info->state, array('class' => 'form-control')) }}
                            <span id = 'err-city' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('zipcode', 'Postcode', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('zipcode', $tank_info->zipcode, array('class' => 'form-control')) }}
                            <span id = 'err-zipcode' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('latitude', 'Latitude', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('latitude', $tank_info->latitude, array('class' => 'form-control')) }}
                            <span id = 'err-latitude' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('longtitude', 'Longtitude', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('longtitude', $tank_info->longtitude, array('class' => 'form-control')) }}
                            <span id = 'err-longtitude' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>

                    <h4> Business Information </h4>
                    
                    <div class = 'form-group'>
                        {{ Form::label('business_days', 'Business Days', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::select('business_days', 
                                array(
                                    '' => 'Choose here',
                                    1 => '5 Days a week',
                                    2 => '7 Days a week',
                                ),
                                $tank->business_days,
                                array('class' => 'form-control')) }}
                            <span id = 'err-business_days' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('delivery_time', 'Delivery Times', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('delivery_time', $tank_info->delivery_time, array('class' => 'form-control')) }}
                            <span id = 'err-delivery_time' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('delivery_notes', 'Delivery Notes', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('delivery_notes', $tank_info->delivery_notes, array('class' => 'form-control')) }}
                            <span id = 'err-delivery_notes' class = 'error-msg-row'> </span>
                        </div>
                    </div>

                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Contact Information</h4>
                    
                    <div class = 'form-group'>
                        {{ Form::label('contact_person', 'Contact Name', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('contact_person', $tank_info->contact_person, array('class' => 'form-control')) }}
                            <span id = 'err-contact_person' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('contact_number', 'Contact Number', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('contact_number', $tank_info->contact_number, array('class' => 'form-control')) }}
                            <span id = 'err-contact_number' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('contact_email', 'Email Address', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('contact_email', $tank_info->contact_email, array('class' => 'form-control')) }}
                            <span id = 'err-contact_email' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('password_plain', 'Password', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('password_plain', $tank_info->password_plain, array('class' => 'form-control')) }}
                            <span id = 'err-password_plain' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('', '', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            <input class = 'send-creds-tick' type = 'checkbox' name = 'send-creds-tick' value = '1' /> Send Credentials to customer Email
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Tank Information</h4>
                    
                    <?php
                        if (STR_EMPTY != $tank->maximum_capacity) $tank->maximum_capacity = number_format((float)$tank->maximum_capacity, 2);
                        if (STR_EMPTY != $tank->safety_limit) $tank->safety_limit = number_format((float)$tank->safety_limit, 2);
                        if (STR_EMPTY != $tank->sump_level) $tank->sump_level = number_format((float)$tank->sump_level, 2);
                    ?>
                    
                    <div class = 'form-group'>
                        {{ Form::label('maximum_capacity', 'Tank Size', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('maximum_capacity', $tank->maximum_capacity, array('class' => 'form-control')) }}
                            <span id = 'err-maximum_capacity' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('safety_limit', 'Safe Fill Limit', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('safety_limit', $tank->safety_limit, array('class' => 'form-control')) }}
                            <span id = 'err-safety_limit' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('sump_level', 'Sump Level', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('sump_level', $tank->sump_level, array('class' => 'form-control')) }}
                            <span id = 'err-sump_level' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Estimated Usage <small style = 'color: blue;'> (* Input known usage amount in only one of the fields below) </small> </h4>
                    
                    <?php
                        if (STR_EMPTY != $tank->estimated_usage) $tank->estimated_usage = number_format((float)$tank->estimated_usage, 0);
                        if (STR_EMPTY != $tank->monthly_usage) $tank->monthly_usage = number_format((float)$tank->monthly_usage, 0);
                        if (STR_EMPTY != $tank->annual_usage) $tank->annual_usage = number_format((float)$tank->annual_usage, 0);
                    ?>
                    
                    <div class = 'form-group'>
                        {{ Form::label('estimated_usage', 'Daily Usage', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('estimated_usage', $tank->estimated_usage, array('class' => 'form-control est-usage-fld')) }}
                            <span id = 'err-estimated_usage' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('monthly_usage', 'Monthly Usage', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('monthly_usage', $tank->monthly_usage, array('class' => 'form-control est-usage-fld')) }}
                            <span id = 'err-monthly_usage' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('annual_usage', 'Annual Usage', array('class' => 'control-label col-md-3')) }}
                        <div class = 'col-md-6'>
                            {{ Form::text('annual_usage', $tank->annual_usage, array('class' => 'form-control est-usage-fld')) }}
                            <span id = 'err-annual_usage' class = 'error-msg-row'> </span>
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <div class = 'form-group'>
                        <center>{{ Form::submit('Save Customer', array('class' => 'btn btn-large btn-primary', 'id' => 'customer-submit-btn')) }}</center>
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
        
        var submit_btn  = $('#customer-submit-btn');
        var form = $('#customer-information');
        
        form.submit(function() {
            
            $('.error-msg-row').hide();
            $('.has-error').removeClass('has-error');
            submit_btn.prop('disabled', true).val('Saving...');
        
            $.post($(this).attr('action'), form.serialize(), function(srv_resp) {
                if (1 == srv_resp.sts) {
                    //$('#historical-cntr').load(BaseURL+' #historical-tb');
                    window.location.replace(BaseURL+'/admin/customer/'+srv_resp.tank_id);
                }
                else {
                    var err_1 = '';
                    for (lbl_input in srv_resp.messages) {
                        
                        if ('' == err_1) {
                            err_1 = lbl_input;
                        }
                        
                        $('#err-'+lbl_input).html(srv_resp.messages[lbl_input][0]);
                        $('[name="'+lbl_input+'"]').parent().addClass('has-error');
                        
                        $('#err-'+lbl_input).show();
                    }
                    
                    $('html, body').animate({
                        scrollTop: $('[name="'+err_1+'"]').offset().top
                    }, 500);
                    
                    //$('body').scrollTo($('#err-company_name'));
                }
                
                /** Resets the Save button when there are errors    */
                submit_btn.prop('disabled', false).val('Save Customer');
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

<script>
var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};

function initAutocomplete()
{
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete((document.getElementById('address-gautocomp')), {types: ['geocode']});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

var fillInAddress = function(){
    var place   = autocomplete.getPlace();
    var street  = '';
    
    $('[name="address"]').val('');
    $('[name="city"]').val('');
    $('[name="state"]').val('');
    $('[name="zipcode"]').val('');
    $('[name="latitude"]').val('');
    $('[name="longtitude"]').val('');
    
    for (var i = 0; i < place.address_components.length; i++) {
        var address_type = place.address_components[i].types[0];
        
        if ('street_number' == address_type) {
            street += place.address_components[i]['long_name']+' ';
            $('[name="address"]').val(street);
        }
        
        if ('route' == address_type) {
            street += place.address_components[i]['long_name'];
            $('[name="address"]').val(street);
        }
        
        if ('locality' == address_type) {
            $('[name="city"]').val(place.address_components[i]['long_name']);
        }
        
        if ('administrative_area_level_1' == address_type) {
            $('[name="state"]').val(place.address_components[i]['short_name']);
        }
        
        if ('postal_code' == address_type) {
            $('[name="zipcode"]').val(place.address_components[i]['short_name']);
        }
    }
    
    $('[name="latitude"]').val(place.geometry.location.lat());
    $('[name="longtitude"]').val(place.geometry.location.lng());
}

function geolocate() {
  $('#address-gautocomp').focus(function() {
      console.log('focus');
    if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
  });
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHMhxMhkz0yza4CFnfRAUwidDEh7ZMLNQ&libraries=places&callback=initAutocomplete"></script>
@stop