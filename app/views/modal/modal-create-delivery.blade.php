<div class="modal" id="modal-create-delivery" role="dialog" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1> Delivery <span id = 'pop-hdr-lbl'> </span> </h1>
            </div>
            
            {{ Form::open(array('url' => 'admin/create-delivery', 'id' => 'create-delivery-form')) }}
            <div class="modal-body">
                <div id = 'delivery-form-body' style = 'display: hidden;'>
                    <div class = 'col-md-12'>
                        <div id = 'delivery-err' class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::text('purchase_number', '', array('class' => 'form-control', 'placeholder' => "Purchase Number")) }}
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::text('volume_manual', '', array('class' => 'form-control', 'placeholder' => "Volume (Actual)")) }}
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('', 'Volume (Round Down): ') }} <span class = 'round_down'> </span>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('', 'Remaining Litres: ') }} <span class = 'remaining_litres'> </span>
                    </div>
                    
                     <div class = 'form-group'>
                        {{ Form::label('', 'Safety Fill: ') }} <span class = 'safety_fill_delivery'> </span>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('', 'Delivery Date: ') }} <span class = 'delivery_txt'> </span>
                        {{ Form::hidden('delivery_date', '0', array('class' => 'form-control')) }}
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::label('', 'Remaining Litres on Vehicle: ') }} <span class = 'free_vol'> </span>
                    </div>
                    
                    {{ Form::hidden('remaining_litres', '0', array('class' => 'form-control')) }}
                    {{ Form::hidden('vehicle_id', '0', array('class' => 'form-control')) }}
                    {{ Form::hidden('tank_id', '0', array('class' => 'form-control')) }}
                    {{ Form::hidden('vehicle_delivery_id', '0', array('class' => 'form-control')) }}
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-danger cancel-delivery" style = 'display: none;'> CANCEL </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
                <button type="submit" class="btn btn-large btn-primary delivery-upd-btn"> SAVE </button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>