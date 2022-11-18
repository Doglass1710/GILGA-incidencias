<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GILGAAdmin</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
              
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/inventario.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-editable-select.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css"/>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.js" defer></script>
    <script type="text/javascript" src="https://rawgit.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">

                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- GILGAAdmin 1.0 -->
                    <div class="imagen_logo" style="width: 100px;height: 50px;background-size: 100% 100%;"></div>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt fa-1x"></i>&nbsp;{{ __('Login') }}</a>
                            </li>
                            
                        @else
                            <li class="nav-item">                                    
                                <a class="nav-link" href="{{url('/')}}"><i class="fas fa-home fa-1x"></i>&nbsp;Inicio</a>                                
                            </li>

                            @if(\Auth::user()->id == '55' || \Auth::user()->id == '59')
                                <!--No necesita ver los modulos-->

                            @elseif(\Auth::user()->role == 'indigo' || \Auth::user()->nick=='auditor')

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-clipboard fa-1x"></i>&nbsp;Modulos <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('captura_incidencia') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Incidencia</a>
                                        <a class="dropdown-item" href="{{ url('reporte_incidencias') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Incidencias</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ url('listado_incidencias') }}"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Listado Incidencias</a>
                                        <a class="dropdown-item" href="{{ url('incidencias_cerradas') }}"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Listado Incidencias Cerradas</a>                                                                    
                                    </div>                                
                                    
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-clipboard fa-1x"></i>&nbsp;Modulos <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('captura_incidencia') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Incidencia</a>
                                    <a class="dropdown-item" href="{{ url('captura_incidencia_sistemas') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Incidencias de Sistemas</a>
                                    <a class="dropdown-item" href="{{ url('captura_medidas') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Medidas Diarias</a>
                                    <a class="dropdown-item" href="{{ url('captura_sisa') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Descarga SISA</a>
                                    <a class="dropdown-item" href="{{ url('captura_consolidado') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Consolidado</a>
                                    <a class="dropdown-item" href="{{ url('dispensarios_bitacora') }}"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Bitacora Dispensarios</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('listado_incidencias') }}"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Listado Incidencias</a>
                                    <a class="dropdown-item" href="{{ url('incidencias_cerradas') }}"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Listado Incidencias Cerradas</a>
                                    @if(\Auth::user()->role == 'admin')
                                    <a class="dropdown-item" href="{{ url('incidencias_cerradas_ant') }}"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Inc. Cerradas Anterior</a>
                                    <a class="dropdown-item" href="{{ url('us_grafico') }}"><i class="fas fa-chart-line"></i>&nbsp;Incidencias Pdts. por usuario</a>                             
                                    @endif
                                </div>                                
                                
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fa fa-folder"></i>&nbsp;Reportes <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                        <a class="dropdown-item" href="{{ url('reporte_incidencias') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Incidencias</a>
                                        <a class="dropdown-item" href="{{ url('reporte_compras') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Compras</a>
                                        <a class="dropdown-item" href="{{ url('reporte_medidas') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Medidas Diarias</a>
                                        <a class="dropdown-item" href="{{ url('reporte_anexo1') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Anexo</a>
                                        <a class="dropdown-item" href="{{ url('reporte_sisa') }}"><i class="far fa-file-pdf fa-1x"></i>&nbsp;Reporte SISA</a>
                                        <a class="dropdown-item" href="{{ url('reporte_dispensarios') }}"><i class="far fa-file-pdf fa-1x"></i>&nbsp;Reporte Dispensarios</a>
                                    </div>
                                </li>
                            @endif

                            <!-- ANEXO-->    
                            @if(\Auth::user()->role == 'indigo' || \Auth::user()->nick=='auditor')
                                <!--No necesita ver el inventario-->
                            @else                        
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fa fa-paperclip"></i>&nbsp;Anexo <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    
                                        <a class="dropdown-item" href="{{ url('anexo_ventas') }}"><i class="fa fa-dollar-sign"></i>&nbsp;Ventas</a>
                                        <a class="dropdown-item" href="{{ url('anexo_compras') }}"><i class="fa fa-shopping-bag"></i>&nbsp;Compras</a>
                                        <a class="dropdown-item" href="{{ url('anexo_diferencia') }}"><i class="fa fa-money-bill"></i>&nbsp;Diferencia</a>
                                        @if(\Auth::user()->id == '42' || \Auth::user()->id == '53')
                                        <a class="dropdown-item" href="{{ url('anexo_consulta') }}"><i class="fa fa-search"></i>&nbsp;Consultar</a>
                                        <a class="dropdown-item" href="{{ url('anexo_corregir') }}"><i class="fa fa-eraser "></i>&nbsp;Corregir Anexo</a>
                                        <a class="dropdown-item" href="{{ url('reporte_anexo_graficas') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Anexo Graficas</a>
                                        @endif
                                        @if(\Auth::user()->id == '55' || \Auth::user()->id == '59')                                    
                                        <a class="dropdown-item" href="{{ url('reporte_anexo1') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Anexo</a>
                                        <a class="dropdown-item" href="{{ url('anexo_inventario') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Plantilla Inventario</a>
                                        <a class="dropdown-item" href="{{ url('anexo_consulta') }}"><i class="fa fa-search"></i>&nbsp;Consultar</a>
                                        @endif
                                    </div>
                                </li>
                            @endif


                            @if(\Auth::user()->id == '55'  || \Auth::user()->id == '59' || \Auth::user()->role == 'indigo' || \Auth::user()->nick=='auditor')
                                <!--No necesita ver el inventario-->
                            @else
                                <!--INVENTARIO-->
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-clipboard fa-1x"></i>&nbsp;Inventario de Sistemas <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(\Auth::user()->id == '53' || \Auth::user()->id == '46' || \Auth::user()->id == '4')
                                        <a class="dropdown-item" href="http://inventario.grupogilga.com"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Ver Checklist</a>
                                    @endif
                                        <a class="dropdown-item" href="http://inventario.grupogilga.com/captura_inventario"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Equipo</a><!--inventario/create-->
                                        <!-- <a class="dropdown-item" href="http://inventario.grupogilga.com/importar_archivo"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Subir Archivo</a> -->
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="http://inventario.grupogilga.com/inventario"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Ver Inventario</a>
                                        <!-- <a class="dropdown-item" href="http://inventario.grupogilga.com/historial"><i class="fas fa-list-alt fa-1x"></i>&nbsp;Ver Historial</a> -->
                                        <a class="dropdown-item" href="http://inventario.grupogilga.com/reporte_inventario"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Generar Reporte</a>
                                    </div>
                                </li>

                            
                                    <!-- MENU ELSA-->
                                    @if(\Auth::user()->id == '53' || \Auth::user()->id == '46' || \Auth::user()->id == '4')
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                <i class="fa fa-motorcycle"></i>&nbsp;Bitácora <span class="caret"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            
                                                <a class="dropdown-item" href="{{ url('bitacora_altapiezas') }}"><i class="fa fa-pen-square"></i>&nbsp;Alta de piezas</a>
                                                <a class="dropdown-item" href="{{ url('bitacora_captura') }}"><i class="fa fa-save"></i>&nbsp;Captura de Bitácora</a>
                                                <a class="dropdown-item" href="{{ url('bitacora_consultas') }}"><i class="fa fa-search"></i>&nbsp;Consultas</a>
                                                <a class="dropdown-item" href="{{ url('reporte_bitacora') }}"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Reporte Bitácora</a>
                                            </div>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                <i class="fas fa-cog"></i>&nbsp;Ajustes <span class="caret"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ url('captura_refacciones') }}"><i class="fas fa-wrench"></i>&nbsp;Agregar Refacción</a>                                     
                                                @if(\Auth::user()->id == '53')
                                                <a class="dropdown-item" href="{{ url('usuario') }}"><i class="fas fa-user-friends"></i>&nbsp;Agregar Usuario</a>       
                                                <a class="dropdown-item" href="{{ url('ver_usuarios') }}"><i class="fas fa-user-friends"></i>&nbsp;Ver Usuarios</a>          
                                                <a class="dropdown-item" href="{{ url('abrirDias') }}"><i class="fas fa-reply"></i>&nbsp;Habilitar Días</a> 
                                                <a class="dropdown-item" href="{{ url('anexo_corregir') }}"><i class="fa fa-eraser "></i>&nbsp;Corregir Anexo</a>
                                                @endif
                                            </div>
                                        </li> 
                                    @endif
                                    
                                    <!--USUARIOS
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            <i class="fas fa-users"></i>&nbsp;Usuarios <span class="caret"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ url('us_grafico') }}"><i class="fas fa-chart-line"></i>&nbsp;Incidencias Pdts.</a>
                                        </div>
                                    </li>   -->   

                            @endif


                            <li class="nav-item dropdown">
                                                                
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user fa-1x"></i>&nbsp;{{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-1x"></i>&nbsp;{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>
