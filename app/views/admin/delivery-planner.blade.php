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
    <input type = 'hidden' id = 'current-vehicle' value = '{{ $vehicle_id }}'>
    
    <div class="row">
        @if (0)
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Map <small></small></h2>
                    
                    <div class="clearfix"></div>
                </div>
                
                <div id="map" style="width:100%;height:500px"></div>
            </div>
        </div>
        @endif
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Scheduled Delivery <small></small></h2>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div id="scheduled-delivery">
                        <div class="x_content" id = 'delivery-list-cntr'>
                            <table class="table table-bordered" id = 'delivery-list-cntr-tb'>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Company</th>
                                        <th>Volume</th>
                                        <th>Total Volume</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php $ctr = 0; ?>
                                    @foreach ($deliveries as $delivery_date)
                                    @for ($idx = 0; count($delivery_date) > $idx; $idx++)
                                    <tr>
                                        @if ($idx == 0)
                                        <td rowspan = "{{ count($delivery_date) }}">
                                            {{ date ('M d', strtotime($delivery_date[$idx]->delivery_date)) }}
                                        </td>
                                        @endif
                                        <td> {{ $delivery_date[$idx]->tank->company_name }} </td>
                                        <td style = 'text-align: right;'> @if (STR_EMPTY != $delivery_date[$idx]->volume_manual) {{ number_format($delivery_date[$idx]->volume_manual, 2) }} @endif </td>
                                        @if ($idx == 0)
                                        <td  style = 'text-align: right;'rowspan = "{{ count($delivery_date) }}"> @if (STR_EMPTY != $totals[$ctr]) {{ number_format($totals[$ctr], 2) }} @endif</td>
                                        @endif
                                    </tr>
                                    @endfor
                                    <?php $ctr++; ?>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-sm-6 col-xs-6">
                
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Customers near sump within 15 days <small></small></h2>
                            
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="x_content" id = 'historical-cntr'>

                            <table class="table table-bordered" id = 'historical-tb'>
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Est. Level</th>
                                        <th>Sump Level</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach ($update_data as $update)
                                    <tr>
                                        <td> 
                                            {{ $update['company_name'] }}
                                        </td>
                                        <td style = 'text-align: right;'> {{ number_format($update['level'], 2) }} </td>
                                        <td style = 'text-align: right;'> {{ number_format($update['sump_level'], 2) }} </td>
                                        <td> {{ date('d M', strtotime($update['date_reach'])) }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                
            </div>
        </div>
    </div>
    
    <div class = 'row'>
        <div class = 'col-md-3'>
            <div class="x_panel" style = 'display:inline-block; width: 260px;'>
                <div class = 'cat-header'><h3>Days</h3></div>
                <div style = 'display:inline; width: 220px; float: left; padding: 3px; margin: 10px;'>
                    <div style = 'display:inline; width: 200px;'>
                        <div style = 'text-align: right; width: 200px'> <b>Date reach Sump </b></div> 
                        <div style = 'text-align: right; width: 200px'> <b>Company Name </b></div>
                        <div style = 'text-align: right; width: 200px'> <b>Delivery Date </b></div>
                        <div style = 'text-align: right; width: 200px'> <b>Purchase No. </b></div>
                        <div style = 'text-align: right; width: 200px'> <b>Volume (Actual) </b></div>
                        <div style = 'text-align: right; width: 200px'> <b>Volume (Round down) </b></div>
                        <div style = 'text-align: right; width: 200px'> <b>Safe Fill </b></div>
                    </div>
                    
                    <div style = 'width: 200px'> <hr/> </div>
                    <div id = 'date-list'>
                        <div id = 'reload-date-list'>
                            <?php $ctr = 0; ?>
                            @for ($days = 0; 60 > $days; $days++)
                            @if ((0 == date('w', strtotime('+ '.$days.' Days'))) || (6 == date('w', strtotime('+ '.$days.' Days'))))
                                <?php $bg_color = '#EFEFEF'; ?>
                            @else
                                <?php $bg_color = '#FFFFFF'; ?>
                            @endif
                            <div style = 'background-color: {{ $bg_color }}; width: 200px; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;'>
                                <span>
                                    <a class = 'add-refill-days' href = "{{ URL::To('admin/add-refill-day/'.$vehicle_id) }}" data-refill-date = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}">
                                    @if (in_array(date('Y-m-d', strtotime('+ '.$days.' Days')), $refills))
                                    <i data-refill = '1' style = 'color:#337ab7;' class = 'fa fa-tachometer'></i>
                                    @else
                                    <i data-refill = '0' class = 'fa fa-tachometer'></i>
                                    @endif
                                    </a>
                                    
                                    <b>{{ date('D d M', strtotime('+ '.$days.' Days')) }} </b>
                                </span>
                                
                                <span style = 'float: right;'>
                                    @if (isset($deliveries[$ctr]))
                                        @if (date('Y-m-d', strtotime($deliveries[$ctr][0]->delivery_date)) == date('Y-m-d', strtotime('+ '.$days.' Days')))
                                            {{ number_format($totals[$ctr], 2) }} 
                                            <?php $ctr++; ?>
                                        @endif
                                    @endif
                                </span>
                                
                                
                                &nbsp;
                                
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class = 'col-md-9' style = 'white-space: nowrap; overflow-x: scroll; overflow-y: hidden;'>
            @foreach ($categories as $category)
            @if (isset($companies[$category->category_label]))
                <?php $cat_width = count($companies[$category->category_label]) * 180; ?>
                <div class="x_panel" style = 'display:inline-block; width: {{ $cat_width }}px;'>
                    <div class = 'cat-header'><h3>{{ $category->category_label }}</h3></div>
                    
                    @foreach ($companies[$category->category_label] as $company)
                    <div style = 'display:inline; width: 150px; float: left; border: solid 1px #13b36b; padding: 5px; margin: 5px;'>
                        <div id = "company-hdr-{{ $company['company']['tank_id'] }}">
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> {{ $company['forecast']['date_sump'] }}</div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> {{ $company['company']['company_name'] }}</div>
                            @if ($company['delivery'])
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> {{ date('M d', strtotime($company['delivery']['delivery_date'])) }}</div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> @if (STR_EMPTY != $company['delivery']['volume_manual']) {{ $company['delivery']['purchase_number'] }} @else --- @endif</div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px; text-align: right; padding-right: 12px;'> @if (STR_EMPTY != $company['delivery']['volume_manual']) {{ number_format($company['delivery']['volume_manual'], 2) }} @else --- @endif</div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px; text-align: right; padding-right: 12px;'> {{ number_format(((int)(($company['specifications']['safety_limit'] - $company['delivery']['remaining_litres']) / 100)) * 100, 2) }}</div>
                            @else
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> --- </div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> --- </div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> --- </div>
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px'> --- </div>
                            @endif
                            <div class = 'reload-hdr-{{ $company['company']['tank_id'] }}' style = 'width: 140px; text-align: right; padding-right: 12px;'> {{ number_format($company['specifications']['safety_limit'], 2) }}</div>
                        </div>
                        
                        <div style = 'width: 130px;'> <hr/> </div>
                        
                        <div class = 'delivery-slot-list'>
                            <?php $days = 0; ?>
                            @foreach ($company['forecast']['forecast_dump'] as $forecast)
                            
                            @if (date('Y-m-d', strtotime($company['forecast']['date_sump'])) == date('Y-m-d', strtotime($forecast['date'])))
                            <div class = 'delivery-slot' style = 'text-align: right; cursor: pointer; width: 130px; background-color: red; color: #FFFFFF; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                            @elseif (FALSE !== array_search(date('Y-m-d', strtotime($forecast['date'])), $company['deliveries']['delivery_dates']))
                            
                                @if ($vehicle_id == $company['deliveries']['vehicles'][array_search(date('Y-m-d', strtotime($forecast['date'])), $company['deliveries']['delivery_dates'])])
                                <div class = 'delivery-slot' style = 'text-align: right; cursor: pointer; width: 130px; background-color: green; color: #FFFFFF; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                                @else
                                <div class = 'delivery-slot' style = 'text-align: right; cursor: pointer; width: 130px; background-color: purple; color: #FFFFFF; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                                @endif
                            @else
                            <div class = 'delivery-slot' style = 'text-align: right; cursor: pointer; width: 130px; border-bottom: solid 1px #000000; padding: 4px; margin: 2px; height: 30px;' data-tank-id = "{{ $company['company']['tank_id'] }}" data-litres = "{{ $forecast['litres'] }}" data-delivery = "{{ date('Y-m-d', strtotime('+ '.$days.' Days')) }}" data-container="body" data-toggle="tooltip" data-placement="bottom" title="{{ $category->category_label }}: {{ $company['company']['company_name'] }}, Safety Fill: {{ number_format($company['specifications']['safety_limit'], 2) }}"> {{ number_format($forecast['litres'], 2) }} </div>
                            @endif
                            <?php $days++ ?>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            @endif
            @endforeach
        </div>
    </div>
    
    @include('modal.modal-create-delivery')
    @include('modal.modal-create-refill')
