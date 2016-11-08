/**--------------------------------------------------------------------------
| Delivery Planner Script
|----------------------------------------------------------------------------
|	@file	delivery-planner.js
|	@brief	Scripts for Delivery Planner
|	@details DESCRIPTION: NONE
|
|       [PROPERTIES]
|           MODAL_CNTR............................Delivery Ticket Modal Form
|           DATE_SLOT.............................Clicked Date slot
|           REFILL_SLOT...........................Clicked Refill slot
|
|       [FUNCTIONS]
|			initDeliveryTicketForm................Initialize Ticket Form
|			initDeliveryPlanner...................Initialization of script
|			loadTicketForm........................Shows the Modal Pop-up
|			loadRefillForm........................Shows the Modal Refill Form
|			chooseDateSlot........................Clicking a Date Slot
|			chooseRefillDate......................Clicking a delivery icon
|			submitDeliveryTicketForm..............Binds the Delivery Ticket form to the Submit Event
|			submitRefillForm......................Binds the Refill form to the Submit Event
|			cancelDeliveryScheduled...............Binds the cancel delivery button to the click Event
|			reloadElements........................Reloads the changed elements after AJAX calls
|
|--------------------------------------------------------------------------*/

var MODAL_CNTR  = $('#modal-create-delivery');
var DATE_SLOT;
var REFILL_SLOT;

/**------------------------------------------------------------------------
|	Initialize Ticket Form
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var initDeliveryTicketForm = function(slot_obj)
{
    /** Display the Pop-up Form */
    loadTicketForm(slot_obj);
}

/**------------------------------------------------------------------------
|	Shows the Modal Pop-up
|--------------------------------------------------------------------------
|	@param [in]		slot_obj    --- Clicked Delivery Slot
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var loadTicketForm = function(slot_obj)
{    
    /** Variable Definition    */
    var modal_body  = $('#delivery-form-body');
    var error_cntr  = $('#delivery-err');
    var cancel_btn  = $('.cancel-delivery');
    
    /** Configure the Modal Pop-up  */
    MODAL_CNTR.modal({
        keyboard: false,
    })
        /** Event before Modal Pops-up  */
        .on('show.bs.modal', function(e) {
            
            var tank_id         = slot_obj.data('tank-id');
            var litres          = slot_obj.data('litres');
            var delivery_date   = slot_obj.data('delivery');
            var vehicle_id      = $('#current-vehicle').val();
            
            /** Hides the content of the body while loading Data */
            modal_body.hide();
            
            /** Make an AJAX Call to get the data according to clicked slot */
            $.post(BaseURL+'/admin/get/scheduled-delivery', { 'vehicle_id': vehicle_id, 'tank_id': tank_id, 'litres': litres, 'delivery_date': delivery_date }, function(resp) { 
                /** Fields that can't be changed    */
                $('.round_down').text(resp.round_down_txt);
                $('.remaining_litres').text(resp.litres_txt);
                $('.delivery_txt').text(resp.date_txt);
                $('.safety_fill_delivery').text(resp.safety_fill);
                $('.free_vol').text(resp.free_volume);
                
                /** Input Fields    */
                $('input[name="purchase_number"]').val(resp.purchase_number);
                $('input[name="volume_manual"]').val(resp.volume_manual);
                $('input[name="remaining_litres"]').val(resp.remaining_litres);
                $('input[name="delivery_date"]').val(resp.delivery_date);
                $('input[name="tank_id"]').val(tank_id);
                $('input[name="vehicle_id"]').val(vehicle_id);
                $('input[name="vehicle_delivery_id"]').val(resp.vehicle_delivery_id);
                
                /** No delivery exist for the chosen date slot          */
                if (0 != resp.vehicle_delivery_id) {
                    cancel_btn.show();
                }
                /** There is already a delivery in the chosen date slot */
                else {
                    cancel_btn.hide();
                }
                
                /** Displays the form when all data has been loaded     */
                modal_body.show();
                
            }, 'json');
        })
        /** Event before Modal closes  */
        .on('hide.bs.modal', function(e) {
            /** Hide Errors and buttons */
            error_cntr.hide();
            cancel_btn.hide();
            
            /** Reset bindings   */
            MODAL_CNTR.unbind();
        })
        .modal('show');
}

