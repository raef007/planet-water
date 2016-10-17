<?php

class AccountLoggingController extends BaseController
{    
    /*-------------------------------------------------------------------------
	|	Displays the Login page
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		Login Page HTML
	|------------------------------------------------------------------------*/
    public function showLoginPage()
	{
        /*--------------------------------------------------------------------
        /*	User is not Logged in
		/*------------------------------------------------------------------*/
        if (Auth::check()) {
            return Redirect::to('/');
        }
        else {
            return View::make('login-register-form');
        }
		
	}
    
    /*-------------------------------------------------------------------------
	|	Submit the Login inputs
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		NONE
	|------------------------------------------------------------------------*/
    public function submitLoginCredentials()
	{
        /*--------------------------------------------------------------------
        /*	Variable Declaration
		/*------------------------------------------------------------------*/
        $messages               = array();
        $rules                  = array();
        $srv_resp               = array();
        
        $srv_resp['sts']        = STS_NG;
        $srv_resp['messages']   = array();
        
        $login_sts              = STS_NG;
        
        $input['email']     = Input::get('email');
        $input['password']  = Input::get('password');
        
        $messages = array(
		    'email.required'    => 'The Email Address field is required.',
		    'password.required' => 'The Password field is required.',
		);

		$rules = array(
	        'email'     => 'required|email',
	        'password'	=> 'required|min:6'
	    );
        
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $srv_resp['messages']	= $validator->messages()->all();
		}
        else {
            $login_sts  = Auth::attempt($input);
            
            if (STS_OK == $login_sts) {
                $srv_resp['sts']    = STS_OK;
            }
            else {
                $srv_resp['messages'][0]    = 'Invalid log-in credentials';
            }
        }
        
        return json_encode($srv_resp);
	}
    
    /*-------------------------------------------------------------------------
	|	Redirects the user according to user type
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		Redirection page according to user type
	|------------------------------------------------------------------------*/
    public function userTypeRedirection()
	{
        $redirect = Redirect::to('/login');
		if (1 == Auth::user()->user_type) {
            $redirect = Redirect::to('/dashboard');
        }
        else if (2 == Auth::user()->user_type) {
            $redirect = Redirect::to('admin/dashboard');
        }
        else {
            
        }
        
        return $redirect;
	}
    
    /*-------------------------------------------------------------------------
	|	Logs out the User
	|--------------------------------------------------------------------------
    |	@param [in] 	NONE
	|	@param [out] 	NONE
	|	@return 		Login Page HTML
	|------------------------------------------------------------------------*/
    public function userLogout()
	{
		Auth::logout();
		Session::flush();
        
        return Redirect::to('/login');
	}

}