@stop

@section('javascript')
<script>
var MODAL_CNTR  = $('#modal-create-delivery');
//var UPD_BTN     = $('#modal-create-delivery');

var initPopForm = function(slot_obj)
{
    /** Unbinds previously binded event to the Modal Save Button    */
    //MODAL_CNTR.off('click', UPD_BTN);
    
    /** Display the Pop-up Form */
    loadPopForm(slot_obj);
}

/**------------------------------------------------------------------------
|	Shows the Modal Pop-up
|--------------------------------------------------------------------------
|	@param [in]		hdr_txt     --- Header to be displayed on the Title
|                   ajax_url    --- Url of the Form to be loaded via AJAX
|
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var loadPopForm = function(slot_obj)
{    
    /** Variable Definition    */
    var modal_body  = $('#delivery-form-body');
    var error_cntr  = $('#delivery-err');
    
    /** Configure the Modal Pop-up  */
    MODAL_CNTR.modal({
        keyboard: false,
    })
        /** Event before Modal Pops-up  */
        .on('show.bs.modal', function(e) {
            /** Loads the Form  */
            //modal_body.html('<img src = "'+BaseURL+'/images/preloader.gif" />');
            //modal_body.load(ajax_url, function(){
            var tank_id         = slot_obj.data('tank-id');
            var litres          = slot_obj.data('litres');
            var delivery_date   = slot_obj.data('delivery');
            var vehicle_id      = $('#current-vehicle').val();
            
            modal_body.hide();
            
            $.post(BaseURL+'/admin/get/scheduled-delivery', { 'vehicle_id': vehicle_id, 'tank_id': tank_id, 'litres': litres, 'delivery_date': delivery_date }, function(resp) { 
                $('.round_down').text(resp.round_down_txt);
                $('.remaining_litres').text(resp.litres_txt);
                $('.delivery_txt').text(resp.date_txt);
                $('.safety_fill_delivery').text(resp.safety_fill);
                $('.free_vol').text(resp.free_volume);
                
                $('input[name="purchase_number"]').val(resp.purchase_number);
                $('input[name="volume_manual"]').val(resp.volume_manual);
                $('input[name="remaining_litres"]').val(resp.remaining_litres);
                $('input[name="delivery_date"]').val(resp.delivery_date);
                $('input[name="tank_id"]').val(tank_id);
                $('input[name="vehicle_id"]').val(vehicle_id);
                $('input[name="vehicle_delivery_id"]').val(resp.vehicle_delivery_id);
                
                if (0 != resp.vehicle_delivery_id) {
                    $('.cancel-delivery').show();
                }
                else {
                    $('.cancel-delivery').hide();
                }
                
                modal_body.show();
                
            }, 'json');
        })
        /** Event before Modal closes  */
        .on('hide.bs.modal', function(e) {
            /** Reset the Form and bindings   */
            error_cntr.hide();
            modal_body.hide();
            $('.cancel-delivery').hide();
            MODAL_CNTR.unbind();
            //$(UPD_BTN).prop('disabled', false).text('Save');
        })
        .modal('show');
}