/**------------------------------------------------------------------------
|	Shows the Modal Refill Form
|--------------------------------------------------------------------------
|	@param [in]		slot_obj    --- Clicked Delivery Slot
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var loadRefillForm = function(slot_obj)
{    
    /** Variable Definition    */
    var modal_form  = $('#modal-create-refill');
    var bnum_fld    = $('input[name="batch_number"]');
    var rdate_fld   = $('input[name="refill_date"]');
    
    /** Configure the Modal Pop-up  */
    modal_form.modal({
        keyboard: false,
    })
        /** Event before Modal Pops-up  */
        .on('show.bs.modal', function(e) {
            /** Loads data on the Field */
            rdate_fld.val(slot_obj.data('refill-date'));
        })
        /** Event before Modal closes  */
        .on('hide.bs.modal', function(e) {
            /** Reset the Form values   */
            bnum_fld.val('');
            rdate_fld.val('');
        })
        .modal('show');
}

/**------------------------------------------------------------------------
|	Clicking a Date Slot
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var chooseDateSlot = function()
{
    var delivery_list   = $('.delivery-slot-list');
    var delivery_slot   = '.delivery-slot';
    
    /** Event handler when a delivery slot is clicked   */
    delivery_list.on('click', delivery_slot, function() {
        /** Stores the information of the clicked date slot */
        DATE_SLOT = $(this);
        
        /** Initialize Modal Ticket Form    */
        initDeliveryTicketForm($(this));
    });
}

/**------------------------------------------------------------------------
|	Clicking a delivery icon
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var chooseRefillDate = function()
{
    var refill_links    = '.add-refill-days';
    var refill_icon     = null;
    
    $('body').on('click', refill_links, function() {
        REFILL_SLOT    = $(this);
        
        refill_icon     = REFILL_SLOT.find('i');
        
        /** Refill Exists in the chosen date    */
        if ('1' == refill_icon.data('refill')) {
            
            /** Remove delivery from chosen date    */
            $.post($(this).attr('href'), { 'batch_number': '0', 'refill_date': $(this).data('refill-date') }, function() {
                /** Reset date color to signify no delivery yet for the chosen date */
                refill_icon.css('color', '#5A738E');
                refill_icon.data('refill', '0');
            });
        }
        /** No refill exist yet */
        else {
            loadRefillForm($(this));
        }
        
        return false;
    });
}

/**------------------------------------------------------------------------
|	Binds the Delivery Ticket form to the Submit Event
|--------------------------------------------------------------------------
|	@param [in]		slot_obj    --- Clicked Delivery Slot
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var submitDeliveryTicketForm = function()
{
    /** Variable Definition */
    var delivery_form   = $('#create-delivery-form');
    
    /** Event handler when Delivery ticket form is submitted    */
    delivery_form.submit(function() {
        var srv_message = '';
        var tank_id     = $('input[name="tank_id"]').val();
        var upd_btn     = $('.delivery-upd-btn');
        var error_cntr  = $('#delivery-err');
        
        /** Initialize form elements after submitting    */
        upd_btn.prop('disabled', true).text('SAVING...');
        
        /** Verify and save data to database    */
        $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
            
            /** Data saved and verified     */
            if (1 == srv_resp.sts) {
                /** Reloads elements that will have data changed according to new delivery inserted */
                console.log(tank_id);reloadElements(tank_id);
                
                /** Change the color of the date slot to signify a delivery is scheduled    */
                DATE_SLOT.css('background-color', 'green');
                DATE_SLOT.css('color', '#FFFFFF');
                
                /** Close the Modal */
                MODAL_CNTR.modal('hide');
            }
            /** Data validation failed      */
            else {
                /**	Formats the warnings received from the Server	*/
                for (idx = 0; idx < srv_resp.messages.length; idx++) {
                    srv_message += srv_resp.messages[idx] +'<br/>';
                }
                
                /**	Display the warning								*/
                error_cntr.html(srv_message);
                error_cntr.show();
                
            }
            
            upd_btn.prop('disabled', false).text('SAVE');
        },'json');       
        
        return false;
    });
}

