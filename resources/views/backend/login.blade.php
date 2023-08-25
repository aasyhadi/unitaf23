@extends('backend.layouts.login')

@section('title', 'Login')

<style type="text/css">
    .login-page, .register-page {
      background:  #00a65a; 
      background-repeat:repeat-y;
      background-position: center center; 
      -webkit-backdrop-filter: blur(10px);
      backdrop-filter: blur(10px);
      max-width: 100%;
      max-height: 100%;
      padding: 20px 40px;
      height: 100%;
      width: 100%;
      background:linear-gradient(0deg, rgba(0, 86, 204, 0.6), rgba(0, 86, 100, 0.6)), url(img/bg-tpp.jpg);
      background-size:cover;
    }
    .card {
        background: whitesmoke ;
        padding: 5px 20px 5px 20px;
    }
</style>

@section('content')
    <div class="login-page">
        <div class="login_wrapper">
            <div class="animate form login_form">
                <div class="card">
                <section class="login_content">
                    <form method="post" id="formLogin" >
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        <!-- <h1>Login Form</h1> -->
                        <p class="text-left" style="background: #a2a3a0 !important; color: #fff !important; padding: 20px !important;">
                            <big>Aplikasi<br><small>UNIT USAHA ALFALAH<br> Tahun 2023</small></big>
                            <img height="30px" width="110" 
								style="margin-top: -90px; padding-left: 10px; padding-right: 10px; float: right;" 
                                class="img img-thumbnail"
								src="{{asset('img/logo_alfalah.png')}}"
							>
							
						</p>
                        
                        <div class="error-alert"></div>
                        <p class="text-center">Masukan Email dan Password !</p>
                        <div>
                            <input type="email" class="form-control" placeholder="Email" required="" name="email" />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" required="" name="password" />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-submit btn-primary ladda-button btn-block">Login</button> 
                        </div>
                        <br>
                        <div>
                            <p>&copy;2023 <b>Version</b> 1.0.0 <strong>All rights reserved.</strong></p>
                        </div>
                        </div>
                    </form>
                </section>
                </div>
            </div>
        </div>
    </div>
@endsection