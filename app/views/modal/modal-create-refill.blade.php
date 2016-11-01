<div class="modal" id="modal-create-refill" role="dialog" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1> Refill <span id = 'pop-hdr-lbl'> </span> </h1>
            </div>
            
            {{ Form::open(array('url' => 'admin/add-refill-day/'.$vehicle_id, 'id' => 'create-refill-form')) }}
            <div class="modal-body">
                <div id = 'refill-form-body' style = 'display: hidden;'>
                    <div class = 'col-md-12'>
                        <div id = 'refill-form-err' class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                    </div>
                    
                    <div class = 'form-group'>
                        {{ Form::text('batch_number', '', array('class' => 'form-control', 'placeholder' => "Batch Number")) }}
                    </div>
                    
                    {{ Form::hidden('refill_date', '0') }}
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
                <button type="submit" class="btn btn-large btn-primary refill-upd-btn"> SAVE </button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>