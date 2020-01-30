
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}"
        type="text/css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
        <link href="{{asset('css/vuetify.min.css')}}" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div id="app" >
        <v-app  style="background-repeat: no-repeat;
background: url(https://i.pinimg.com/originals/0b/55/f0/0b55f029f2aca119b59bf67878ae9676.jpg) no-repeat center center fixed;
    background-size: auto;
background-size: 100% 100%;" >


                <div class="login-box" style="display:none" >
                    <div class="login-logo">
                        <a href="../../index2.html"><b>STREAMING</b></a>
                    </div>
                    <!-- /.login-logo -->
                    <div class="login-box-body">
                        <p class="login-box-msg">Ingresa para iniciar sesión</p>

                        <form method="post" id="form-login" action="{{ url('login') }}">
                        @csrf
                            <div class="form-group has-feedback">
                                <v-text-field  v-model="usuario" name="usuario" v-validate="'required|max:50'"
                                    :counter="50" :error-messages="errors.collect('usuario')" label="Usuario"
                                    data-vv-name="usuario" required></v-text-field>
                                <v-text-field v-model="contrasena"  v-validate="'required|max:50'"
                                    :counter="50" :error-messages="errors.collect('contrasena')" label="Contraseña"
                                    data-vv-name="contrasena" name="contrasena" :type="showPasswordLogin ? 'text' : 'password'"
                                    :append-icon="showPasswordLogin ? 'visibility' : 'visibility_off'" required
                                    @click:append="showPasswordLogin = !showPasswordLogin"></v-text-field>
                            </div>
                            <div class="row">

                                <!-- /.col -->
                                <div class="col-sm-12">
                                    <button type="button" :disabled="disableBtn" @click="login"
                                        class="btn btn-primary btn-block btn-flat">Entrar</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>


                    </div>
                    <!-- /.login-box-body -->
                </div>
        </v-app>
    </div>
    <!-- /.login-box -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.1.11/dist/vuetify.min.js"></script>
    <?php
if (App::environment('local')) {
?>
    <script src="{{ asset('js/app.login.js')}}"></script>
<?php
}else {
?>
    <script src="https://appjslaravel.000webhostapp.com/app.login.js" ></script>
<?php
}

?>
    <!-- jQuery 3 -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        });
    </script>
</body>

</html>
