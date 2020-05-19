<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Aula Virtual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    
  <link href="{{ asset('dist/sass/custom_vuetify.scss') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilo_adjunto.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
     <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
     
      <link rel="stylesheet" href="{{ asset('dist/css/getHTMLMediaElement.css') }}">
<!--script src="{{ asset('dist/js/RTCMultiConnection.js') }}"></script-->
<script src="http://localhost:9001/socket.io/socket.io.js"></script>
      
    

</head>
<body oncontextmenu="return false"  onmousedown="return false;" class="hold-transition skin-blue sidebar-mini" style="">
    <style>
    .v-application{
        font-family: 'Lato', sans-serif !important;
        font-weight: 400;
    }
     </style> 
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header" id="app" >

<v-app>
    <div class="app-header header-shadow" style="background-color:#bf0909;">
        <div class="app-header__logo">
            <div class="logo-src" >
                <div style="">
                <img class="logo-src" style="border-radius:10px;width:200px;height:90px;margin:auto;" src="{{asset('img/logo5.png')}}">
                </div>
                
            </div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
            <span>
                <button type="button"
                    class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>
        <div class="app-header__content" >
       

            <div class="app-header-right btn">
                                         


                <div class="header-btn-lg pr-0"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="widget-content p-0">
                        
                        <div class="widget-content-wrapper" style="color:white;">
                            <div class="widget-content-left">
                                <div class="btn-group"> 
                                        <span class="material-icons" style="font-size: 50px;">
                                            account_circle
                                        </span>
                                   
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                        class="dropdown-menu dropdown-menu-right">
                                        
                                        <a href="{{ url('logout') }}" onclick="event.preventDefault(); 
                                        document.getElementById('logout-form').submit();"
                                            class="dropdown-item"><v-icon left color="black">exit_to_app</v-icon>&nbsp;&nbsp;&nbsp;Cerrar Sesión</a>
                                        <form id="logout-form" action="{{ url('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading">
                                    {{ucwords(auth()->user()->usuario)}}
                                </div>
                                <div class="widget-subheading">
                                    {{ucwords(auth()->user()->perfil["nombre"])}}
                                </div>
                            </div>
                             
                            <i class="fa fa-angle-down ml-2 opacity-8"></i>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-main" >
        <div class="app-sidebar sidebar-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button"
                        class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="scrollbar-sidebar">
                <div class="app-sidebar__inner">
                    <ul class="vertical-nav-menu"  style="padding-top:40px;padding-left:0px !important;">
                        {!!$menu!!}
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="app-main__outer" style="width: 100%;">
            <div class="app-main__inner">
            <input type="hidden" id="usuario_id" data-nivel="{{auth()->user()->perfil['codigo']}}"   value="{{auth()->user()->id}}">
                <!--contentheader></contentheader-->
            
                <router-view></router-view>
                 
            </div>
            <!--div class="app-wrapper-footer">
                <div class="app-footer">
                    <div class="app-footer__inner" style="background-color: #00000008;">
                        <p style="margin: auto;padding: 10px;">Desarrollado por <a target="_blank" href="https://lgsoftwareweb.com/" >LGSoftware</a> </p>
                        
  <div class="rounded-social-buttons">
                    <a class="social-button facebook" href="https://www.facebook.com/lgsoftwareweb/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="social-button twitter" href="https://twitter.com/lgsoftwareweb" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a class="social-button linkedin" href="https://www.linkedin.com/company/lgsoftware" target="_blank"><i class="fab fa-linkedin"></i></a>
                   
                    <a class="social-button instagram" href="https://www.instagram.com/lgsoftware/" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
                    </div>
                </div>
            </div-->
        </div>

    </div>
</v-app>

</div> 


<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<!--script src="{{ asset('dist/js/pages/dashboard.js') }}"></script-->
<!--script src="{{ asset('dist/js/demo.js') }}"></script-->
    <script src="{{ asset('js/app.js')}}"></script> 

 
     <script src="{{ asset('dist/js/menu.js') }}"></script>
<script src="{{asset('dist/js/adapter.js')}}"></script>
<script src="{{ asset('dist/js/getHTMLMediaElement.js') }}"></script>
       

   
</body>
</html>