/**------------------------------------------------------------------------
|	Binds the Refill form to the Submit Event
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var submitRefillForm = function()
{
    var modal_form  = $('#modal-create-refill');
    var refill_form = $('#create-refill-form');
    var error_cntr  = $('#refill-form-err');
    var refill_icon = null;
    
    refill_form.submit(function() {
        
        /** Initialize form elements after form submit  */
        $('.refill-upd-btn').prop('disabled', true).text('SAVING...');
        
        /** Verify and save data into the database  */
        $.post($(this).attr('action'), $(this).serialize(), function(srv_resp) {
            
            refill_icon = REFILL_SLOT.find('i');
            
            /** Data verified and saved */
            if (1 == srv_resp.sts) {
                modal_form.modal('hide');
                
                refill_icon.css('color', '#337ab7');
                refill_icon.data('refill', '1');
            }
            /** Data validation failed  */
            else {
                /**	Formats the warnings received from the Server	*/
                for (idx = 0; idx < srv_resp.messages.length; idx++) {
                    srv_message += srv_resp.messages[idx] +'<br/>';
                }
                
                /**	Display the warning								*/
                error_cntr.html(srv_message);
                error_cntr.show();
            }
            
            $('.refill-upd-btn').prop('disabled', false).text('SAVE');
        },'json');       
        
        return false;
    });
}

/**------------------------------------------------------------------------
|	Binds the cancel delivery button to the click Event
|--------------------------------------------------------------------------
|	@param [in]		slot_obj    --- Clicked Delivery Slot
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var cancelDeliveryScheduled = function()
{
    var cancel_btn  = $('.cancel-delivery');
    var tank_id     = '';
    
    cancel_btn.click(function() {
        tank_id = $('input[name="tank_id"]').val();
        
        /** Removes data from the database  */
        $.get(BaseURL+'/admin/cancel-delivery/'+$('input[name="vehicle_delivery_id"]').val(), function() {
            /** Reloads the elements changed according to performed action  */
            reloadElements(tank_id);
            
            /** Closes the delivery ticket modal   */
            MODAL_CNTR.modal('hide');
            
            /** Change the background color of date slot to signify no scheduled delivery   */
            DATE_SLOT.css('background-color', '#FFFFFF');
            DATE_SLOT.css('color', '#73879C');
        });
    });
}

/**------------------------------------------------------------------------
|	Reloads the changed elements after AJAX calls
|--------------------------------------------------------------------------
|	@param [in]		tank_id --- Tank ID
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
var reloadElements = function(tank_id)
{
    /** Reloads the elements changed according to performed action  */
    $('#delivery-list-cntr').load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' #delivery-list-cntr-tb');
    $('#date-list').load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' #reload-date-list');
    $('#company-hdr-'+tank_id).load(BaseURL+'/admin/delivery-planner/'+$('#current-vehicle').val()+' .reload-hdr-'+tank_id);
}

$(document).ready(function() {

/**------------------------------------------------------------------------
|	Initialization of script
|--------------------------------------------------------------------------
|	@param [in]		NONE
|	@param [out] 	NONE
|	@return 		NONE
|------------------------------------------------------------------------*/
function initDeliveryPlanner()
{
    /** Displays the data of the Tank in a Bootstrap tooltip    */
    $('[data-toggle="tooltip"]').tooltip();
    
    /** Initialize Event Bindings   */
    chooseDateSlot();
    chooseRefillDate();
    submitDeliveryTicketForm();
    submitRefillForm();
    cancelDeliveryScheduled();
}

/** Initialization  */
initDeliveryPlanner();

});