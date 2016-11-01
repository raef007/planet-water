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
                    <h2>General Information <small></small></h2>
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                    <div class = 'col-md-12'>
                        <div class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                    </div>
                    {{ Form::open(array('url' => 'admin/submit/vehicle-information', 'id' => 'vehicle-information', 'class' => 'form-horizontal form-label-left')) }}
            
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('vehicle_label', $vehicle_info->vehicle_label, array('class' => 'form-control', 'placeholder' => "Vehicle Label")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('tank_capacity', $vehicle_info->tank_capacity, array('class' => 'form-control', 'placeholder' => "Vehicle Capacity")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::text('plate_number', $vehicle_info->plate_number, array('class' => 'form-control', 'placeholder' => "Plate Number")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group'>
                        <div class = 'col-md-12'>
                            {{ Form::label('vehicle_description', 'Description') }}
                            {{ Form::textarea('vehicle_description', $vehicle_info->vehicle_description, array(
                                'id'      => 'vehicle_description',
                                'rows'    => 5,
                                'class'	  => 'form-control',
                                ));
                            }}
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <h4>Location</h4>
                    
                    <div class = 'form-group form-inline'>
                        <div class = 'col-md-12'>
                            {{ Form::text('address', $vehicle_info->address, array('class' => 'form-control', 'placeholder' => "Address")) }}
                            {{ Form::text('city', $vehicle_info->city, array('class' => 'form-control', 'placeholder' => "City")) }}
                            {{ Form::text('state', $vehicle_info->state, array('class' => 'form-control', 'placeholder' => "State")) }}
                        </div>
                    </div>
                    
                    <div class = 'form-group form-inline'>
                        <div class = 'col-md-12'>
                            {{ Form::text('latitude', $vehicle_info->latitude, array('class' => 'form-control', 'placeholder' => "Latitude")) }}
                            {{ Form::text('longtitude', $vehicle_info->longtitude, array('class' => 'form-control', 'placeholder' => "Longtitude")) }}
                        </div>
                    </div>
                    
                    <div class = 'col-md-12'> <hr/></div>
                    
                    <div class = 'form-group'>
                        @if (isset($vehicle_info->vehicle_id))
                        <center>{{ Form::submit('Update Vehicle', array('class' => 'btn btn-large btn-primary', 'id' => 'vehicle-submit-btn')) }}</center>
                        @else
                        <center>{{ Form::submit('Add Vehicle', array('class' => 'btn btn-large btn-primary', 'id' => 'vehicle-submit-btn')) }}</center>
                        @endif
                    </div>
                    
                    {{ Form::hidden('vehicle_id', $vehicle_info->vehicle_id) }}
                    
                    {{ Form::close() }}
                </div>
            </div>
            
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Categories <small></small></h2>
                    <div class="clearfix"></div>
                </div>
                    
                <div class = 'col-md-12'> <hr/></div>
                    
                <div id = 'categories-cntr'>
                    <div class = 'col-md-12 reload-cat'>
                        <a id = 'pop-vcat-form' href = '#'> Add / Remove Category </a>
                    </div>
                    
                    <div class = 'col-md-12 reload-cat'> <br/></div>
                    
                    <div class = 'form-group form-inline reload-cat'>
                    {{ Form::open(array('url' => 'admin/submit/ccat-form', 'id' => 'ccat-form', 'class' => 'form-horizontal form-label-left')) }}
                        <div class = 'col-md-12'>
                            <div id = 'ccat-err' class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                        </div>
                        
                        <div class = 'col-md-12'>
                            <select name = 'company_name' class = 'form-control'>
                                <option value = ''> Choose Company </option>
                                @foreach ($uncat_data as $uncat_company)
                                <option value = '{{ $uncat_company->tank_id }}'> {{ $uncat_company->tank_info->company_name }} </option>
                                @endforeach
                            </select>
                            
                            <select name = 'category_name' class = 'form-control'>
                                <option value = ''> Choose Category </option>
                                @foreach ($vcat_data as $category)
                                <option value = '{{ $category->category_id }}'> {{ $category->category_label }} </option>
                                @endforeach
                            </select>
                            
                            {{ Form::submit('Add Company', array('class' => 'btn btn-large btn-primary', 'id' => 'ccat-submit-btn')) }}
                        </div>
                    {{ Form::close() }}
                    </div>
                    
                    <div class = 'col-md-12 reload-cat'>
                        @foreach ($vcat_data as $category)
                            <div class = 'col-md-3'>
                                <h4> {{ $category->category_label }} </h4>
                                
                                @if (isset($ccat_data[$category->category_label]))
                                    @foreach ($ccat_data[$category->category_label] as $company)
                                        <div>
                                            <a class = 'del-ccat-link' href = '{{ URL::To("admin/delete/ccat/".$company["ccat_id"]) }}'>
                                                <i class = 'fa fa-times'> </i>
                                            </a>
                                            {{ $company['company']['company_name'] }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('modal.modal-vehicle-categories')
@stop

@section('javascript')
<script>
    var loadVcatForm = function()
    {    
        /** Variable Definition    */
        
        /** Configure the Modal Pop-up  */
        $('#modal-create-vcat').modal({
            keyboard: false,
        })
            /** Event before Modal Pops-up  */
            .on('show.bs.modal', function(e) {
                /** Loads the Form  */
                
            })
            /** Event before Modal closes  */
            .on('hide.bs.modal', function(e) {
                /** Reset the Form and bindings   */
                $('input[name="category_label"]').val('');
            })
            .modal('show');
    }


    $(document).ready(function() {
        
        var srv_message = '';
        var error_cntr  = $('.error-msg');
        var submit_btn  = $('#vehicle-submit-btn');
        var form        = $('#vehicle-information');
        
        form.submit(function() {
            
            srv_message = '';
            submit_btn.prop('disabled', true).text('Saving...');
        
            $.post($(this).attr('action'), form.serialize(), function(srv_resp) {
                if (1 == srv_resp.sts) {
                    //$('#historical-cntr').load(BaseURL+' #historical-tb');
                    location.reload();
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
        
        $('body').on('click', '#pop-vcat-form', function() {
            loadVcatForm();
            
            return false;
        });
        
        $('body').on('submit', '#ccat-form', function() {
            var error_cntr  = $('#ccat-err');
            var srv_message = '';
            var current_url = window.location.href;
            
            $('#ccat-submit-btn').prop('disabled', true).text('ADDING...');
            
            $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
                
                /** Refresh the list    */
                if (1 == srv_resp.sts) {
                    $('#categories-cntr').load(current_url+' .reload-cat');
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
                
                $('#ccat-submit-btn').prop('disabled', false).text('Add Company');
                
            }, 'json');
            
            return false;
        });
        
        $('body').on('click', '.del-ccat-link', function() {
            var current_url = window.location.href;
            
            $.get($(this).attr('href'), function() {
                $('#categories-cntr').load(current_url+' .reload-cat');
            });
            
            return false;
        });
        
        $('body').on('submit', '#create-vcat-form', function() {
            var error_cntr  = $('#vcat-add-err');
            var srv_message = '';
            var current_url = window.location.href;
            
            $('#vcat-add-btn').prop('disabled', true).text('SAVING...');
            
            $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
                
                /** Refresh the list    */
                if (1 == srv_resp.sts) {
                    $('#vcat-list').load(current_url+' .reload-vcat-list');
                    $('#categories-cntr').load(current_url+' .reload-cat');
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
                
                $('input[name="category_label"]').val('');
                $('#vcat-add-btn').prop('disabled', false).text('SAVE');
                
            }, 'json');
            
            return false;
        });
        
        $('body').on('click', '.del-vcat-link', function() {
            var current_url = window.location.href;
            
            $.get($(this).attr('href'), function() {
                $('#vcat-list').load(current_url+' .reload-vcat-list');
                $('#categories-cntr').load(current_url+' .reload-cat');
            });
            
            return false;
        });
    });
</script>
@stop