var loadRefillForm = function(slot_obj)
{    
    /** Variable Definition    */
    var modal_body  = $('#delivery-form-body');
    var error_cntr  = $('.error-msg');
    
    /** Configure the Modal Pop-up  */
    $('#modal-create-refill').modal({
        keyboard: false,
    })
        /** Event before Modal Pops-up  */
        .on('show.bs.modal', function(e) {
            /** Loads the Form  */
            var refill_date     = slot_obj.data('refill-date');
            
            $('input[name="refill_date"]').val(refill_date);
        })
        /** Event before Modal closes  */
        .on('hide.bs.modal', function(e) {
            /** Reset the Form and bindings   */

            $('input[name="batch_number"]').val('');
            $('input[name="refill_date"]').val('');
        })
        .modal('show');
}

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()
    var DATE_SLOT;
    
    $('.delivery-slot-list').on('click', '.delivery-slot', function() {
        DATE_SLOT = $(this);
        initPopForm($(this));
    });
    
    $('#create-delivery-form').submit(function() {
        var srv_message = '';
        var tank_id = $('input[name="tank_id"]').val();
        var upd_btn = $('.delivery-upd-btn');
        var error_cntr  = $('#delivery-err');
        
        upd_btn.prop('disabled', true).text('SAVING...');
        
        $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
            
            if (1 == srv_resp.sts) {
                $('#delivery-list-cntr').load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' #delivery-list-cntr-tb');
                $('#date-list').load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' #reload-date-list');
                $('#company-hdr-'+tank_id).load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' .reload-hdr-'+$('#current-vehicle').val());
                DATE_SLOT.css('background-color', 'green');
                DATE_SLOT.css('color', '#FFFFFF');
                $('#modal-create-delivery').modal('hide');
                
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
            
            upd_btn.prop('disabled', false).text('SAVE');
        },'json');       
        
        return false;
    });
    
    var date_clicked = null;
    
    $('body').on('click', '.add-refill-days', function() {
        date_clicked    = $(this);
        
        var refill_icon     = date_clicked.find('i');
        
        if ('1' == refill_icon.data('refill')) {
            $.post($(this).attr('href'), { 'batch_number': '0', 'refill_date': $(this).data('refill-date') }, function() {
                refill_icon.css('color', '#5A738E');
                refill_icon.data('refill', '0');
            });
        }
        else {
            loadRefillForm($(this));
        }
        
        return false;
    });
    
    $('#create-refill-form').submit(function() {
        var error_cntr  = $('#refill-form-err');
        
        $('.refill-upd-btn').prop('disabled', true).text('SAVING...');
        
        $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
            
            var refill_icon     = date_clicked.find('i');
            
            if (1 == srv_resp.sts) {
                refill_icon.css('color', '#337ab7');
                refill_icon.data('refill', '1');
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
            
            $('.refill-upd-btn').prop('disabled', false).text('SAVE');
        },'json');       
        
        return false;
    });
    
    $('.cancel-delivery').click(function() {
        var tank_id = $('input[name="tank_id"]').val();
        
        $.get(BaseURL+'/admin/cancel-delivery/'+$('input[name="vehicle_delivery_id"]').val(), function() {
            $('#modal-create-delivery').modal('hide');
            DATE_SLOT.css('background-color', '#FFFFFF');
            DATE_SLOT.css('color', '#73879C');
            
            $('#delivery-list-cntr').load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' #delivery-list-cntr-tb');
            $('#date-list').load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' #reload-date-list');
            $('#company-hdr-'+tank_id).load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' .reload-hdr-'+$('#current-vehicle').val());
        })
    });
    
});
</script>

@stop