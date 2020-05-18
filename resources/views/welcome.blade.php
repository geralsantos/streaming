
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('app.name')}} | Entrar</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--link rel="icon" type="image/png" href="images/icons/favicon.ico"/-->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="{{asset('css/login/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/login/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('fonts/iconic/css/material-design-iconic-font.min.css')}}">
	<link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div id="app" >
	
        <v-app >
		
		
<div class="limiter">
		<div class="container-login100" style="background-image: url({{asset('assets/images/wallpaperlogi.jpg')}});">
		 
			<div class="wrap-login100" style="display:none">
				<form method="post" id="form-login" @key.enter="login" action="{{ url('login') }}" class="login100-form validate-form">
                    @csrf
                    <span class="">

                    <img src="{{asset('img/logo5.png')}}" width="400" alt="">
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
                    Plataforma <br> de Video-coferencias
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Ingrese un usuario">
						<input class="input100" v-model="usuario" required type="text" name="usuario" placeholder="Usuario">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Ingrese una contraseña">
						<input class="input100" v-model="contrasena" required type="password" name="contrasena" placeholder="Contraseña">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" :disabled="disableBtn" @click="login">
							Entrar
						</button>
					</div>
				</form>
			</div> 
			 
		 
		</div>
	</div>
		
		
	
	
        </v-app>
		
    </div>
    <!-- /.login-box -->
       <!-- /.Desarrollado por lgsoftwareweb.com  -->
    <script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{ asset('js/app.login.js')}}"></script>
    
</body>

</html>
