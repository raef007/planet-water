<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tank Level Tracker | Login </title>

        <!--    BOOTSTRAP   -->
        <link rel="stylesheet" href="{{ URL::asset('styles/bootstrap.min.css') }}"  media="screen">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css"/> </link>
    
        <!--    MAIN STYLE  -->
        <link rel="stylesheet" href="{{ URL::asset('styles/custom.css') }}" media="screen"></link>
    </head>
    
    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content container">
                        {{ Form::open(array('url' => 'login', 'id' => 'login')) }}
                            <h1>Tank Level Tracker</h1>
                            <div class = 'col-md-12'>
                                <div class="alert alert-danger error-msg" role="alert" style = 'display: none;'></div>
                            </div>
                            
                            <div>
                                {{ Form::text('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Email')) }}
                            </div>
                            
                            <div>
                                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
                            </div>
                            
                            
                            <div class = 'col-md-12'>
                                {{ Form::submit('Log In', array('class' => 'btn btn-large btn-primary login-btn', 'id' => 'login-btn', 'style' => 'margin-left:40%')) }}
                                @if (0)
                                    <a class="reset_pass" href="#">Lost your password?</a>
                                @endif
                            </div>
                            
                            
                            <div class="clearfix"></div>

                            <div class="separator">
                                @if (0)
                                <p class="change_link">New to site?
                                    <a href="#signup" class="to_register"> Create Account </a>
                                </p>
                                
                                <div class="clearfix"></div>
                                <br />
                                @endif
                                <div>
                                    @if (0)
                                    <h1><i class="fa fa-paw"></i> Tank Level Tracker</h1>
                                    @endif
                                    <p>©Copyright&copy; {{ date('Y') }} Tank Level Tracker. All rights reserved</p>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </section>
                </div>
                
                <div id="register" class="animate form registration_form">
                    <section class="login_content">
                        <form>
                            <h1>Create Account</h1>
                            <div>
                                <input type="text" class="form-control" placeholder="Username" required="" />
                            </div>
                            
                            <div>
                                <input type="email" class="form-control" placeholder="Email" required="" />
                            </div>
                            
                            <div>
                                <input type="password" class="form-control" placeholder="Password" required="" />
                            </div>
                            
                            <div>
                                <a class="btn btn-default submit" href="index.html">Submit</a>
                            </div>

                            <div class="clearfix"></div>

                            <div class="separator">
                                <p class="change_link">Already a member ?
                                    <a href="#signin" class="to_register"> Log in </a>
                                </p>

                                <div class="clearfix"></div>
                                <br />

                                <div>
                                    <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                                    <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
        
        <script type="text/javascript"> var BaseURL = '{{ URL::to("/") }}'; </script>
        <script src="{{ URL::asset('scripts/1.11.2.jquery.min.js') }}"></script>
        <script src="{{ URL::asset('scripts/custom/login-form.js') }}"></script>
        
    </body>
</html>