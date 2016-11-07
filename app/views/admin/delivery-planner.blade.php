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
<script src="{{ URL::asset('scripts/custom/delivery-planner.js') }}"></script>
@stop