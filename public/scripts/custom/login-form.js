/**--------------------------------------------------------------------------
| Customer Dashboard Scripts
|----------------------------------------------------------------------------
|	@file	cust-dashboard.js
|	@brief	Scripts for Customer Dashboard
|	@details DESCRIPTION: Highcharts dependent
|
|--------------------------------------------------------------------------*/

$(document).ready(function() {

function initLoginForm()
{
	bindloginForm();
}

function bindloginForm()
{
    var srv_message = '';
    var error_cntr  = $('.error-msg');
    var submit_btn  = $('#login-btn');
    var login_form  = $('#login');
    
    login_form.submit(function() {
        
        srv_message = '';
        submit_btn.prop('disabled', true).text('Verifying...');
        
        $.post(BaseURL+'/login', login_form.serialize(), function(srv_resp) {
            
            if (1 == srv_resp.sts) {
                window.location.replace(BaseURL+'/redirect-user');
            }
            else {
                /**	Formats the warnings received from the Server	*/
                for (idx = 0; idx < srv_resp.messages.length; idx++) {
                    srv_message += srv_resp.messages[idx] +'<br/>';
                }
                
                /**	Display the warning								*/
                error_cntr.show();
                error_cntr.html(srv_message);
                
                /** Resets the Save button when there are errors    */
                submit_btn.prop('disabled', false).text('Log In');
            }
        }, 'json')
        
        return false;
    });
}

initLoginForm();

});