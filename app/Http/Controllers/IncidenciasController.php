<?php

namespace App\Http\Controllers;

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;
use Image;

use App\Exports\UsersExport;
use App\Exports\IncidenciasExport;
use App\Exports\ComprasExport;
use App\Exports\MedidasExport;
use App\Exports\BitacoraExport;
use App\Exports\AnexoExport;
use App\Exports\Sheets\AnexoExport_General;
use App\Exports\Anexo_PlantillaInventario;
use App\Addition;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Incidencia;
use App\DetalleIncidencia;
use App\Compras;
use App\Compra_Detalle;
use App\Medidas_Diarias;
use App\Bitacora_catalogo;
use App\Bitacora_mtto;
use App\Bitacora_mtto_detalle;
use App\anexo_ventas_producto;
use App\anexo_ventas;
use App\anexo_compras;
use App\anexo_diferencia;
use App\sisa_captura;
use App\Dispensarios_bitacora;
use App\funciones;
use App\Inventario;
use App\incidencia_relacion;
use App\Refacciones;
use App\incidencias_comentarios;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\IncidenciaFormRequest;
use App\IncidenciaLog;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Us_estacion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
//use DB;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;


class IncidenciasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ReporteAnexoGraficas(Request $request)
    {
        $fecha1=$request->input("fecha_desde");
        $fecha2=$request->input("fecha_hasta");
        $estacionSeleccionada=$request->input("estacion");
        $sucursal=$request->input("aux_sucursal");

        if($fecha1==null)
        {
            $fecha1=Carbon::now();
            $fecha1=$fecha1->subDays(1); 
            $fecha1=$fecha1->format('Y-m-d');
            $fecha2=Carbon::now();
            $fecha2=$fecha2->subDays(1); 
            $fecha2=$fecha2->format('Y-m-d');//"2022-06-19";
        }
        if($estacionSeleccionada==null)
        {
            $estacionSeleccionada="6620";
            $sucursal="6620 - SERVIGILGA";
        }

        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        $datos=DB::table('anexo_ventas_producto as av')
        ->select(DB::raw('av.estacion, es.nombre_corto, round(sum(av.venta),2) as Venta'))
        ->join('estaciones as es','av.estacion','=','es.estacion')
        ->whereRaw('av.fecha_diavencido between "'. $fecha1 . '" and "'. $fecha2. '"') 
        ->groupBy('av.estacion')
        ->groupBy('es.nombre_corto')
        ->get();

        $producto = DB::select('call spGraficaVentasAnexo(?, ?)',array($fecha1, $fecha2));

        $porestacion=DB::table('anexo_ventas_producto')
        ->select(DB::raw('estacion,SUM(venta) as venta,producto as gasolina,
            case when producto="Magna" then "1b9e77" when producto="Premium" then "d50000" else "202124" end as color'))
        ->whereRaw('fecha_diavencido between "'. $fecha1 . '" and "'. $fecha2. '"')
        ->where('estacion','=',$estacionSeleccionada)
        ->groupby('estacion','gasolina','color')
        ->orderby('color')
        ->get();

        $total_venta=0;
        foreach($porestacion as $por){
            $total_venta= $total_venta + $por->venta;
        }

        //FORMATO DE FECHA
        setlocale(LC_ALL,"es_MX");
        $mesfecha1= strftime("%B", strtotime( $fecha1 ));
        $mesfecha2= strftime("%B", strtotime( $fecha2 ));

        if($mesfecha1==$mesfecha2){
            $fecha1 = strftime("%d", strtotime( $fecha1 ));
        }else{
            $fecha1 = strftime("%d de %B", strtotime( $fecha1 ));
        }
            $fecha2 = strftime("%d de %B", strtotime( $fecha2 ));
            $fecha1 = utf8_encode($fecha1);

        return view("incidencias.reporte_anexo_graficas",[   
            "estaciones"=>$estaciones,"fecha1"=>$fecha1,"fecha2"=>$fecha2,"sucursal"=>$sucursal,"total_venta"=>$total_venta,
            "estacion"=>$estacionSeleccionada,"datos"=>$datos,"producto"=>$producto,"porestacion"=>$porestacion]);
    }

    public function calcularPromedio(Request $request)
    {
        if ($request->ajax()) {
        
            $fecha1=$request->fecha1;
            $fecha2=$request->fecha2;
            $estacionSeleccionada=$request->estacion;

        //     if($fecha1==null || $fecha2==null )
        // {
        //     $fecha1="2022-06-19";
        //     $fecha2="2022-06-19";
        // }

            $porestacion=DB::table('anexo_ventas_producto')
            ->select(DB::raw('estacion,venta,producto as gasolina,fecha_diavencido,
                case when producto="Magna" then "1b9e77" when producto="Premium" then "d50000" else "202124" end as color'))
            ->whereRaw('fecha_diavencido between "'. $fecha1 . '" and "'. $fecha2. '"')
            ->where('estacion','=',$estacionSeleccionada)
            ->get();    
            
            //PROMEDIO
            $fecha1 = Carbon::parse($request->fecha1);
            $fecha2 = Carbon::parse($request->fecha2);
            $diasDiferencia = $fecha2->diffInDays($fecha1);
            $promedio_magna=0;
            $promedio_premium=0;
            $promedio_diesel=0;
            $total_magna=0;
            $total_premium=0;
            $total_diesel=0;
            foreach($porestacion as $pes)
            {
                if($pes->gasolina == "Magna")
                {
                    $total_magna = $total_magna + $pes->venta;
                }elseif($pes->gasolina == "Premium")
                {
                    $total_premium = $total_premium + $pes->venta;
                }else
                {   //diesel
                    $total_diesel = $total_diesel + $pes->venta;
                }            
            }
            //magna
            if($total_magna == 0 || $diasDiferencia==0 ){
                $promedio_magna=$total_magna;
            }else{
                $promedio_magna=Round($total_magna/($diasDiferencia + 1),2);
            }
            //premium
            if($total_premium == 0 || $diasDiferencia==0){
                $promedio_premium=$total_premium;
            }else{
                $promedio_premium=Round($total_premium/($diasDiferencia + 1),2);
            }
            //diesel
            if($total_diesel == 0 || $diasDiferencia==0){
                $promedio_diesel=$total_diesel;
            }else{
                $promedio_diesel=Round($total_diesel/($diasDiferencia + 1),2);
            }
            // $total_venta = $total_magna + $total_premium + $total_diesel;
            //END PROMEDIO
                      
            return response()->json([
                'promedio_magna' => $promedio_magna,
                'promedio_premium' => $promedio_premium,
                'promedio_diesel' => $promedio_diesel
            ]);
        }
    }

    public function consolidado(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        $msj="";

        return view("incidencias.captura_consolidado",["estaciones"=>$estaciones,"message" => $msj,"role"=>$role]);
    }
    
    public function insertar_comentarios(Request $request)
    {
        $user = \Auth::user(); 
        $com=New incidencias_comentarios;
        $com->id_usuario=$user->id;
        $com->id_incidencia=$request->input("id");
        $com->comentario=$request->input("txt_observaciones");
        $com->save();
        
        return Redirect::to('listado_incidencias')->with(['message' => 'Comentario agregado correctamente']);
    }

    public function leer_comentarios(Request $request)
    {        
        if ($request->ajax()){

            $comentarios=DB::table('incidencias_comentarios as c')
           // ->select("u.name,c.comentario,c.fecha_captura")
            ->join("users as u","c.id_usuario","=","u.id")
            ->where('c.id_incidencia','=',$request->id)
            ->get();

            return response()->json(['comentarios'=>$comentarios]);
        }
    }
    
    public function agregar_refaccion(Request $request)
    {       
        if($request->input("descripcion") == null)
        {
            return view('incidencias.captura_refacciones');
        }
        else{
            $ref= New Refacciones;
            $ref->descripcion=$request->input("descripcion");
            $ref->prioridad=$request->input("prioridad");
            $ref->id_area_atencion=$request->input("area_atencion");
            $ref->id_Catalogo=$request->input("catalogo");
            $ref->save();

            $msj="Refacción creada correctamente";
            return Redirect::to('captura_refacciones')->with(['message' => $msj]); 
        }        
    }

    public function ConsultarUsuario(Request $request)
    {
        $consulta=DB::table('users')->get();
        //->select(DB::raw('id,name,email'))
        return view('incidencias.consultar_usuario',["consulta"=>$consulta]);
    }

    public function RegistrarUsuario(Request $request)
    {
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        if($request->input("name") == null)
        {
            return view('incidencias.crear_usuario',["estaciones"=>$estaciones]);
        }else
        {
            if ($request->input("role")=="auditor"){
                $permiso="admin";
                $nick="auditor";
            }else{
                $permiso=$request->input("role");
                $nick="";
            }
            //guardar en tbl user
            $us=new User;
            $us->role=$permiso;
            $us->name=$request->input("name");
            $us->surname=$request->input("ap");
            $us->nick=$nick;
            $us->email=$request->input("email");
            $us->password= Hash::make($request->input("password"));
            $us->save();

            //obtener max(id) usuario (ultimo registrado)
            $max= DB::table('users')
            ->select(DB::raw('max(id) id'))
            ->get();

            foreach($max as $m){
                $id_permiso=$m->id;
            }
            
            $arreglo=['estacion'=>$request->input("estacion")];
            
            //guardar en tbl user
            $us=new Us_estacion;
            $us->id_usuario=$user->id;
            $us->id_usuario_permiso=$id_permiso;
            $us->estacion=$request->input("estacion");
            $us->save();

            $msj="Usuario Creado correctamente";
            return Redirect::to('usuario')->with(['message' => $msj]);              
        }      
    }

    public function HabilitarDias(Request $request)
    {
        if($request->input("dias") == null)
        {
            return view('incidencias.HabilitarDias');
        }else
        {
            $AbrirDias=DB::table('medidas_dias')
            ->update(['dias' => $request->input("dias")]);     
        }
        return Redirect::to('abrirDias')->with(['message' => 'Hecho']);
    }

    //Grafico x usuario
    public function usuarios_grafico()
    {
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $nombre=$user->name;

        if($id_usuario_permiso==4 || $id_usuario_permiso==53)
        {
            $incidencias = DB::table('incidencias as i')
                    ->select(DB::raw('u.name as estacion,"" as nombre_corto,count(*) as total'))
                    ->join('users as u','i.id_usuario','=','u.id')
                    ->where('i.estatus_incidencia','=','ABIERTA')
                    ->where('u.role','=','admin')
                    ->groupBy('u.name')
                    ->get();
            
            $areas_atencion = DB::table('incidencias as i')
            ->select(DB::raw('u.name as descripcion,count(*) as total'))
            ->join('users as u','i.id_usuario','=','u.id')
            ->where('i.estatus_incidencia','=','ABIERTA')
            ->where('u.role','=','admin')
            ->groupBy('u.name')
            ->get();
        }
        else{            
//select u.name,count(*) as total from `incidencias` as `i` inner join users as u on i.id_usuario=u.id 
//where `i`.`estatus_incidencia` = 'ABIERTA' and u.role="admin" group by u.name 
            $incidencias = DB::table('incidencias as i')
                    ->select(DB::raw('i.estacion,e.nombre_corto,count(*) as total'))
                    ->join('estaciones as e','i.estacion','=','e.estacion')
                    ->where('i.estatus_incidencia','=','ABIERTA')
                    ->where('i.id_usuario','=',$id_usuario_permiso)
                    ->groupBy('i.estacion')
                    ->groupBy('e.nombre_corto')
                    ->orderByRaw('cast(i.estacion as unsigned)')
                    ->get();
            
            $areas_atencion = DB::table('incidencias as i')
                    ->select(DB::raw('a.descripcion,count(*) as total'))
                    ->join('areas_atencion as a','i.id_area_atencion','=','a.id')                
                    ->where('i.estatus_incidencia','=','ABIERTA')
                    ->where('i.id_usuario','=',$id_usuario_permiso)
                    ->groupBy('a.descripcion')
                    ->get();
        }            
            return view('incidencias.us_grafico',["incidencias"=>$incidencias,"areas_atencion"=>$areas_atencion,"nombre"=>$nombre]);
    }

//visualizar orden de trabajo
    public function orden_trabajo($orden_ruta){
        $path = storage_path('app/orden_trabajo/'.$orden_ruta);
        return response()->file($path); 
    }
    
//visualizar bitacora
    public function dispensarios_pdf($estacion, $fecha1, $fecha2){

        $user = \Auth::user();
        $role = $user->role;
        $sucursal="_";

        if($estacion=='*')
        {
            $consulta=DB::table('dispensarios_bitacora as bit')
            ->select(DB::raw('bit.estacion,bit.fecha,bit.hora, 
            case 
                when bit.id_evento=1 then CONCAT(cat.descripcion,", Dispensario ",bit.dispensario,", Posición ",bit.posicion,", ",bit.producto)
                when bit.id_evento=2 then CONCAT(cat.descripcion,", ",bit.producto,", Folio de Acuse ",bit.folio) 
                else CONCAT(cat.descripcion,", Dispensario ",bit.dispensario) 
            END as descripcion,
                bit.factor_ajuste, bit.folio as acuse,concat_ws(" - ",e.estacion,e.nombre_corto) sucursal'))
            ->join('estaciones as e','e.estacion','=','bit.estacion')
            ->join('dispensarios_catalogo as cat','bit.id_descripcion','=','cat.id')
            ->whereRaw('bit.fecha between cast("'. $fecha1 .'" AS DATE) and CAST("'. $fecha2 .'" AS DATE)' )
            ->orderBy('bit.estacion')
            ->orderBy('bit.fecha')
            ->get();
            $sucursal="Sucursales";
        }else
        {
            $consulta=DB::table('dispensarios_bitacora as bit')
            ->select(DB::raw('bit.fecha,bit.hora, 
            case 
                when bit.id_evento=1 then CONCAT(cat.descripcion,", Dispensario ",bit.dispensario,", Posición ",bit.posicion,", ",bit.producto)
                when bit.id_evento=2 then CONCAT(cat.descripcion,", ",bit.producto,", Folio de Acuse ",bit.folio) 
                else CONCAT(cat.descripcion,", Dispensario ",bit.dispensario) 
            END as descripcion,
                bit.factor_ajuste,bit.folio as acuse,concat_ws(" - ",e.estacion,e.nombre_corto) sucursal'))
            ->join('estaciones as e','e.estacion','=','bit.estacion')
            ->join('dispensarios_catalogo as cat','bit.id_descripcion','=','cat.id')
            ->whereRaw('bit.fecha between cast("'. $fecha1 .'" AS DATE) and CAST("'. $fecha2 .'" AS DATE)' )
            ->where('bit.estacion','=',$estacion)        
            ->orderBy('bit.fecha')
            ->orderBy('bit.hora')
            ->get();
            foreach($consulta as $bit)
            {
                $sucursal=$bit->sucursal;
            }
        }
        
        $pdf= PDF::loadView('incidencias.reportes.bitacora_dispensarios',compact('consulta','sucursal','fecha1','fecha2')); 
        //return $pdf->download('bitacora.pdf');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('bitacora.pdf');  
    }

    public function dispensarios_rpt(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        if($request->input("estacion")=="*")
        {
            $consulta=DB::table('dispensarios_bitacora as bit')
            ->select(DB::raw('bit.estacion,bit.fecha,bit.hora, 
            case 
                when bit.id_evento=1 then CONCAT(cat.descripcion,", Dispensario ",bit.dispensario,", Posición ",bit.posicion,", ",bit.producto)
                when bit.id_evento=2 then CONCAT(cat.descripcion,", ",bit.producto,", Folio de Acuse ",bit.folio, " Nuevo precio: ",bit.factor_ajuste) 
                else CONCAT(cat.descripcion,", Dispensario ",bit.dispensario) 
            END as descripcion,
                bit.factor_ajuste, bit.folio as acuse, bit.orden_ruta,concat_ws(" - ",e.estacion,e.nombre_corto) sucursal'))
            ->join('estaciones as e','e.estacion','=','bit.estacion')
            ->join('dispensarios_catalogo as cat','bit.id_descripcion','=','cat.id')
            
            ->whereRaw('bit.fecha between cast("'. $request->input("fecha1") .'" AS DATE) and CAST("'. $request->input("fecha2").'" AS DATE)' )
            ->orderBy('bit.estacion')
            ->orderBy('bit.fecha')
            ->orderBy('bit.hora')
            ->orderBy('bit.id_evento')
            ->get();
        }else{
            $consulta=DB::table('dispensarios_bitacora as bit')
            ->select(DB::raw('bit.estacion,bit.fecha,bit.hora,
            case 
                when bit.id_evento=1 then CONCAT(cat.descripcion,", Dispensario ",bit.dispensario,", Posición ",bit.posicion,", ",bit.producto)
                when bit.id_evento=2 then CONCAT(cat.descripcion,", ",bit.producto,", Folio de Acuse ",bit.folio, " Nuevo precio: ",bit.factor_ajuste) 
                else CONCAT(cat.descripcion,", Dispensario ",bit.dispensario) 
            END as descripcion,
                bit.factor_ajuste, bit.folio as acuse, bit.orden_ruta,concat_ws(" - ",e.estacion,e.nombre_corto) sucursal'))
            ->join('estaciones as e','e.estacion','=','bit.estacion')
            ->join('dispensarios_catalogo as cat','bit.id_descripcion','=','cat.id')
            
            ->where('bit.estacion','=',$request->input("estacion"))        
            ->whereRaw('bit.fecha between cast("'. $request->input("fecha1") .'" AS DATE) and CAST("'. $request->input("fecha2").'" AS DATE)' )
            ->orderBy('bit.fecha')
            ->orderBy('bit.hora')
            ->orderBy('bit.id_evento')
            ->get();
        }

        if(count($consulta)==0){
            $msj="";
        }else{
            $msj="Mostrar";
        }
        $aux_sucursal=$request->aux_sucursal;
        $aux_estacion=$request->aux_estacion;
        $aux_fecha1=$request->input("fecha1");
        $aux_fecha2=$request->input("fecha2");

        return view("incidencias.reporte_dispensarios",
            ["estaciones"=>$estaciones,"consulta"=>$consulta,"msj"=>$msj,
            "aux_sucursal"=>$aux_sucursal,"aux_estacion"=>$aux_estacion,"role"=>$role,
            "aux_fecha1"=>$aux_fecha1, "aux_fecha2"=>$aux_fecha2 ]);
    }

    public function dispensarios_bit(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $nombre="";

        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();
        $eventos=DB::table('dispensarios_eventos')->get();    
        
        if($request->input("evento")==3 || $request->input("evento")==4){
            $factor_ajuste=" ";
        }else{
            $factor_ajuste=$request->input("valor");
        }

        if($request->input("posicion")==""){
            $posicion=0;
        }else{
            $posicion=$request->input("posicion");
        }

        if($request->input("producto")==""){
            $producto=" ";
        }else{
            $producto=$request->input("producto");
        }
        
        if($request->input("evento")==1){
            $descripcion=$request->input("descripcion");
            if($descripcion==1){
                $factor_ajuste="Escalamiento";
            }else{
                $factor_ajuste="Calibración a Volumen";
            }

        }else{
            $descripcion=$request->input("evento");
            $descripcion=($descripcion+1);
        }

        if($request->input("evento")==2){
            $folio_ordentrabajo=strtoupper($request->input("folio"));
            //$guardar=1;
            $dispensario=1;     //cambio  $dispensario=0;
        }else{
            $folio_ordentrabajo=$request->input("folio_orden");
            $dispensario=$request->input("dispensario");
    
        }
            //validar folio de orden de trabajo
                //si el evento es=1 tiene 2 descripciones pueden ser 2 ordenes de trabajo distintas el mismo dia
        if($request->input("evento")==1){     
            $orden = DB::table('dispensarios_bitacora')
            ->select(DB::raw('orden_folio,orden_ruta'))
            ->where('estacion', '=', $request->estacion)
            ->where('fecha', '=', $request->input("fecha"))    
            ->where('id_evento', '=', $request->input("evento")) 
            ->where('id_descripcion', '=', $descripcion)                 
            ->get();
        }else{
            $orden = DB::table('dispensarios_bitacora')
                ->select(DB::raw('orden_folio,orden_ruta'))
                ->where('estacion', '=', $request->estacion)
                ->where('fecha', '=', $request->input("fecha"))    
                ->where('id_evento', '=', $request->input("evento"))              
                ->get();
        }
            foreach($orden as $or)
            {
                $nombre=$or->orden_ruta;
                $guardar=1;
            }

            //subir orden de trabajo
            if($nombre==""){
                if($request->hasFile("orden_trabajo"))
                {
                    $file = $request->file("orden_trabajo")->getClientOriginalName();
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    //echo $filename . ' ' . $extension; 

                    //imagen
                    if($extension=="jpg" || $extension=="png" || $extension=="jpeg" || $extension=="JPG" || $extension=="PNG" || $extension=="JPEG")
                    {
                        $file=$request->file("orden_trabajo");            
                        $nombre = "orden_".$request->estacion."_".$request->input("fecha")."_".$request->input("evento")."_".$descripcion.".jpg";
                        $ruta = storage_path("app/orden_trabajo/".$nombre);
                        copy($file, $ruta);   
                        $guardar=1; 
                    }
                    //pdf
                    elseif($extension=="pdf" || $extension=="PDF")
                    {
                        $file=$request->file("orden_trabajo");            
                        $nombre = "orden_".$request->estacion."_".$request->input("fecha")."_".$request->input("evento")."_".$descripcion.".pdf";
                        $ruta = storage_path("app/orden_trabajo/".$nombre);
                        copy($file, $ruta);   
                        $guardar=1; 
                    }                    
                    else //otro
                    {
                        $msj="Formato de archivo no válido. Debe seleccionar un tipo de archivo con extensión: .jpg, .png, .jpeg, .pdf";
                        $guardar=0;
                    }  
                }
                else{
                    $msj="No se adjunto orden de trabajo";
                    $guardar=0;
                }
            }
                
        
        if($guardar==1)
        {
            $disp_bit=new Dispensarios_bitacora;
            $disp_bit->estacion=$request->estacion;
            $disp_bit->id_evento=$request->input("evento");
            $disp_bit->folio=strtoupper($request->input("folio"));
            $disp_bit->fecha=$request->input("fecha");
            $disp_bit->hora=$request->input("hora");
            $disp_bit->id_descripcion=$descripcion;
            $disp_bit->factor_ajuste=$factor_ajuste;
            $disp_bit->dispensario=$dispensario;
            $disp_bit->posicion=$posicion;
            $disp_bit->producto=$producto;
            $disp_bit->orden_folio=$folio_ordentrabajo;
            $disp_bit->orden_ruta=$nombre;
            $disp_bit->save();
        $msj="Registro guardado correctamente";
        }        

        return view("incidencias.dispensarios_bitacora",["estaciones"=>$estaciones,"eventos"=>$eventos,"message" => $msj,"role"=>$role]);
    }

    public function dispensarios()
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        $eventos=DB::table('dispensarios_eventos')->get(); 

        $msj="";

        return view("incidencias.dispensarios_bitacora",[
            "estaciones"=>$estaciones,
            "eventos"=>$eventos,
            "message" => $msj,"role"=>$role]);
    }

    public function obtener_descripcion(Request $request)
    {
        if ($request->ajax()) {
            $catalogo = DB::table('dispensarios_catalogo')
                ->where('id_evento', '=', $request->id)
                ->get();
            return response()->json($catalogo);
        }
    }
    public function obtener_factor(Request $request)
    {
        if ($request->ajax()) {
            $factor = DB::table('dispensarios_eventos')
                ->select(DB::raw('id,evento,factor_ajuste'))
                ->where('id', '=', $request->id)
                ->get();
            return response()->json($factor);
        }
    }
    public function obtener_dispensarios(Request $request)
    {
        if ($request->ajax()) {
            $factor = DB::table('vw_dispensarios_posiciones')
            ->select(DB::raw('estacion,id_equipo'))
            ->where('estacion', '=', $request->estacion)
            ->groupBy('estacion')
            ->groupBy('id_equipo')
            ->get();
            return response()->json($factor);
        }
    }
    public function obtener_ordentrabajo(Request $request)
    {
        if ($request->ajax()) {            

            if($request->input("fecha")==""){
                $fecha=" ";
            }else{
                $fecha=$request->input("fecha");
            } 
            if($request->id==""){
                $evento="0";
            }else{
                $evento=$request->id;
            } 

                $orden = DB::table('dispensarios_bitacora')
                ->select(DB::raw('orden_folio'))
                ->where('estacion', '=', $request->estacion)
                ->where('id_evento','=', $evento)
                ->where('fecha','=', $fecha)
                ->get();
                      
            return response()->json($orden);
        }
    }
    
    public function firmados_sisa(Request $request)
    {
        $estaciones = DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('medidas_tanques_catalogo as tc','tc.estacion','=','e.estacion')
        ->where('tc.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        // $estacion_plus=DB::table('estaciones')
        // ->select(DB::raw('estacion,concat_ws(" - ",estacion,nombre_corto) as sucursal'))
        // ->where('estacion','=','12923');

        //$estaciones= $estaciones->union($estacion_plus)->get();

        if($request->input("fecha")=="" ||  $request->input("fecha2")=="")
        {
            $consulta_sisa=DB::table('sisa_captura')
            ->select(DB::raw('estacion,factura,fecha,producto,archivopdf'))
            ->whereRaw('fecha=curdate()')
            ->where('archivopdf','<>','no_aplica')
            ->get();
            $fecha= Carbon::now()->format('Y-m-d');
        }
        else{
            if($request->input("estacion")=="*")
            {
                $consulta_sisa=DB::table('sisa_captura')
                ->select(DB::raw('estacion,factura,fecha,producto,archivopdf'))
                ->whereRaw('fecha between cast("'. $request->input("fecha") .'" AS DATE) and CAST("'. $request->input("fecha2").'" AS DATE)' )
                ->where('archivopdf','<>','no_aplica')
                ->get();
            }else
            {
                $consulta_sisa=DB::table('sisa_captura')
                ->select(DB::raw('estacion,factura,fecha,producto,archivopdf'))
                ->whereRaw('fecha between cast("'. $request->input("fecha") .'" AS DATE) and CAST("'. $request->input("fecha2").'" AS DATE)' )
                ->where('archivopdf','<>','no_aplica')
                ->where('estacion','=',$request->input("estacion"))
                ->get();
            }
            $fecha= $request->input("fecha2");
        }

        if(count($consulta_sisa)==0){
            $msj="";
        }else{
            $msj="Mostrar";
        }
        $aux_sucursal=$request->aux_sucursal;

        return view("incidencias.reporte_sisaFirmados",
        ["consulta_sisa"=>$consulta_sisa,"fecha"=>$fecha,"msj"=>$msj,"aux_sucursal"=>$aux_sucursal,"estaciones"=>$estaciones]);
    }

    public function firmados_visualizar($pdf)
    {
        $path = storage_path('app/pdf/'.$pdf);
        return response()->file($path); 
    }

    public function captura_sisa()
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social,g.nombre'))
        ->join('medidas_tanques_catalogo as tc','tc.estacion','=','ue.estacion')
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->join('gerentes as g','g.estacion','=','e.estacion')
        ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        ->where('tc.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
        ->where('g.estatus','=','ACTIVO')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        // $estacion_plus=DB::table('usuarios_estaciones as ue')
        // ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social,g.nombre'))
        // ->join('estaciones as e','e.estacion','=','ue.estacion')
        // ->join('companias as c','c.id','=','e.id_compania')
        // ->join('gerentes as g','g.estacion','=','e.estacion')
        // ->where('e.estacion','=','12923')
        // ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        // ->where('g.estatus','=','ACTIVO');

        //$estaciones= $estaciones->union($estacion_plus)->get();

        $msj="";

        return view("incidencias.captura_sisa",["estaciones"=>$estaciones,"message" => $msj,"role"=>$role]);
    }

    public function captura_sisa_guardar(Request $request){    
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estacion=$request->estacion;  

        $sisa=new sisa_captura;
        $sisa->usuario=$id_usuario_permiso;
        $sisa->estacion=$estacion;
        $sisa->gerente=$request->input("txt_gerente");
        $sisa->operador=$request->input("txt_operador");
        $sisa->fecha=$request->input("fecha");
        $sisa->hora=$request->input("hora");
        $sisa->producto=$request->input("producto");
        $sisa->volumen=$request->input("txt_volumen");
        $sisa->placas=$request->input("txt_placas");
        $sisa->remision=$request->input("txt_remision");
        $sisa->factura=$request->input("txt_factura");
        $sisa->volumen_inicial=$request->input("txt_vinicial");
        $sisa->volumen_final=$request->input("txt_vfinal");
        $sisa->aumento=$request->input("txt_aumento");
        $sisa->venta=$request->input("txt_venta");
        $sisa->cubetas=$request->input("txt_cubetas");
        $sisa->observaciones=$request->input("txt_observ");

        //  //Subir la imagen
        //  $image_path = $request->file('foto_sisa');
        //  if ($image_path) {
        //      $image_path_name = time() . $image_path->getClientOriginalName();
        //      $ruta= storage_path('app/img_sisa/'.$image_path_name);
        //      Image::make($image_path->getRealPath())
        //         ->resize(1280, null, function ($constraint) {
        //          $constraint->aspectRatio();
        //          $constraint->upsize();
        //         })->save($ruta,60);
        //      $sisa->foto_sisa = $image_path_name;             
        //  }
        $funcion= new funciones();
        $image_path = $request->file('foto_sisa');
        $sisa->foto_sisa=$funcion->comprime_img('foto_sisa',$image_path);
        $image_path = $request->file('foto_domo');
        $sisa->foto_domo=$funcion->comprime_img("foto_domo",$image_path);
        $image_path = $request->file('foto_valvula');
        $sisa->foto_valvulas=$funcion->comprime_img("foto_valvula",$image_path);
        $image_path = $request->file('foto_remision');
        $sisa->foto_remision=$funcion->comprime_img("foto_remision",$image_path);
        $image_path = $request->file('foto_tanque');
        $sisa->foto_tanque=$funcion->comprime_img("foto_tanque",$image_path);
        $image_path = $request->file('foto_tira');
        $sisa->foto_tira=$funcion->comprime_img("foto_tira",$image_path);
        $image_path = $request->file('foto_cubetas');
        $sisa->foto_cubetas=$funcion->comprime_img("foto_cubetas",$image_path);
        $image_path = $request->file('foto_venta');
        $sisa->foto_venta=$funcion->comprime_img("foto_venta",$image_path);
        $image_path = $request->file('foto_relleno');
        $sisa->foto_relleno=$funcion->comprime_img("foto_relleno",$image_path);
        $sisa->archivopdf="no_aplica";
        $sisa->save();
        $msj="Registro guardado correctamente";

        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social,g.nombre'))
        ->join('medidas_tanques_catalogo as tc','tc.estacion','=','ue.estacion')
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->join('gerentes as g','g.estacion','=','e.estacion')
        ->where('id_usuario_permiso', '=', $id_usuario_permiso)
        ->where('tc.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
        ->where('g.estatus','=','ACTIVO')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        // $estacion_plus=DB::table('usuarios_estaciones as ue')
        // ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social,g.nombre'))
        // ->join('estaciones as e','e.estacion','=','ue.estacion')
        // ->join('companias as c','c.id','=','e.id_compania')
        // ->join('gerentes as g','g.estacion','=','e.estacion')
        // ->where('e.estacion','=','12923')
        // ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        // ->where('g.estatus','=','ACTIVO');

        //$estaciones= $estaciones->union($estacion_plus)->get();

        if ($role=="admin"){
            $razon_social="";
        }else{
            foreach($estaciones as $e){
                $razon_social=$e->razon_social;
            }
        }

        return view("incidencias.captura_sisa",["estaciones"=>$estaciones,"message" => $msj,"razon_social"=>$razon_social,"role"=>$role]);
    }

    public function reporte_sisa(Request $request){

        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $role = $user->role;

        $estaciones = DB::table('usuarios_estaciones as ue')
        ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('medidas_tanques_catalogo as tc','tc.estacion','=','ue.estacion')
        ->join('estaciones as e','e.estacion','=','ue.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('id_usuario_permiso', '=', $id_usuario_permiso)
        ->where('tc.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();
        
        // $estacion_plus=DB::table('usuarios_estaciones as ue')
        // ->select(DB::raw('ue.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        // ->join('estaciones as e','e.estacion','=','ue.estacion')
        // ->join('companias as c','c.id','=','e.id_compania')
        // ->join('gerentes as g','g.estacion','=','e.estacion')
        // ->where('e.estacion','=','12923')
        // ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
        // ->where('g.estatus','=','ACTIVO');

        //$estaciones= $estaciones->union($estacion_plus)->get();

        if($request->input("fecha")=="" || $request->input("fecha2")==""){

            $consulta=DB::table('sisa_captura as s')
            ->select(DB::raw('s.id,s.estacion,s.factura,s.producto,s.fecha'))
            ->join('usuarios_estaciones as ue','ue.estacion','=','s.estacion')
            ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
            ->whereRaw('s.fecha=curdate()')
            ->get();
            $fecha= Carbon::now()->format('Y-m-d');
        }
        else{

            if($request->input("estacion")=="*")
            {
                $consulta=DB::table('sisa_captura as s')
                ->select(DB::raw('s.id,s.estacion,s.factura,s.producto,s.fecha'))
                ->join('usuarios_estaciones as ue','ue.estacion','=','s.estacion')
                ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
                ->whereRaw('s.fecha between cast("'. $request->input("fecha") .'" AS DATE) and CAST("'. $request->input("fecha2").'" AS DATE)' )
                ->orderBy('s.fecha')
                ->get();
            }else
            {
                $consulta=DB::table('sisa_captura as s')
                ->select(DB::raw('s.id,s.estacion,s.factura,s.producto,s.fecha'))
                ->join('usuarios_estaciones as ue','ue.estacion','=','s.estacion')
                ->where('ue.id_usuario_permiso', '=', $id_usuario_permiso)
                ->whereRaw('s.fecha between cast("'. $request->input("fecha") .'" AS DATE) and CAST("'. $request->input("fecha2").'" AS DATE)' )
                ->where('s.estacion','=',$request->input("estacion"))
                ->orderBy('s.fecha')
                ->get();
            }

                $fecha= $request->input("fecha2"); 
        }        

        if(count($consulta)==0){
            $msj="";
        }else{
            $msj="Mostrar";
        }
        $aux_sucursal=$request->aux_sucursal;

        return view("incidencias.reporte_sisa",
        ["estaciones"=>$estaciones,"aux_sucursal"=>$aux_sucursal,"consulta"=>$consulta,"msj"=>$msj,"role"=>$role,"fecha"=>$fecha]);
    }

    public function consultar_companias(Request $request){
        if ($request->ajax()) {
            $razon_s=DB::table('companias as c')
            ->select(DB::raw('e.estacion,c.razon_social'))
            ->join('estaciones as e','e.id_compania','=','c.id')
            ->where('e.estacion','=',$request->estacion)
            ->get();
        return response()->json($razon_s);
        }
    }

    public function consultar_gerentes(Request $request){
        if ($request->ajax()) {
            $gerente=DB::table('gerentes as g')
            ->select(DB::raw('e.estacion,g.nombre'))
            ->join('estaciones as e','e.estacion','=','g.estacion')
            ->where('e.estacion','=',$request->estacion)
            ->get();
        return response()->json($gerente);
        }
    }

    public function pdf_sisa($id){

        $estaciones = DB::table('sisa_captura as s')
        ->select(DB::raw('s.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        ->join('medidas_tanques_catalogo as tc','tc.estacion','=','s.estacion')
        ->join('estaciones as e','e.estacion','=','s.estacion')
        ->join('companias as c','c.id','=','e.id_compania')
        ->where('s.id', '=', $id)
        ->where('tc.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        // $estacion_plus=DB::table('sisa_captura as s')
        // ->select(DB::raw('s.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal,c.razon_social'))
        // ->join('estaciones as e','e.estacion','=','s.estacion')
        // ->join('companias as c','c.id','=','e.id_compania')
        // ->where('s.id', '=', $id)
        // ->where('e.estacion','=','12923');

        //$estaciones= $estaciones->union($estacion_plus)->get();

        foreach($estaciones as $e){
            $sucursal=$e->sucursal;
            $razon_social=$e->razon_social;
        }

        $consulta=DB::table('sisa_captura')
        ->Where('id','=',$id)
        ->groupBy('id')
        ->get();

        $pdf= PDF::loadView('incidencias.reportes.reporte_sisa',compact('consulta','sucursal','razon_social'));
        //return $pdf->download('informe.pdf');
        return $pdf->stream('informe.pdf');        
    }

    public function subir_pdf(Request $request){

        if($request->hasFile("pdf_file")){

            $id=$request->input("txt_id");
            $file=$request->file("pdf_file");            
            $nombre = "pdf_".time().".pdf";
            $ruta = storage_path("app/pdf/".$nombre);

            $editar = sisa_captura::findOrFail($id);
            $editar->archivopdf = $nombre;
            $editar->update();

            //if($file->guessExtension()=="pdf"){
                copy($file, $ruta);
            //}
            $msj="ARCHIVO GUARDADO CON ÉXITO!";
        }else{
            $msj="INGRESA UN ARCHIVO PDF VALIDO";
        }

            return view("incidencias.reporte_sisa_html",["msj"=>$msj]);
    }

    public function anexo_inventario(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;

        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion, concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get(); 

        // $meses=DB::table('anexo_ventas_producto')
        // ->select(DB::raw('DISTINCT date_format(fecha_diavencido,"%m") "MES_NUM", CASE 
        // WHEN date_format(fecha_diavencido,"%m") = "01" THEN "ENERO" 
        // WHEN date_format(fecha_diavencido,"%m") = "02" THEN "FEBRERO" 
        // WHEN date_format(fecha_diavencido,"%m") = "03" THEN "MARZO"
        // WHEN date_format(fecha_diavencido,"%m") = "04" THEN "ABRIL"
        // WHEN date_format(fecha_diavencido,"%m") = "05" THEN "MAYO" 
        // WHEN date_format(fecha_diavencido,"%m") = "06" THEN "JUNIO" 
        // WHEN date_format(fecha_diavencido,"%m") = "07" THEN "JULIO" 
        // WHEN date_format(fecha_diavencido,"%m") = "08" THEN "AGOSTO" 
        // WHEN date_format(fecha_diavencido,"%m") = "09" THEN "SEPTIEMBRE" 
        // WHEN date_format(fecha_diavencido,"%m") = "10" THEN "OCTUBRE" 
        // WHEN date_format(fecha_diavencido,"%m") = "11" THEN "NOVIEMBRE" 
        // WHEN date_format(fecha_diavencido,"%m") = "12" THEN "DICIEMBRE" 
        // END "MES_LETRA"'))
        // ->OrderBy('MES_NUM','DESC')
        // ->get();

        $msj="";

        if($request->estacion=="")
        {
            //return view("incidencias.reporte_anexo_inventario",["message" => $msj,"estaciones"=>$estaciones,"rol"=>$role, "meses"=>$meses]); 
            return view("incidencias.reporte_anexo_inventario",["message" => $msj,"estaciones"=>$estaciones,"rol"=>$role]);
        }
        else{
            $estacion=$request->estacion;
            $fecha1=$request->input("fecha_desde");
            $fecha2=$request->input("fecha_hasta");
            return Excel::download(new Anexo_PlantillaInventario($estacion,$fecha1,$fecha2), 'Plantilla_Inventario.xlsx');
            // $mes=$request->mes;
            // $aux_mes=$request->aux_mes;
            //return Excel::download(new Anexo_PlantillaInventario($estacion,$mes,$aux_mes), 'Plantilla_Inventario.xlsx');
        }
        
    }

    public function anexo_borrar()
    {
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;

        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion, concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get(); 

        foreach($estaciones as $suc){
            $estacion=$suc->estacion;
        }
        $consulta=DB::table('anexo_compras')
        ->where('estacion','=','')
        ->get();
        return view("incidencias.anexo_borrar", ["estaciones"=>$estaciones,"consulta"=>$consulta]);
    }

    public function anexo_borrar_ejecutar(Request $request)
    {
        $user = \Auth::user();
        //$role = $user->role;
        $id_usuario_permiso = $user->id;
        $tabla=$request->input("tabla");
        //$msj="";
        $existe="";

        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion, concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get(); 

        try {
            DB::beginTransaction();

            $borrar = anexo_ventas_producto::where('estacion','=', $request->input("estacion"))
            ->where('fecha_diavencido','=',$request->input("dias"))
            ->delete();
            
            DB::commit();

        } catch (\Exception $e) {
            var_dump($e);
            DB::rollBack();
        }
        $consulta=DB::table('anexo_compras')
        ->where('estacion','=','')
        ->get();
        return redirect()->action('IncidenciasController@anexo_borrar_ejecutar',["estaciones"=>$estaciones,"consulta"=>$consulta])->with('status', 'Venta Eliminada correctamente');          
    }

    public function dias_anexo(Request $request){
        if ($request->ajax()) 
        {
            $dias= DB::table('anexo_ventas_producto')
            ->select(DB::raw('max(fecha_diavencido) fecha_diavencido'))
            ->where('estacion','=',$request->estacion)
            //->whereRaw("CAST(fecha_aplica AS DATE) =".$request->fecha)
            ->get();

            return response()->json($dias);
        }
    }
    
    public function ruta_anexo_ventas(){   
        
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;

        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion, concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get(); 

        foreach($estaciones as $suc){
            $estacion=$suc->estacion;
        //    $sucursal=$suc->sucursal;
            $msj="";
        }

        // //"obtener medidas iniciales"
        // $medidas=DB::table('medidas_diarias')
        // ->where('estacion','=',$estacion)
        // ->whereRaw("CAST(fecha_captura AS DATE) = CAST(CURDATE()-1 AS DATE)")
        // ->get();

        //validar antes de guardar
        // $validar=DB::table('anexo_ventas_producto')
        // ->select(DB::raw('count(1) as capturado'))
        // ->where('estacion','=',$estacion)
        // ->whereRaw('CAST(fecha_captura AS DATE) = CAST(CURDATE() AS DATE)')
        // ->get();

        // foreach($validar as $v){
        //     $anexo_capturado=$v->capturado;
        // }

        // if ($anexo_capturado>0){
        //     $msj="Ya tienes creado el registro de Hoy."; 
        // }

        return view("incidencias.anexo_ventas",
        ["message" => $msj,"estaciones"=>$estaciones,"role"=>$role]);

        // return view("incidencias.anexo_ventas",
        // ["message" => $msj,"estacion"=>$estacion,"sucursal"=>$sucursal,"medidas"=>$medidas]);
    }

    
    public function anexo_ventas_guardar(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estacion=$request->estacion;
        $diavencido = $request->input("fecha");
            $dia=substr($diavencido,8,2);
                
        //obtener estaciones para el input select
        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion, concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get(); 

        //-------------VALIDAR ANEXO REPETIDO----------------
        $validar=DB::table("anexo_ventas_producto")
        ->select(DB::raw('estacion,fecha_diavencido'))
        ->where('estacion','=', $estacion)
        ->where('fecha_diavencido','=', $diavencido)
        ->get();

        $actualizar=count($validar);
        if($actualizar==0)
        {

            //---------------Magna-------------------
            $max=DB::table("anexo_ventas_producto")
            ->select(DB::raw(" case when venta_acum is null then 0 else max(venta_acum) end venta_acum, 
                                case when id is null then 0 else max(id) end idx"))
            ->where('estacion','=',$estacion)
            //->orderBy('fecha_diavencido')
            ->groupBy('id')
            ->get();

            foreach($max as $v){
                $venta_acum=$v->venta_acum;
            }  
            if(count($max)==0){
                $venta_acum=0;
            }    

            $reset_acum_xmes=DB::table("anexo_ventas_producto")
            ->whereRaw('DATE_FORMAT(fecha_diavencido, "%Y-%m")=DATE_FORMAT("'.$diavencido.'", "%Y-%m")')
            ->where('estacion','=',$estacion)
            ->get();
            
            if(count($reset_acum_xmes)==0){
                $venta_acum=0;
                $acum=0;
            }
            
            //------------venta acumulada-----------------
                $suma_venta=($request->input("txt_ventaMagna")+$venta_acum);

            //--------------inventario inicial---------------
            $inv=DB::table("medidas_diarias")
            ->select(DB::raw("magna+magna_T2 magna"))
            ->where("estacion","=",$estacion)
            //->whereRaw("CAST(fecha_captura AS DATE)=CAST(CURDATE()-1 AS DATE)")
            ->whereRaw("CAST(fecha_aplica AS DATE)=CAST('".$diavencido."' AS DATE)")
            //->groupBy("magna")
            ->get();

            foreach($inv as $i){       
                $inv_inicial=$i->magna;
            }
            if(count($inv)==0){
                $inv_inicial=0;
                $msj="Error, No has capturado las medidas iniciales al: ".$diavencido." - NO SE GUARDÓ";
                return view("incidencias.anexo_ventas",["message" => $msj,"estaciones"=>$estaciones,"role"=>$role]);
            }   
            //--------------inventario final---------------
            $inv=DB::table("medidas_diarias")
            ->select(DB::raw("magna+magna_T2 magna"))
            ->where("estacion","=",$estacion)
            //->whereRaw("CAST(fecha_captura AS DATE)=CAST(CURDATE() AS DATE)")
            ->whereRaw("CAST(fecha_aplica AS DATE)=DATE_ADD('".$diavencido."',INTERVAL 1 DAY)")
            ->get();
            foreach($inv as $i){
                $inv_final=$i->magna;  
            }
            if (count($inv)==0){
                $inv_final=0;
                $msj="Error, No has capturado las medidas del inventario final al: ".$diavencido." - NO SE GUARDÓ";
                return view("incidencias.anexo_ventas",["message" => $msj,"estaciones"=>$estaciones,"role"=>$role]);
            }
            //--------------inventario teorico, variacion y venta---------------
            $inv_teorico=($inv_inicial+$request->input("txt_compraMagna"))-$request->input("txt_ventaMagna");
            $variacion=($inv_final-$inv_teorico);
            $venta=$request->input("txt_ventaMagna");
            //--------------porcen variacion---------------
            if($variacion==0 or $venta==0){
                $porc_variacion=0;
            }else {
                $porc_variacion=($variacion/$venta)*100;
            }
            //--------------rot_inv---------------
            if($inv_final==0 or $venta==0){
                $rot_inv=0;
            }else{
                $rot_inv=($inv_final/$venta);
            } 
            //-------------- acum ------------------(agregar periodo)
            $consulta_acum=DB::table("anexo_ventas_producto")
            ->select(DB::raw(" case when acum is null then 0 else max(acum) end acum, 
                                case when id is null then 0 else max(id) end idx"))
            ->where('estacion','=',$estacion)
            ->groupBy('id')
            ->get();

            foreach($consulta_acum as $acm){
                $acum=$acm->acum;
            }  
            if(count($consulta_acum)==0){
                $acum=0;
            }           
            
            $reset_acum_xmes=DB::table("anexo_ventas_producto")
            ->whereRaw('DATE_FORMAT(fecha_diavencido, "%Y-%m")=DATE_FORMAT("'.$diavencido.'", "%Y-%m")')
            ->where('estacion','=',$estacion)
            ->get();
            
            if(count($reset_acum_xmes)==0){
                $acum=0;
            }


            //------------acum= variacion + acum-----------------
                $acum=($variacion+$acum);

            //------------------------ac------------------------
            if($acum==0 or $suma_venta==0){
                $ac=0;
            }else{
                $ac=($acum/$suma_venta)*100;
            }

            $producto= new anexo_ventas_producto;
            $producto->usuario=$id_usuario_permiso;
            $producto->estacion=$estacion;
            $producto->dia=$dia;  
            $producto->producto="Magna";
            $producto->inv_inicial=$inv_inicial;
            $producto->compra=$request->input("txt_compraMagna");
            $producto->venta=$venta;
            $producto->venta_acum=$suma_venta;
            $producto->inv_teorico=$inv_teorico;
            $producto->inv_final=$inv_final;
            $producto->variacion=$variacion;
            $producto->acum=$acum;
            $producto->porc_variacion=$porc_variacion;
            $producto->ac=$ac;
            $producto->rot_inv=$rot_inv;
            $producto->fecha_diavencido=$diavencido;
            $producto->save();

            //---------------Premium-------------------
            $max=DB::table("anexo_ventas_producto")
            ->select(DB::raw(" case when venta_acum is null then 0 else max(venta_acum) end venta_acum, 
                                case when id is null then 0 else max(id) end idx"))
            ->where('estacion','=',$estacion)
            ->groupBy('id')
            ->get();

            foreach($max as $v){
                $venta_acum=$v->venta_acum;
            }
            
            if(count($max)==0){
                $venta_acum=0;
            }
            //------------venta acumulada-----------------
            $suma_venta=($request->input("txt_ventaPremium")+$venta_acum);

            //--------------inventario inicial---------------
            $inv=DB::table("medidas_diarias")
            ->select(DB::raw("premium"))
            ->where("estacion","=",$estacion)
            ->whereRaw("CAST(fecha_aplica AS DATE)=CAST('".$diavencido."' AS DATE)")
            ->get();
    
            foreach($inv as $i){       
                $inv_inicial=$i->premium;
            }
            if(count($inv)==0){
                $inv_inicial=0;
            }   
            //--------------inventario final---------------
            $inv=DB::table("medidas_diarias")
            ->select(DB::raw("premium"))
            ->where("estacion","=",$estacion)
            ->whereRaw("CAST(fecha_aplica AS DATE)=DATE_ADD('".$diavencido."',INTERVAL 1 DAY)")
            ->get();
            foreach($inv as $i){
                $inv_final=$i->premium;  
            }
            if (count($inv)==0){
                $inv_final=0;
            }
            //--------------inventario teorico, variacion y venta---------------
            $inv_teorico=($inv_inicial+$request->input("txt_compraPremium"))-$request->input("txt_ventaPremium");
            $variacion=($inv_final-$inv_teorico);
            $venta=$request->input("txt_ventaPremium");
            //--------------porcen variacion---------------
            if($variacion==0 or $venta==0){
                $porc_variacion=0;
            }else {
                $porc_variacion=($variacion/$venta)*100;
            }
            //--------------rot_inv---------------
            if($inv_final==0 or $venta==0){
                $rot_inv=0;
            }else{
                $rot_inv=($inv_final/$venta);
            } 

            //-------------- acum ------------------(agregar periodo)
            $consulta_acum=DB::table("anexo_ventas_producto")
            ->select(DB::raw(" case when acum is null then 0 else max(acum) end acum, 
                                case when id is null then 0 else max(id) end idx"))
            ->where('estacion','=',$estacion)
            ->groupBy('id')
            ->get();

            foreach($consulta_acum as $acm){
                $acum=$acm->acum;
            }  
            if(count($consulta_acum)==0){
                $acum=0;
            }              

            $acum=($variacion+$acum);

            //------------------------ac------------------------
            if($acum==0 or $suma_venta==0){
                $ac=0;
            }else{
                $ac=($acum/$suma_venta)*100;
            }

            $producto= new anexo_ventas_producto;
            $producto->usuario=$id_usuario_permiso;
            $producto->estacion=$estacion;
            $producto->dia=$dia;  
            $producto->producto="Premium";
            $producto->inv_inicial=$inv_inicial;
            $producto->compra=$request->input("txt_compraPremium");
            $producto->venta=$venta;
            $producto->venta_acum=$suma_venta;
            $producto->inv_teorico=$inv_teorico;
            $producto->inv_final=$inv_final;
            $producto->variacion=$variacion;
            $producto->acum=$acum;
            $producto->porc_variacion=$porc_variacion;
            $producto->ac=$ac;
            $producto->rot_inv=$rot_inv;
            $producto->fecha_diavencido=$diavencido;
            $producto->save();

            //---------------Diesel-------------------
            $max=DB::table("anexo_ventas_producto")
            ->select(DB::raw(" case when venta_acum is null then 0 else max(venta_acum) end venta_acum, 
                                case when id is null then 0 else max(id) end idx"))
            ->where('estacion','=',$estacion)
            ->groupBy('id')
            ->get();

            foreach($max as $v){
                $venta_acum=$v->venta_acum;
            }
            
            if(count($max)==0){
                $venta_acum=0;
            }
            //------------------venta acumulada
            $suma_venta=($request->input("txt_ventaDiesel")+$venta_acum);

            //--------------inventario inicial---------------
            $inv=DB::table("medidas_diarias")
            ->select(DB::raw("diesel"))
            ->where("estacion","=",$estacion)
            ->whereRaw("CAST(fecha_aplica AS DATE)=CAST('".$diavencido."' AS DATE)")
            ->get();

            foreach($inv as $i){       
                $inv_inicial=$i->diesel;
            }
            if(count($inv)==0){
                $inv_inicial=0;
            }   
            //--------------inventario final---------------
            $inv=DB::table("medidas_diarias")
            ->select(DB::raw("diesel"))
            ->where("estacion","=",$estacion)
            ->whereRaw("CAST(fecha_aplica AS DATE)=DATE_ADD('".$diavencido."',INTERVAL 1 DAY)")
            ->get();
            foreach($inv as $i){
                $inv_final=$i->diesel;  
            }
            if (count($inv)==0){
                $inv_final=0;
            }
            //--------------inventario teorico, variacion y venta---------------
            $inv_teorico=($inv_inicial+$request->input("txt_compraDiesel"))-$request->input("txt_ventaDiesel");
            $variacion=($inv_final-$inv_teorico);
            $venta=$request->input("txt_ventaDiesel");
            //--------------porcen variacion---------------
            if($variacion==0 or $venta==0){
                $porc_variacion=0;
            }else {
                $porc_variacion=($variacion/$venta)*100;
            }
            //--------------rot_inv---------------
            if($inv_final==0 or $venta==0){
                $rot_inv=0;
            }else{
                $rot_inv=($inv_final/$venta);
            } 

            //-------------- acum ------------------(agregar periodo)
            $consulta_acum=DB::table("anexo_ventas_producto")
            ->select(DB::raw(" case when acum is null then 0 else max(acum) end acum, 
                                case when id is null then 0 else max(id) end idx"))
            ->where('estacion','=',$estacion)
            ->groupBy('id')
            ->get();

            foreach($consulta_acum as $acm){
                $acum=$acm->acum;
            }  
            if(count($consulta_acum)==0){
                $acum=0;
            }              

            //------------acum= variacion + acum-----------------
            $acum=($variacion+$acum);

            //------------------------ac------------------------
            if($acum==0 or $suma_venta==0){
                $ac=0;
            }else{
                $ac=($acum/$suma_venta)*100;
            }

            $producto= new anexo_ventas_producto;
            $producto->usuario=$id_usuario_permiso;
            $producto->estacion=$estacion;
            $producto->dia=$dia;  
            $producto->producto="Diesel";
            $producto->inv_inicial=$inv_inicial;
            $producto->compra=$request->input("txt_compraDiesel");
            $producto->venta=$venta;
            $producto->venta_acum=$suma_venta;
            $producto->inv_teorico=$inv_teorico;
            $producto->inv_final=$inv_final;
            $producto->variacion=$variacion;
            $producto->acum=$acum;
            $producto->porc_variacion=$porc_variacion;
            $producto->ac=$ac;
            $producto->rot_inv=$rot_inv;
            $producto->fecha_diavencido=$diavencido;
            $producto->save();
            //-----------------------------------------------

            $msj="Anexo creado correctamente";

        
        }else{
            $msj="Ya tienes creado el anexo de este día: ".$diavencido;   
        }


        return view("incidencias.anexo_ventas",
        ["message" => $msj,"estaciones"=>$estaciones,"role"=>$role]);
    }

    public function anexo_consulta(Request $request){

        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;

        $sucursal=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get(); 
            
        if($role=="admin"){
            $estacion=$request->input("estacion");

        }else{
            foreach($sucursal as $suc){
                $estacion=$suc->estacion;
            }
        }

        $meses=DB::table('anexo_ventas_producto')
        ->select(DB::raw('DISTINCT date_format(fecha_diavencido,"%m") "MES_NUM", CASE 
        WHEN date_format(fecha_diavencido,"%m") = "01" THEN "ENERO" 
        WHEN date_format(fecha_diavencido,"%m") = "02" THEN "FEBRERO" 
        WHEN date_format(fecha_diavencido,"%m") = "03" THEN "MARZO"
        WHEN date_format(fecha_diavencido,"%m") = "04" THEN "ABRIL"
        WHEN date_format(fecha_diavencido,"%m") = "05" THEN "MAYO" 
        WHEN date_format(fecha_diavencido,"%m") = "06" THEN "JUNIO" 
        WHEN date_format(fecha_diavencido,"%m") = "07" THEN "JULIO" 
        WHEN date_format(fecha_diavencido,"%m") = "08" THEN "AGOSTO" 
        WHEN date_format(fecha_diavencido,"%m") = "09" THEN "SEPTIEMBRE" 
        WHEN date_format(fecha_diavencido,"%m") = "10" THEN "OCTUBRE" 
        WHEN date_format(fecha_diavencido,"%m") = "11" THEN "NOVIEMBRE" 
        WHEN date_format(fecha_diavencido,"%m") = "12" THEN "DICIEMBRE" 
        END "MES_LETRA"'))
        ->OrderBy('MES_NUM','DESC')
        ->get();

        if($request->input("mes")==""){
            $mes="%m";
            $complemento="";
        }else{
            $mes=$request->input("mes");
            $sig_mes=($mes+1);
            $complemento="and CAST(fecha_diavencido AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ";
        }           
           

       // $set="DB::statement(DB::raw('SET @acum=0'));";
        $consulta_magna=DB::table("anexo_ventas_producto")
        ->select(DB::Raw("id,estacion,dia,producto,inv_inicial,compra,venta,venta_acum,
        inv_teorico,inv_final,Round(variacion,2) variacion,Round(porc_variacion,2) porcen_variacion,  
        Acum, Round(ac,2) ac, Round(rot_inv,2) rot_inv "))
        ->where('estacion','=',$estacion)
        ->where('producto','=','Magna')
        ->whereRaw("CAST(fecha_diavencido  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$mes."-01') ".$complemento);
        
        $consulta_premium=DB::table("anexo_ventas_producto")
        ->select(DB::Raw("id,estacion,dia,producto,inv_inicial,compra,venta,venta_acum,
        inv_teorico,inv_final,Round(variacion,2) variacion,Round(porc_variacion,2) porcen_variacion,  
        Acum, Round(ac,2) ac, Round(rot_inv,2) rot_inv "))
        ->where('estacion','=',$estacion)
        ->where('producto','=','Premium')
        ->whereRaw("CAST(fecha_diavencido  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$mes."-01') ".$complemento);

         $consulta_diesel=DB::table("anexo_ventas_producto")
        ->select(DB::Raw("id,estacion,dia,producto,inv_inicial,compra,venta,venta_acum,
        inv_teorico,inv_final,Round(variacion,2) variacion,Round(porc_variacion,2) porcen_variacion,  
        Acum, Round(ac,2) ac, Round(rot_inv,2) rot_inv "))
        ->where('estacion','=',$estacion)
        ->where('producto','=','Diesel')
        ->whereRaw("CAST(fecha_diavencido  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$mes."-01') ".$complemento);

        //$insert=DB::insert("INSERT INTO 'temp_auxtable'('id', 'dia', 'producto', 'i_inicial', 'compras', 'ventas', 'venta_acum', 'i_teorico', 'i_final', 'variacion', 'acum', 'porcen_variacion', 'ac', 'rot_inv')");
        $consulta=$consulta_magna
        ->union($consulta_premium)
            ->union($consulta_diesel)     
                ->orderBy("id")       
                ->orderBy("dia")      
                ->get();
        
        $aux_sucursal=$request->input("aux_sucursal");
        $aux_mes=$request->input("aux_mes");
        return view("incidencias.anexo_consulta",["consulta"=>$consulta,"sucursal"=>$sucursal,"id_usuario_permiso"=>$id_usuario_permiso,"meses"=>$meses,"aux_sucursal"=>$aux_sucursal,"aux_mes"=>$aux_mes]);
    }

    public function anexo_compras(){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        $consulta=DB::table('anexo_compras')
            //->select(DB::raw("date_format(fecha, '%d/%m/%Y') fecha"))
           // ->where('id','=', $request->input("txt_id_modificar"))
            ->get();   

        $msj="";
        return view("incidencias.anexo_compras",["message" => $msj,"estaciones"=>$estaciones,"consulta"=>$consulta]);
    }

    public function anexo_compras_consultar(Request $request)
    {
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();
        
        $estacion=$request->estacion;

        //validar si existe el registro
            $consulta=DB::table('anexo_compras')
            //->select(DB::raw("date_format(fecha, '%d/%m/%Y') fecha"))
            ->where('estacion','=', $estacion)
            ->where('fecha','=',$request->input("fecha"))
            ->get();        

        return view("incidencias.anexo_borrar",["estaciones"=>$estaciones,"consulta"=>$consulta]);
    }

    public function anexo_compras_eliminar(Request $request)
    {
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();
        
        $estacion=$request->estacion;

        //validar si existe el registro
        $consulta=DB::table('anexo_compras')
        //->select(DB::raw("date_format(fecha, '%d/%m/%Y') fecha"))
        ->where('estacion','=', $estacion)
        ->where('fecha','=',$request->input("fecha"))
        ->get();
        
        // $borrar = anexo_ventas_producto::where('estacion','=', $request->input("estacion"))
        //     ->where('fecha_diavencido','=',$request->input("dias"))
        //     ->delete();

        // return view("incidencias.anexo_borrar",["estaciones"=>$estaciones,"consulta"=>$consulta]);
   
        try {
            DB::beginTransaction();
            $borrar = anexo_compras::where('id','=',$request->input("txt_id"))
            ->delete();
            DB::commit();

        } catch (\Exception $e) {
            var_dump($e);
            DB::rollBack();
        }        
        //return redirect()->action('IncidenciasController@anexo_compras_eliminar',$id)->with('status', 'Compra eliminada correctamente');       
        return redirect()
        ->action('IncidenciasController@anexo_borrar_ejecutar',["estaciones"=>$estaciones,"consulta"=>$consulta])
        ->with('status', 'Compra Eliminada correctamente');          

        //return Redirect::to('anexo_validar/'.$id)->with('status', 'Eliminado correctamente'); 
        //return view("incidencias.anexo_borrar",["estaciones"=>$estaciones,"consulta"=>$consulta]);
    }

    public function anexo_compras_guardar(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();
        // foreach($estaciones as $e){
        //     $estacion=$e->estacion;
        // }
        $estacion=$request->input("estacion");

        //validar compra
        // $validar=DB::table("anexo_compras") 
        // ->where("estacion","=",$estacion)
        // ->where("fecha","=",$request->input("fecha"))
        // ->where("no_tanque","=",$request->input("tanque"))
        // ->where("producto","=",$request->input("producto"))
        // ->where("operador","=",$request->input("operador"))
        // ->get();

        // foreach($validar as $val){
        //     $id=$val->id;
        // }

        // $actualizar=count($validar);
        // end validar

        if($request->input("cubetas")=="")
        {
            $cubetas=0;
        }else{
            $cubetas=$request->input("cubetas");
        }

        $hora_inicia=substr($request->input("inicia"),0,2);
        $minuto_inicia=substr($request->input("inicia"),3,2);

        $hora_termina=substr($request->input("termina"),0,2);
        $minuto_termina=substr($request->input("termina"),3,2);

        $fecha_inicia=Carbon::create(2021,01,01,$hora_inicia,$minuto_inicia,00);
        $fecha_termina=Carbon::create(2021,01,01,$hora_termina,$minuto_termina,00);
        $tiempo_descarga= $fecha_inicia->diffInMinutes($fecha_termina);

        if ($tiempo_descarga>=60){
            $tiempo_descarga=($tiempo_descarga/60);
            if($tiempo_descarga<10){
                $horas=substr($tiempo_descarga,0,1);
                $decimal=substr($tiempo_descarga,1,1);
                if($decimal=="."){
                    $decimal=substr($tiempo_descarga,2,3);
                    $decimal='0.'.$decimal;
                    $minutos=(60)*($decimal);
                    $minutos=round($minutos);

                    if($minutos<10){
                        $tiempo_descarga='0'.$horas.':0'.$minutos.':00'; 
                    }else{
                        $tiempo_descarga='0'.$horas.':'.$minutos.':00'; 
                    }                   
                    //$tiempo_descarga='decimal '.$decimal.' minutos '.$minutos;
                }else{
                    $tiempo_descarga='0'.$horas.':00:00';
                }
            }else{
                $horas=substr($tiempo_descarga,0,2);
                $decimal=substr($tiempo_descarga,2,1);
                if($decimal=="."){
                    $decimal=substr($tiempo_descarga,3,4);
                    $decimal='0.'.$decimal;
                    $minutos=(60)*($decimal);
                    $minutos=round($minutos);
                    $tiempo_descarga=$horas.':'.$minutos.':00';
                }else{
                    $tiempo_descarga=$horas.':00:00';
                }
            }
        }else{
            if ($tiempo_descarga<10){
                $tiempo_descarga='00:0'.$tiempo_descarga.':00';
            }else{
                $tiempo_descarga='00:'.$tiempo_descarga.':00';
            }
        }

        $vol_descarga=($request->input("vfinal")-$request->input("vinicial"));
        //$vol_descarga=0;
        if ($request->input("venta")==""){
            $venta=0;
        }else{
            $venta=$request->input("venta");
        }
        
        $precioLitroIva=($request->input("importe")/$request->input("litros"));
        $precioLitro=($precioLitroIva-($precioLitroIva*0.16));


        // if($actualizar==0){

            $compra = new anexo_compras;
            $compra->usuario=$id_usuario_permiso;
            $compra->estacion=$estacion;
            $compra->no_tanque=$request->input("tanque");
            $compra->cubetas=$cubetas;
            $compra->no_eco=$request->input("num_eco");
            $compra->operador=$request->input("operador");
            $compra->fecha=$request->input("fecha");
            $compra->importe=$request->input("importe");
            $compra->producto=$request->input("producto");
            $compra->litros=$request->input("litros");
            $compra->folioPMX=$request->input("folio");
            $compra->inicia=$request->input("inicia");
            $compra->termina=$request->input("termina");
            $compra->tiempo_descarga=$tiempo_descarga;
            $compra->vol_inicial=$request->input("vinicial");
            $compra->vol_final=$request->input("vfinal");
            $compra->vol_descarga=$vol_descarga;
            $compra->venta_descarga=$venta;
            $compra->precioLitroIva=$precioLitroIva;
            $compra->precioLitro=$precioLitro;
            $compra->save();
            $msj="Compra guardada correctamente";

        
        // }else{

        //     $editar = anexo_compras::findOrFail($id);
        //     $editar->no_tanque=$request->input("tanque");
        //     $editar->cubetas=$cubetas;
        //     $editar->no_eco=$request->input("num_eco");
        //     $editar->operador=$request->input("operador");
        //     $editar->fecha=$request->input("fecha");
        //     $editar->importe=$request->input("importe");
        //     $editar->producto=$request->input("producto");
        //     $editar->litros=$request->input("litros");
        //     $editar->folioPMX=$request->input("folio");
        //     $editar->inicia=$request->input("inicia");
        //     $editar->termina=$request->input("termina");
        //     $editar->tiempo_descarga=$tiempo_descarga;
        //     $editar->vol_inicial=$request->input("vinicial");
        //     $editar->vol_final=$request->input("vfinal");
        //     $editar->vol_descarga=$vol_descarga;
        //     $editar->venta_descarga=$venta;
        //     $editar->precioLitroIva=$precioLitroIva;
        //     $editar->precioLitro=$precioLitro;
        //     $editar->update();
        //     $msj="Compra Actualizada correctamente";
                
        // }

        return view("incidencias.anexo_compras",["message" => $msj,"estaciones"=>$estaciones]);
    }    

    public function anexo_diferencia(){
        $msj="";
        return view("incidencias.anexo_diferencia",["message" => $msj]);
    }

    public function anexo_diferencia_guardar(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estacion=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();

        foreach($estacion as $e){
            $estacion=$e->estacion;
        }
        $calculo=Round(($request->input("litros")-$request->input("acumulado")),2);

        // //SELECT * FROM `anexo_diferencia` where estacion='CORPORATIVO' and fecha='2021-06-15' and turno=1
        $validar=DB::table('anexo_diferencia')
        ->where('estacion','=',$estacion)
        ->where('fecha','=',$request->input("fecha"))
        ->where('turno','=',$request->input("turno"))
        ->get();

        foreach($validar as $val){
            $id=$val->id;
        }

        $actualizar=count($validar);

        if($actualizar==0)
        {
            $dif=new anexo_diferencia;
            $dif->usuario=$id_usuario_permiso;
            $dif->estacion=$estacion;
            $dif->fecha=$request->input("fecha");
            $dif->turno=$request->input("turno");
            $dif->diferencia_litros=$request->input("litros");
            $dif->acumulado=$request->input("acumulado");
            $dif->diferencia=$calculo;
            $dif->total="0";
            $dif->save();
            $msj="Registro guardado correctamente";
        }
        else
        {
            $editar = anexo_diferencia::findOrFail($id);
            $editar->diferencia_litros=$request->input("litros");
            $editar->acumulado=$request->input("acumulado");
            $editar->diferencia=$calculo;
            $editar->update();
            $msj="Registro Actualizado correctamente";
        }        

        return view("incidencias.anexo_diferencia",["message" => $msj]);
    }

    public function reporte_anexo(){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $role = $user->role; 

        // $meses=DB::table('anexo_ventas_producto')
        // ->select(DB::raw('DISTINCT date_format(fecha_diavencido,"%m") "MES_NUM", CASE 
        // WHEN date_format(fecha_diavencido,"%m") = "01" THEN "ENERO" 
        // WHEN date_format(fecha_diavencido,"%m") = "02" THEN "FEBRERO" 
        // WHEN date_format(fecha_diavencido,"%m") = "03" THEN "MARZO"
        // WHEN date_format(fecha_diavencido,"%m") = "04" THEN "ABRIL"
        // WHEN date_format(fecha_diavencido,"%m") = "05" THEN "MAYO" 
        // WHEN date_format(fecha_diavencido,"%m") = "06" THEN "JUNIO" 
        // WHEN date_format(fecha_diavencido,"%m") = "07" THEN "JULIO" 
        // WHEN date_format(fecha_diavencido,"%m") = "08" THEN "AGOSTO" 
        // WHEN date_format(fecha_diavencido,"%m") = "09" THEN "SEPTIEMBRE" 
        // WHEN date_format(fecha_diavencido,"%m") = "10" THEN "OCTUBRE" 
        // WHEN date_format(fecha_diavencido,"%m") = "11" THEN "NOVIEMBRE" 
        // WHEN date_format(fecha_diavencido,"%m") = "12" THEN "DICIEMBRE" 
        // END "MES_LETRA"'))
        // ->OrderBy('MES_NUM','DESC')
        // ->get();

        $sucursal=DB::table('estaciones as e')
        ->select(DB::raw('e.estacion,concat_ws(" - ",e.estacion,e.nombre_corto) as sucursal'))
        ->join('usuarios_estaciones as u','e.estacion','=','u.estacion')
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw('e.estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(e.estacion as unsigned)')
        ->get();
      
        $fecha= Carbon::now();
        $diavencido = $fecha->subDays(1); 
        $diavencido = $diavencido->format('Y-m-d');

        if ($role=="admin")
        {            
            $medidas_tbl= DB::table('medidas_tanques_catalogo as a')
           ->select(DB::raw('a.estacion,e.nombre_corto'))
           ->join('estaciones as e','e.estacion','=','a.estacion')
           ->whereRaw("a.estacion not in (select estacion from anexo_ventas_producto where cast(fecha_diavencido as date)='".$diavencido."')")
           ->get();
           $cant=count($medidas_tbl);

       }else
       {
           $medidas_tbl= DB::table('anexo_ventas_producto as a')
           ->select(DB::raw('a.estacion,a.dia,a.producto,a.inv_inicial,a.compra,a.venta,a.venta_acum,a.inv_teorico,a.inv_final'))
           ->join('usuarios_estaciones as u', function($join){
               $join->on('a.estacion','=','u.estacion');
               })
           ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
           ->whereRaw("CAST(a.fecha_captura AS DATE) = CAST(CURDATE() AS DATE)")
           ->get();
           $cant=0;
       }
  
       return view('incidencias.reporte_anexo1',["sucursal"=>$sucursal,"rol"=>$role,"cant"=>$cant,"medidas_tbl"=>$medidas_tbl]);
            //return view('incidencias.reporte_anexo1',["sucursal"=>$sucursal,"meses"=>$meses,"rol"=>$role,"cant"=>$cant,"medidas_tbl"=>$medidas_tbl]);
            //return view('incidencias.captura_medidas',["estaciones" => $estaciones,"medidas_tbl"=>$medidas_tbl]);
        
    }

    public function reporte_anexo_excel(Request $request){
        $estacion=$request->estacion;
        $sucursal=$request->aux_sucursal;
        // $mes=$request->mes;
        // $aux_mes=$request->aux_mes;

        // $sig_mes=($mes+1);
        // if($sig_mes<10){
        //     $sig_mes="0".$sig_mes;
        // }

        // $complemento="and CAST(fecha AS DATE) < DATE_FORMAT(NOW(),'%Y-".$sig_mes."-01') ";

        // if($mes==12)
        // {
        //     $complemento="";
        // }

        $fecha1 = $request->input("fecha_desde");
        $fecha2 = $request->input("fecha_hasta");
        //$mes_letra = $request->input("aux_mes");

        // Carbon::setLocale('es');
        setlocale(LC_ALL, 'es_ES');
        $mes=Carbon::createFromFormat('Y-m-d',$fecha1);
        $mes_letra=strtoupper($mes->formatLocalized('%B'));

         $consulta=DB::table("anexo_compras")
        ->select(DB::raw("id"))
        ->where('estacion','=',$estacion)
        // ->whereRaw("CAST(fecha  AS DATE) >= DATE_FORMAT(NOW(),'%Y-".$mes."-01') ".$complemento)
        ->whereRaw("CAST(fecha AS DATE) between cast('$fecha1' as date) and cast('$fecha2' as date)")
        ->get();

        $compras_cant=count($consulta);

        return Excel::download(new AnexoExport($estacion,$sucursal,$fecha1,$fecha2,$compras_cant,$mes_letra), 'ANEXO.xlsx');
        //return Excel::download(new AnexoExport($estacion,$mes,$sucursal,$aux_mes,$compras_cant), 'ANEXO.xlsx');
    }

    // public function reporte_comprasGral(Request $request){
    //     $user = \Auth::user();
    //     $id_usuario_permiso = $user->id;

    //     $meses=DB::table('anexo_ventas_producto')
    //     ->select(DB::raw('DISTINCT date_format(fecha_diavencido,"%m") "MES_NUM", CASE 
    //     WHEN date_format(fecha_diavencido,"%m") = "01" THEN "ENERO" 
    //     WHEN date_format(fecha_diavencido,"%m") = "02" THEN "FEBRERO" 
    //     WHEN date_format(fecha_diavencido,"%m") = "03" THEN "MARZO"
    //     WHEN date_format(fecha_diavencido,"%m") = "04" THEN "ABRIL"
    //     WHEN date_format(fecha_diavencido,"%m") = "05" THEN "MAYO" 
    //     WHEN date_format(fecha_diavencido,"%m") = "06" THEN "JUNIO" 
    //     WHEN date_format(fecha_diavencido,"%m") = "07" THEN "JULIO" 
    //     WHEN date_format(fecha_diavencido,"%m") = "08" THEN "AGOSTO" 
    //     WHEN date_format(fecha_diavencido,"%m") = "09" THEN "SEPTIEMBRE" 
    //     WHEN date_format(fecha_diavencido,"%m") = "10" THEN "OCTUBRE" 
    //     WHEN date_format(fecha_diavencido,"%m") = "11" THEN "NOVIEMBRE" 
    //     WHEN date_format(fecha_diavencido,"%m") = "12" THEN "DICIEMBRE" 
    //     END "MES_LETRA"'))
    //     ->OrderBy('MES_NUM','DESC')
    //     ->get();
        
    //     return view('incidencias.reporte_comprasGral',["meses"=>$meses]);
    // }

    // public function reporte_comprasGral_Exp(Request $request){
    //     $mes=$request->mes;
    //     $aux_mes=$request->aux_mes;
    //     return Excel::download(new AnexoExport_General($mes,$aux_mes), 'Compras_Anexo.xlsx');
    // }

    public function bitacora_alta(){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $catalogo=DB::table('bitacora_catalogo')->get();

        return view("incidencias.bitacora_altapiezas",["catalogo"=>$catalogo]);
    }

    public function bitacora_catalogo(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        
        if($request->editar=="editar"){
            $editar = Bitacora_catalogo::findOrFail($request->input("id"));
            $editar->descripcion = trim($request->input("descripcion"));
            $editar->update();
        }
        else
        {
            $tbl_bitacora_catalogo= new Bitacora_catalogo;
            $tbl_bitacora_catalogo->descripcion = trim($request->input("descripcion"));
            $tbl_bitacora_catalogo->id_usuario = $id_usuario_permiso;
            $tbl_bitacora_catalogo->save();
        }    
        $catalogo=DB::table('bitacora_catalogo')->get();

        return view("incidencias.bitacora_altapiezas",["catalogo"=>$catalogo]);
    }

    // public function bitacora_catalogo_editar(Request $request){
    //     $user = \Auth::user();
    //     $id_usuario_permiso = $user->id;
    //         $editar = Bitacora_Catalogo::findOrFail($request->input("id"));
    //         $editar->descripcion = trim($request->input("descripcion"));
    //        // $editar->id_usuario = $id_usuario_permiso;
    //         $editar->update();
            
    //     $catalogo=DB::table('bitacora_catalogo')->get();

    //     //return view("incidencias.bitacora_altapiezas",["catalogo"=>$catalogo]);
    //     return redirect()->action('IncidenciasController@bitacora_alta')->with('status', 'Se editó correctamente');
    // }

    public function bitacora_captura(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
           
        $sucursal=DB::table('bitacora_vehiculos as v')
        ->select(DB::raw('v.estacion, concat(v.estacion," - ",e.nombre_corto) as sucursal'))
        ->join('estaciones as e','v.estacion','=','e.estacion')
        ->groupBy('v.estacion')
        ->orderBy('sucursal')
        ->get();        

        $catalogo=DB::table('bitacora_catalogo')->get();

        return view("incidencias.bitacora_captura",["sucursal"=>$sucursal,"catalogo"=>$catalogo]);
    }

    public function capturar_bitacora(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;

        $bitacora =new Bitacora_mtto;
        $bitacora->id_usuario=$id_usuario_permiso;
        $bitacora->id_vehiculo=$request->input("vehiculo");
        $bitacora->fecha_bitacora=$request->input("fecha");
        $bitacora->nota=$request->input("nota");
        $bitacora->trabajo=$request->input("trabajo");
        $bitacora->observaciones=$request->input("observaciones");
        $bitacora->save();

        $id_bitacora=0;
        $ver_bitacora=DB::table('bitacora_mtto')
        ->select(DB::raw('max(id) id'))
        ->get();

        foreach($ver_bitacora as $b){
            $id_bitacora=$b->id;
        }

        try {
            DB::beginTransaction();

            $cantidad=$request->input('cantidad');
            $unidad=$request->input('unidad');
            $refaccion=$request->input('refaccion');
            $importe=$request->input('precio_uni');
            $iva=$request->input('iva_uni');
            $total=$request->input('total_uni');

            $cont = 0;
            while($cont < count($cantidad)){
                $detalle= new Bitacora_mtto_detalle();
                $detalle->id_bitacora=$id_bitacora;             
                $detalle->cantidad = $cantidad[$cont];
                $detalle->unidad = $unidad[$cont];
                $detalle->refaccion = $refaccion[$cont];  
                $detalle->importe = $importe[$cont];  
                $detalle->iva = $iva[$cont];  
                $detalle->total = $total[$cont];                
                $detalle->save();

                $cont = $cont + 1;
            }
                DB::commit();

            } 
        catch (\Exception $e) {
                DB::rollBack();
        } 

        return Redirect::to('bitacora_captura')->with(['message' => 'Bitacora Guardada Correctamente']);
    }

    public function bit_moto(Request $request){
        if ($request->ajax()) {
            if ($request->input('sucursal')<>'0')
            {
                $vehiculo=DB::table('bitacora_vehiculos as v')
                ->select(DB::raw('v.id,concat(v.descripcion," ",v.marca," ",v.categoria," ",v.version," ",v.modelo) as descripcion, concat(v.estacion," - ",e.nombre_corto) as sucursal'))
                ->join('estaciones as e','v.estacion','=','e.estacion')
                ->where('e.estacion','=',$request->estacion)
                ->orderBy('sucursal')
                ->get();
            }
        return response()->json($vehiculo);
        }
    }

    public function bit_fecha(Request $request){
        if ($request->ajax()) {
            if ($request->input('sucursal')<>'0')
            {
                $fecha=DB::table('bitacora_mtto as bm')
                ->select(DB::raw('bm.id id, concat(bm.id," - ",bm.fecha_bitacora) fecha'))
                ->join('bitacora_vehiculos as v','v.id','=','bm.id_vehiculo')
                ->where('v.estacion','=',$request->estacion)
                ->where('v.id','=',$request->vehiculo)
                ->get();
            }
        return response()->json($fecha);
        }
    }

    public function bitacora_consultas(){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
           
        $sucursal=DB::table('bitacora_vehiculos as v')
        ->select(DB::raw('v.estacion, concat(v.estacion," - ",e.nombre_corto) as sucursal'))
        ->join('estaciones as e','v.estacion','=','e.estacion')
        ->groupBy('v.estacion')
        ->orderBy('sucursal')
        ->get();        
        $bitacora=DB::table('bitacora_mtto')->where('id','=','0')->get();
        $detalle=DB::table('bitacora_mtto_detalle')->where('id','=','0')->get();

        return view("incidencias.bitacora_consultas",["sucursal"=>$sucursal,"bitacora"=>$bitacora,"detalle"=>$detalle]);
    }

    public function bitacora_consultas_buscar(Request $request){
        $sucursal=DB::table('bitacora_vehiculos as v')
        ->select(DB::raw('v.estacion, concat(v.estacion," - ",e.nombre_corto) as sucursal'))
        ->join('estaciones as e','v.estacion','=','e.estacion')
        ->groupBy('v.estacion')
        ->orderBy('sucursal')
        ->get(); 

        $bitacora=DB::table('bitacora_mtto')
        ->select(DB::raw('DATE_FORMAT(fecha_bitacora, "%d %m %Y") fecha_bitacora,nota,trabajo,observaciones'))
        ->where('id','=',$request->fecha)
        ->get();

        $totales=DB::table('bitacora_mtto_detalle')
        ->select(DB::raw("'','','','','','Total:', concat('$',Round( sum(total),2) )"))
        ->where('id_bitacora','=',$request->fecha);
       
        $detalle=DB::table('bitacora_mtto_detalle as d')
        ->select(DB::raw('d.cantidad,d.unidad,c.descripcion,Round (d.importe, 2) importe,Round ((d.importe*d.cantidad), 2) puXcant ,Round (d.iva, 2) iva,Round(d.total,2) total'))
        ->join('bitacora_catalogo as c','c.id','=','d.refaccion')
        ->where('d.id_bitacora','=',$request->fecha)
        ->UNION($totales)
        ->get();

        //$vehiculo=$request->vehiculo;
        $vehiculo=DB::table('bitacora_vehiculos as v')
        ->select(DB::raw('concat(v.estacion," - ",e.nombre_corto) as sucursal, concat(v.descripcion," ",v.marca," ",v.categoria," ",v.version," ",v.modelo,", Placas: ", v.placas) as descripcion, v.imagen as imagen'))
        ->join('estaciones as e','v.estacion','=','e.estacion')
        ->where('v.id','=',$request->vehiculo)
        ->get();

        foreach($vehiculo as $ve){
            $vehiculo   =   $ve->descripcion;
            $img        =   $ve->imagen;
            $estacion   =   $ve->sucursal;
        }

        return view("incidencias.bitacora_consultas",["sucursal"=>$sucursal,"bitacora"=>$bitacora,"detalle"=>$detalle,"vehiculo"=>$vehiculo,"estacion"=>$estacion,"img"=>$img]);
    }

    public function reporte_bitacora(){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
           
        $sucursal=DB::table('bitacora_vehiculos as v')
        ->select(DB::raw('v.estacion, concat(v.estacion," - ",e.nombre_corto) as sucursal'))
        ->join('estaciones as e','v.estacion','=','e.estacion')
        ->orderBy('sucursal')
        ->get(); 
        return view("incidencias.reporte_bitacora",["sucursal"=>$sucursal]);
    }

    public function reporte_bitacora_excel(Request $request){

        $user = \Auth::user();
        $estacion = $request->input('estacion');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');
        
        $vehiculo=DB::table('bitacora_vehiculos as v')
        ->select(DB::raw('concat("ESTACION: ",v.estacion," - ",e.nombre_corto) as sucursal, 
            concat(v.descripcion," ",v.marca," ",v.categoria," ",v.version," ",v.modelo,", PLACAS: ", v.placas) as descripcion, v.imagen as imagen'))
        ->join('estaciones as e','v.estacion','=','e.estacion')
        ->where('v.estacion','=',$estacion)
        ->get();

        foreach($vehiculo as $ve){
            $vehiculo   =   $ve->descripcion;
            $img        =   $ve->imagen;
            $sucursal   =   $ve->sucursal;
        }
        return Excel::download(new BitacoraExport($estacion,$fecha_desde,$fecha_hasta,$vehiculo,$sucursal), 'ReporteBitacora.xlsx');
    }

    public function captura_medidas(){
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones')
            ->where('id_usuario_permiso', '=', $id_usuario_permiso)
            ->whereRaw('estacion not in ("CORPORATIVO","H. INDIGO")')
            ->orderByRaw('cast(estacion as unsigned)')
            ->get();
            
            $medidas_tbl= DB::table('medidas_diarias as m')
            ->select(DB::raw('m.estacion, m.magna, m.premium, m.diesel, m.fecha_aplica'))
            ->join('usuarios_estaciones as u', function($join){
                $join->on('m.estacion','=','u.estacion');
                $join->on('m.usuario','=','u.id_usuario_permiso');
                })
            ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
            ->whereRaw("CAST(m.fecha_captura AS DATE) = CAST(CURDATE() AS DATE)")
            ->OrderBy("m.fecha_aplica")
            ->get();

            // $ValidarHorario=DB::table('medidas_horario')->get();

            // foreach($ValidarHorario as $h){
            //     $hbd=$h->HorarioActivo;
            // } 
            // if($hbd==0){
            //     $msj="Los sentimos, estas intentando capturar Fuera de horario. Horario de captura: 6:00 am a 10:00 am";
            // }else{$msj="";}
            $msj="";

            foreach($estaciones as $es){
                $estacion_bd=$es->estacion;
            }

            $catalogo_ob=DB::table('medidas_observaciones_catalogo')->get();
            
            setlocale(LC_ALL,"es_MX");
            //$fechaFormato = strftime("%A %d %B %Y", strtotime( date('Y-m-d') ));
            $fechaFormato = strftime("%A", strtotime( date('Y-m-d') ));
            setlocale(LC_ALL,"");
            $fechaFormato = utf8_encode($fechaFormato);  
            
            $AbrirDias=DB::table('medidas_dias')->get();

            foreach($AbrirDias as $ad){
                $dd=$ad->dias;
            }

            $dias=[];
            for($i=0;$i<=$dd;$i++){
                $dias[]=$i;
            }

        return view('incidencias.captura_medidas',[
            "estaciones" => $estaciones,
            "medidas_tbl"=>$medidas_tbl,
            //"ValidarHorario"=>$ValidarHorario,
            "catalogo_ob"=>$catalogo_ob,
            "id_usuario_permiso"=>$id_usuario_permiso,
            "message" => $msj,
            "fechaFormato"=>$fechaFormato,
            "dias"=>$dias]);
    }

    public function consultartanques(Request $request){
        if ($request->ajax()) {
            $tanques=DB::table('medidas_tanques_catalogo')
            ->where('estacion','=',$request->estacion)
            ->get();
        return response()->json($tanques);
        }
    }

    public function activarhorario(Request $request){
        if ($request->ajax()) 
        {
            //$ValidarHorario=$request->horario;
            $ValidarHorario=DB::table('medidas_horario')->get();

            foreach($ValidarHorario as $h){
                $hbd=$h->HorarioActivo;
            } 
            if($hbd==0){
                $boleano=1;
            }else{
                $boleano=0;;
            }

            $ActivarHorario=DB::table('medidas_horario')
            ->where('id', 1)
            ->update(['HorarioActivo' => $boleano]);

        return response($boleano); 
        }
    }

    public function guardar_medidas(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;

        //consulto por coincidencias en fecha
        $medidas_tbl= DB::table('medidas_diarias as m')
        ->select(DB::raw('m.id,m.estacion, CAST(m.fecha_aplica AS DATE) fecha_aplica'))
        ->join('usuarios_estaciones as u', function($join){ $join->on('m.estacion','=','u.estacion');})
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->where ('m.estacion','=',$request->input('estacion')) 
        ->where('m.fecha_aplica','=',$request->input('fecha'))
        ->get();

        foreach($medidas_tbl as $m){
            $id_bd=$m->id;
        } 
        
        //5420
        if($request->input('estacion')=="5420"){
            $magnaT2=$request->input('txt_magna_T2');
            if ($request->input('txt_magna_T2')==""){
                $magnaT2=0;
            }
        }else
        {
            $magnaT2=0;
        }
        
        //validar variables pipas
        if($request->input('pipa1')==""){
            $pipa1=0;
        }else{
            $pipa1=$request->input('pipa1');
        }
        if($request->input('pipa2')==""){
            $pipa2=0;
        }else{
            $pipa2=$request->input('pipa2');
        }
        if($request->input('pipa3')==""){
            $pipa3=0;
        }else{
            $pipa3=$request->input('pipa3');
        }
        
        $tbl_medidasdiarias= new Medidas_Diarias;
        $tbl_medidasdiarias->usuario = $id_usuario_permiso;
        $tbl_medidasdiarias->estacion = $request->input('estacion');
        $tbl_medidasdiarias->magna = $request->input('txt_magna');
        $tbl_medidasdiarias->magna_T2 = $magnaT2;
        $tbl_medidasdiarias->premium = $request->input('txt_premium');
        $tbl_medidasdiarias->diesel = $request->input('txt_diesel');

        $tbl_medidasdiarias->pipa_magna=$pipa1;
        $tbl_medidasdiarias->pipa_premium=$pipa2;
        $tbl_medidasdiarias->pipa_diesel=$pipa3;
        $tbl_medidasdiarias->observ_magna=$request->input('ob_p1');
        $tbl_medidasdiarias->observ_premium=$request->input('ob_p2');
        $tbl_medidasdiarias->observ_diesel=$request->input('ob_p3');
        $tbl_medidasdiarias->fecha_aplica=$request->input('fecha');

        //validando para ver si guardo o actualizo.
            if (count($medidas_tbl)==0){
                $tbl_medidasdiarias->save();
            }
            else
            {
                $medida = Medidas_Diarias::findOrFail($id_bd);

                $medida->magna = $request->input('txt_magna');
                $medida->magna_T2 = $magnaT2;
                $medida->premium = $request->input('txt_premium');
                $medida->diesel = $request->input('txt_diesel');
                $medida->pipa_magna = $pipa1;
                $medida->pipa_premium = $pipa2;
                $medida->pipa_diesel = $pipa3;
                $medida->observ_magna = $request->input('ob_p1');
                $medida->observ_premium = $request->input('ob_p2');
                $medida->observ_diesel = $request->input('ob_p3');
                $medida->fecha_captura=Carbon::now();
                $medida->fecha_aplica=$request->input('fecha');
                $medida->update();
            } 
       //end else
       
       //actualizando visibilidad
        $medidas_tbl= DB::table('medidas_diarias as m')
        ->select(DB::raw('m.id,m.estacion, m.magna+m.magna_T2 magna, m.premium, m.diesel, CAST(m.fecha_aplica AS DATE) fecha_aplica'))
        ->join('usuarios_estaciones as u', function($join){
            $join->on('m.estacion','=','u.estacion');
            })
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->whereRaw("CAST(m.fecha_captura AS DATE) = CAST(CURDATE() AS DATE)")
        ->ORderBy("m.id")
        ->get();

        $estaciones= DB::table('usuarios_estaciones')
        ->select(DB::raw('estacion'))
        ->where('id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(estacion as unsigned)')
        ->get();

        $catalogo_ob=DB::table('medidas_observaciones_catalogo')->get();
        
        $AbrirDias=DB::table('medidas_dias')->get();

            foreach($AbrirDias as $ad){
                $dd=$ad->dias;
            }

            $dias=[];
            for($i=0;$i<=$dd;$i++){
                $dias[]=$i;
            }
        
        //visibilidad lunes
        setlocale(LC_ALL,"es_MX");
        //$fechaFormato = strftime("%A %d %B %Y", strtotime( date('Y-m-d') ));
        $fechaFormato = strftime("%A", strtotime( date('Y-m-d') ));
        setlocale(LC_ALL,"");
        $fechaFormato = utf8_encode($fechaFormato);
        
         return view('incidencias.captura_medidas',[
            "estaciones" => $estaciones,
            "medidas_tbl"=>$medidas_tbl,
            //"ValidarHorario"=>$ValidarHorario,
            "catalogo_ob"=>$catalogo_ob,                
            "id_usuario_permiso"=>$id_usuario_permiso,
            "message","message",
            "fechaFormato"=>$fechaFormato,
            "dias"=>$dias]
        );
    }

    public function guardar_medidas1(Request $request){
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
                
        $estaciones= DB::table('usuarios_estaciones')
         ->select(DB::raw('estacion'))
         ->where('id_usuario_permiso', '=', $id_usuario_permiso)
         ->whereRaw('estacion not in ("CORPORATIVO","H. INDIGO")')
         ->orderByRaw('cast(estacion as unsigned)')
         ->get();

         $catalogo_ob=DB::table('medidas_observaciones_catalogo')->get();

        //consulto por coincidencias en fecha
        $medidas_tbl= DB::table('medidas_diarias as m')
        ->select(DB::raw('m.id,m.estacion, m.magna, m.premium, m.diesel, CAST(m.fecha_aplica AS DATE) fecha_aplica'))
        ->join('usuarios_estaciones as u', function($join){
            $join->on('m.estacion','=','u.estacion');
            })
        ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
        ->where ('m.estacion','=',$request->input('estacion')) //actualizacion-cambio 20-01-2021 (modo prueba)
        //->whereRaw("CAST(m.fecha_captura AS DATE) = CAST(CURDATE() AS DATE)")
        //->whereRaw("m.fecha_captura=".$request->input('fecha'))
        ->where('m.fecha_aplica','=',$request->input('fecha'))
        ->get();

        foreach($medidas_tbl as $m){
            $id_bd=$m->id;
            $fecha_bd = $m->fecha_aplica;
            $estacion_bd = $m->estacion;
        } 


        $ValidarHorario=DB::table('medidas_horario')
        ->get();
        foreach($ValidarHorario as $h){
            $hbd=$h->HorarioActivo;
        } 
        if($hbd==0){
            //horario cerrado. No se guarda
        }else
        {
            //obtengo los valores aguardar
            $magnaT2=0;

            if($request->input('estacion')=="5420"){
                $magnaT2=$request->input('txt_magna_T2');
                if ($request->input('txt_magna_T2')==""){
                    $magnaT2=0;
                }
            }
            
            //cambio medias rpt
            if($request->input('pipa1')==""){
                $pipa1=0;
            }else{
                $pipa1=$request->input('pipa1');
            }
            if($request->input('pipa2')==""){
                $pipa2=0;
            }else{
                $pipa2=$request->input('pipa2');
            }
            if($request->input('pipa3')==""){
                $pipa3=0;
            }else{
                $pipa3=$request->input('pipa3');
            }


            $tbl_medidasdiarias= new Medidas_Diarias;
            $tbl_medidasdiarias->usuario = $id_usuario_permiso;
            $tbl_medidasdiarias->estacion = $request->input('estacion');
            $tbl_medidasdiarias->magna = $request->input('txt_magna');
            $tbl_medidasdiarias->magna_T2 = $magnaT2;
            $tbl_medidasdiarias->premium = $request->input('txt_premium');
            $tbl_medidasdiarias->diesel = $request->input('txt_diesel');

            $tbl_medidasdiarias->pipa_magna=$pipa1;
            $tbl_medidasdiarias->pipa_premium=$pipa2;
            $tbl_medidasdiarias->pipa_diesel=$pipa3;
            $tbl_medidasdiarias->observ_magna=$request->input('ob_p1');
            $tbl_medidasdiarias->observ_premium=$request->input('ob_p2');
            $tbl_medidasdiarias->observ_diesel=$request->input('ob_p3');
            $tbl_medidasdiarias->fecha_aplica=$request->input('fecha');
            
            //validando para ver si guardo o actualizo.
            if (count($medidas_tbl)==0){
            //if ($id_bd==""){
                $tbl_medidasdiarias->save();
            }
            //elseif ($fecha_bd==$fecha_hoy && $estacion_bd==$request->input('estacion')){
            else //if ($fecha_bd==$request->input('fecha') && $estacion_bd==$request->input('estacion')){
            {
                $medida = Medidas_Diarias::findOrFail($id_bd);

                $medida->magna = $request->input('txt_magna');
                $medida->magna_T2 = $magnaT2;
                $medida->premium = $request->input('txt_premium');
                $medida->diesel = $request->input('txt_diesel');
                $medida->pipa_magna = $pipa1;
                $medida->pipa_premium = $pipa2;
                $medida->pipa_diesel = $pipa3;
                $medida->observ_magna = $request->input('ob_p1');
                $medida->observ_premium = $request->input('ob_p2');
                $medida->observ_diesel = $request->input('ob_p3');
                //$medida->fecha_captura=Carbon::now();
                $medida->fecha_aplica=$request->input('fecha');
                $medida->update();
            }   
                
                //actualizando visibilidad
                $medidas_tbl= DB::table('medidas_diarias as m')
                ->select(DB::raw('m.id,m.estacion, m.magna+m.magna_T2 magna, m.premium, m.diesel, CAST(m.fecha_aplica AS DATE) fecha_aplica'))
                ->join('usuarios_estaciones as u', function($join){
                    $join->on('m.estacion','=','u.estacion');
                    })
                ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
                ->whereRaw("CAST(m.fecha_captura AS DATE) = CAST(CURDATE() AS DATE)")
                ->ORderBy("m.id")
                ->get();
                
                //->orderBy("m.id","desc")
                //->limit(1)

                //visibilidad lunes
                setlocale(LC_ALL,"es_MX");
                //$fechaFormato = strftime("%A %d %B %Y", strtotime( date('Y-m-d') ));
                $fechaFormato = strftime("%A", strtotime( date('Y-m-d') ));
                setlocale(LC_ALL,"");
                $fechaFormato = utf8_encode($fechaFormato);
            
        }
        $AbrirDias=DB::table('medidas_dias')->get();

            foreach($AbrirDias as $ad){
                $dd=$ad->dias;
            }

            $dias=[];
            for($i=0;$i<=$dd;$i++){
                $dias[]=$i;
            }

            return view('incidencias.captura_medidas',[
                "estaciones" => $estaciones,
                "medidas_tbl"=>$medidas_tbl,
                "ValidarHorario"=>$ValidarHorario,
                "catalogo_ob"=>$catalogo_ob,                
                "id_usuario_permiso"=>$id_usuario_permiso,
                "message","message",
                "fechaFormato"=>$fechaFormato,
                "dias"=>$dias]
            );
            
    }

    public function mostrar_medidas(){ 
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        
        $estaciones= DB::table('usuarios_estaciones')
        ->select(DB::raw('estacion'))
        ->where('id_usuario_permiso', '=', $id_usuario_permiso)
        ->whereRaw('estacion not in ("CORPORATIVO","H. INDIGO")')
        ->orderByRaw('cast(estacion as unsigned)')
        ->get();

        if ($role=="admin"){            

            //query: SELECT estacion,descripcion FROM medidas_tanques_catalogo where estacion not in (select estacion from medidas_diarias where cast(fecha_captura as date)=cast(curdate() as date))
            $medidas_tbl= DB::table('medidas_tanques_catalogo')
            ->select(DB::raw('estacion,descripcion'))
            ->whereRaw("estacion not in (select estacion from medidas_diarias where cast(fecha_aplica as date)=cast(curdate() as date))")
            ->get();
            $cant=count($medidas_tbl);

        }else{

            $medidas_tbl= DB::table('medidas_diarias as m')
            ->select(DB::raw('m.id,m.estacion, m.magna+m.magna_T2 magna, m.premium, m.diesel, CAST(m.fecha_aplica AS DATE) fecha_aplica'))
            ->join('usuarios_estaciones as u', function($join){
                $join->on('m.estacion','=','u.estacion');
                })
            ->where('u.id_usuario_permiso','=',$id_usuario_permiso)
            ->whereRaw("CAST(m.fecha_captura AS DATE) = CAST(CURDATE() AS DATE)")
            ->OrderBy("m.fecha_aplica")
            ->get();
            $cant=0;
        }

        return view('incidencias.reporte_medidas',["estaciones" => $estaciones,"rol"=>$role,"cant"=>$cant,"medidas_tbl"=>$medidas_tbl]);
        //return view('incidencias.captura_medidas',["estaciones" => $estaciones,"medidas_tbl"=>$medidas_tbl]);
    }

    public function consultar_medidas(Request $request){
        if ($request->ajax()) 
        {
            $medidas_tbl= DB::table('medidas_diarias')
            ->select(DB::raw('id,estacion, magna+magna_T2 magna, premium, diesel, fecha_aplica'))
            ->where('estacion','=',$request->estacion)
            ->whereRaw("CAST(fecha_aplica AS DATE) =".$request->fecha)
            ->get();

            return response()->json($medidas_tbl);
        }
    }

        //cambio medias rpt
    public function genReporteMedidas(Request $request){
        $user = \Auth::user();
        $estacion = $request->input('estacion');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');

        $tipo_reporte=$request->input('tipo_rpt');

        $fecha_hoy = Carbon::now(); 
        $fecha_hoy = $fecha_hoy->format('Y-m-d');

        if($fecha_desde=='' or $fecha_hasta==''){
            $fecha_desde=$fecha_hoy;
            $fecha_hasta=$fecha_hoy;
        }   

        if($tipo_reporte=="solo_medidas"){
        
            if($estacion=='*'){
                $sebas=DB::table('medidas_diarias as m')
                    ->select(DB::raw('distinct e.estacion,e.nombre_corto,m.magna,t.diesel'))
                    ->join('medidas_tanques_catalogo as t','m.estacion','=','t.estacion')
                    ->join('estaciones as e','m.estacion','=','e.estacion')
                    ->where('t.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                    ->whereRaw("cast(m.fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                    ->get();
                $pemex=DB::table('medidas_diarias as m')
                    ->select(DB::raw('distinct e.estacion,e.nombre_corto,m.magna,t.diesel'))
                    ->join('medidas_tanques_catalogo as t','m.estacion','=','t.estacion')
                    ->join('estaciones as e','m.estacion','=','e.estacion')
                    ->where('t.descripcion','=','PEMEX')
                    ->whereRaw("cast(m.fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                    ->get();
                $repsol=DB::table('medidas_diarias as m')
                    ->select(DB::raw('distinct e.estacion,e.nombre_corto,m.magna,t.diesel'))
                    ->join('medidas_tanques_catalogo as t','m.estacion','=','t.estacion')
                    ->join('estaciones as e','m.estacion','=','e.estacion')
                    ->where('t.descripcion','=','REPSOL')
                    ->whereRaw("cast(m.fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                    ->get();
            }else{
                $sebas=DB::table('medidas_diarias as m')
                    ->select(DB::raw('distinct e.estacion,e.nombre_corto,m.magna,t.diesel'))
                    ->join('medidas_tanques_catalogo as t','m.estacion','=','t.estacion')
                    ->join('estaciones as e','m.estacion','=','e.estacion')
                    ->where('t.descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                    ->where('m.estacion','=',$estacion)
                    ->whereRaw("cast(m.fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                    ->get();
                $pemex=DB::table('medidas_diarias as m')
                    ->select(DB::raw('distinct e.estacion,e.nombre_corto,m.magna,t.diesel'))
                    ->join('medidas_tanques_catalogo as t','m.estacion','=','t.estacion')
                    ->join('estaciones as e','m.estacion','=','e.estacion')
                    ->where('t.descripcion','=','PEMEX')
                    ->where('m.estacion','=',$estacion)
                    ->whereRaw("cast(m.fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                    ->get();
                $repsol=DB::table('medidas_diarias as m')
                    ->select(DB::raw('distinct e.estacion,e.nombre_corto,m.magna,t.diesel'))
                    ->join('medidas_tanques_catalogo as t','m.estacion','=','t.estacion')
                    ->join('estaciones as e','m.estacion','=','e.estacion')
                    ->where('t.descripcion','=','REPSOL')
                    ->where('m.estacion','=',$estacion)
                    ->whereRaw("cast(m.fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                    ->get();
            }
            
                
        }else{
            
            if($estacion=='*'){
                $sebas=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('distinct *'))
                ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                ->whereRaw("cast(fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                ->get();

                $pemex=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('distinct *'))
                ->where('Tanque_Descripcion','=','PEMEX')
                ->whereRaw("cast(fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                ->get();

                $repsol=DB::table('vw_medidas_vs_anexo_reporte')
                ->select(DB::raw('distinct *'))
                ->where('Tanque_Descripcion','=','REPSOL')
                ->whereRaw("cast(fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                ->get();           
                
            }else{
                $sebas=DB::table('vw_medidas_vs_anexo_reporte')
                ->where('Tanque_Descripcion','=','TRANSPORTES SEBASTOPOL, SA DE CV')
                ->where('estacion','=',$estacion)
                ->whereRaw("cast(fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                ->get();

                $pemex=DB::table('vw_medidas_vs_anexo_reporte')
                ->where('Tanque_Descripcion','=','PEMEX')
                ->where('estacion','=',$estacion)
                ->whereRaw("cast(fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                ->get();

                $repsol=DB::table('vw_medidas_vs_anexo_reporte')
                ->where('Tanque_Descripcion','=','REPSOL')
                ->where('estacion','=',$estacion)
                ->whereRaw("cast(fecha_aplica as date) between cast('$fecha_desde' as date) and cast('$fecha_hasta' as date)")
                ->get();           
                
            }

        }        
        
        $sebas=count($sebas);
        $pemex=count($pemex);
        $repsol=count($repsol);

        return Excel::download(new MedidasExport($estacion,$fecha_desde,$fecha_hasta,$sebas,$pemex,$repsol,$tipo_reporte), 'ReporteMedidas.xlsx');
    }

    public function getCompras_detalle($id_compra){

        $compras_detalle = Compra_Detalle::where('id_compra','=',$id_compra)->get();
        return response()->json([
            'compras_detalle' => $compras_detalle
        ]);

    }

    public function getIncidencias_detalle($id_incidencia){
        
        $detalle_inc = DB::table('detalle_incidencias as det')
            ->select(DB::raw('u.name as usuario,det.fecha_detalle_incidencia,det.comentarios,IFNULL(det.foto_ruta,"") as foto_ruta,det.estatus'))
            ->join('users as u','det.id_usuario','=','u.id')
            ->where('det.id_incidencia',$id_incidencia)
            ->get();

            //si no aparece en detalles
            $validar=DB::table("incidencias_relacion")
            ->where("id_requerimiento","=",$id_incidencia)
            ->get(); 

            foreach($validar as $v){
                $id_inc=$v->id_incidencia;
            }
            if(count($validar)>0){
                $detalle_inc = DB::table('detalle_incidencias as det')
                ->select(DB::raw('u.name as usuario,det.fecha_detalle_incidencia,"Ligado a la incidencia '.$id_inc.'" as comentarios,IFNULL(det.foto_ruta,"") as foto_ruta,det.estatus'))
                ->join('users as u','det.id_usuario','=','u.id')
                ->where('det.id_incidencia','=',$id_inc)
                ->get(); 
            }

        return response()->json(['detalle_inc' => $detalle_inc]);
        
    }

    public function rptordencompra($id_compra){
        $users = User::all();
        //$id_usuario_permiso = $user->id;
        
        $compras = DB::table('compras as c')
            ->select(DB::raw('c.id,c.id_incidencia as id_incidencia,c.id_usuario,c.fecha_compra,c.proveedor,c.facturar_a,c.folio,c.observaciones,c.usuario_autoriza,c.autorizada_sn,c.subtotal,c.iva,c.total,p.razon_social as proveedor_razon_social,com.razon_social as compania_razon_social'))    
            ->join('proveedores as p','c.proveedor','=','p.proveedor')
            ->join('companias as com','c.facturar_a','=','com.id')
            ->where('c.id','=',$id_compra)->get();          

        $compras_detalle = DB::table('compras_detalle')
            ->where('id_compra','=',$id_compra)->get();


        
        //var_dump($compras); 
        foreach($compras as $c){
            $id_inc = $c->id_incidencia;
            //break;
        } 

        //var_dump($id_inc);
                     
        $estacion_aux = DB::table('incidencias')->select('estacion')->where('id','=',$id_inc)->first();
        $estacion = DB::table('estaciones')->select(DB::raw('estacion,nombre_corto'))->where('estacion',$estacion_aux->estacion)->get();    
        
        $pdf = PDF::loadView('incidencias.reportes.orden_compra',compact('compras','compras_detalle','estacion','users'));
        return $pdf->stream();
        
    }
    
    public function reporte_incidencias() 
    {
        //cargo las estaciones a las que el usuario tiene permiso
        //Conseguir usuario identificado
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones')
            ->where('id_usuario_permiso', '=', $id_usuario_permiso)
            ->orderByRaw('cast(estacion as unsigned)')
            ->get();
        return view('incidencias.reporte_incidencias', ["estaciones" => $estaciones]);
    }
    
    public function genReporte(Request $request)
    {        
        $estacion = $request->input('estacion');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta'); 
        
        return Excel::download(new IncidenciasExport($estacion,$fecha_desde,$fecha_hasta), 'ReporteIncidencias.xlsx');
    }

    public function reporte_compras() 
    {        
        return view('incidencias.reporte_compras');
    }
    
    public function genReporteCompras(Request $request){
        
        $tipo_reporte= $request->input('tipo');
        $fecha_desde = $request->input('fecha_desde');
        $fecha_hasta = $request->input('fecha_hasta');        
        
        return Excel::download(new ComprasExport($tipo_reporte,$fecha_desde,$fecha_hasta), 'ReporteCompras.xlsx');
    }

    public function captura_incidencia()
    {
        return view('incidencias.captura_incidencia');
    }
    
    public function captura_detalle_incidencia($id)
    {
        $incidencia = Incidencia::findOrFail($id);    
        $users = User::all();    
        
        return view('incidencias.captura_detalle_incidencia')
            ->with("incidencia", $incidencia)
            ->with("users",$users);        
    }
    
    //este metodo es para capturar el detalle_incidencia
    public function captura_detalleincidencia(Request $request, $id)
    {
        //Conseguir usuario identificado
        $user = \Auth::user();

        $consulta=DB::table("incidencias")
        ->where('id','=', $id)
        ->get();
        foreach($consulta as $cc){
            $usuario_incidencia=$cc->id_usuario;
        }        
        //$usuario_incidencia=$incidencia->id_usuario;

        //Validacion del formulario
        $validate = $this->validate($request, [
            'comentarios' => 'required|string|max:255',
            'estatus' => 'required'
        ]);

        $detalleincidencia = new DetalleIncidencia;

        //$detalleincidencia->id_incidencia=$id_incidencia;
        $detalleincidencia->id_incidencia = $id;
        $detalleincidencia->id_usuario = $user->id;
        //$detalleincidencia->fecha_detalle_incidencia = $request->input('fecha_detalle');
        $fecha_det_inc = Carbon::now();
        $detalleincidencia->fecha_detalle_incidencia = $fecha_det_inc;
        $detalleincidencia->comentarios = $request->input('comentarios');

        //Subir la imagen
        $image_path = $request->file('foto_ruta');
        //var_dump($image_path);
        if ($image_path) {
            //poner nombre unico
            $image_path_name = time() . $image_path->getClientOriginalName();
            
            //definir ruta de guardado
            $ruta= storage_path('app/detalle_incidencias/'.$image_path_name);
            
            //reducir peso de imagen usando el parametro 60 de calidad de imagen 0-100
            Image::make($image_path->getRealPath())->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
              })->save($ruta,60);
              
            //Guardar en la carpeta storage/app/users
            //Storage::disk('detalle_incidencias')->put($image_path_name, File::get($image_path));

            //seteo el nombre de la imagen en el objeto
            $detalleincidencia->foto_ruta = $image_path_name;
            
        }
        
        $detalleincidencia->estatus = $request->input('estatus');
        $detalleincidencia->save();
        
        //despues de guardar el detalle debo actualizar el campo fecha_ultima_actualizacion de la tabla incidencias con la fecha del detalle
        
        $incidencia = Incidencia::findOrFail($id);
        $incidencia->fecha_ultima_actualizacion = $fecha_det_inc;
        $incidencia->update();

            //revisar si la incidencia tiene relacion con un requerimiento
            $validar=DB::table("incidencias_relacion")
                ->where("id_incidencia","=",$id)
                ->get(); 

            foreach($validar as $v){
                $id_req=$v->id_requerimiento;

                if(count($validar)>0){
                    $req=Incidencia::findOrFail($id_req);
                    $req->fecha_ultima_actualizacion = $fecha_det_inc;
                    $req->update();
                }
            }
        
        //despues de Guardar el detalle_incidencia debo checar si el estatus = Terminado y el usuario que captura el detalle es igual
        //al creador de la incidencia, entonces debo hacer update a la incidencia y ponerle el estatus Cerrada
        if($request->input('estatus') == "En Proceso")
        {
            $msj="Detalle Generado Correctamente";   
        }
        elseif ($request->input('estatus') == "Terminado" && $usuario_incidencia == $user->id)  //$incidencia->id_usuario|| $user->role=="admin"
        { 
            $incidencia->estatus_incidencia = "CERRADA";
            $incidencia->fecha_cierre = $fecha_det_inc;
            $incidencia->dias_vida_incidencia = $fecha_det_inc->diffInDays($incidencia->fecha_incidencia);
            $incidencia->update();

            if(count($validar)>0){
                foreach($validar as $v){

                    $id_req=$v->id_requerimiento;
                    
                    $req=Incidencia::findOrFail($id_req);
                    $req->estatus_incidencia = "CERRADA";
                    $req->fecha_cierre = $fecha_det_inc;
                    $req->dias_vida_incidencia = $fecha_det_inc->diffInDays($req->fecha_incidencia);
                    $req->update();
                }
            }

            $msj="Detalle Generado Correctamente";            
        }
        else{
            $msj="La incidencia no se cerró; estatus: ". $request->input('estatus') . 
            ", usuario_incidencia: " . $usuario_incidencia . ", usuario_detalle: " . $user->id;
        }   

        return Redirect::to('incidencias/'.$id)->with('status', $msj);  
    }
    
    public function incidencias_cerradas(Request $request)
    {
        $users = User::all();
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        if ($role == 'admin') {
            $incidencias_cerradas = DB::table('vw_listado_incidencias as i')
            ->select(DB::raw("distinct i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,i.nombre_corto,i.zona,   
                case when ir.id_requerimiento is not null then concat('Ligado ',(select folio from incidencias where id=ir.id_requerimiento)) else '' end as 'Detalle',                            
                i.id_area_estacion,i.area_estacion_descripcion,i.id_equipo,i.equipo_descripcion,i.refaccion_descripcion,i.fecha_incidencia,
                i.asunto,i.descripcion,i.id_area_atencion,i.area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
                i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,i.posicion")) 
            ->join('encargados_areas_atencion as ae', 'i.id_area_atencion', '=', 'ae.id_area_atencion')
            ->leftjoin('incidencias_relacion as ir','i.id','=','ir.id_incidencia')
            ->where('i.estatus_incidencia','=','CERRADA')
            ->where('ae.id_usuario','=',$id_usuario_permiso)
            //->whereRaw('i.id not in (select id_requerimiento from incidencias_relacion)')
            ->get();
        }else{
            $incidencias_cerradas = DB::table('vw_listado_incidencias as i')
            ->select(DB::raw("distinct i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,i.nombre_corto,i.zona,
                case when ir.id_requerimiento is not null then concat('Ligado ',(select folio from incidencias where id=ir.id_requerimiento)) else '' end as 'Detalle',
                i.id_area_estacion,i.area_estacion_descripcion,i.id_equipo,i.equipo_descripcion,i.refaccion_descripcion,i.fecha_incidencia,
                i.asunto,i.descripcion,i.id_area_atencion,i.area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
                i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,i.posicion,ir.id_requerimiento"))
            ->join('usuarios_estaciones as ue', 'i.estacion', '=', 'ue.estacion')
            ->leftjoin('incidencias_relacion as ir','i.id','=','ir.id_incidencia')
            ->where('i.estatus_incidencia','=','CERRADA')
            ->where('ue.id_usuario_permiso','=',$id_usuario_permiso)    
            //->whereRaw('i.id not in (select id_requerimiento from incidencias_relacion)')               
            ->get();
        }
        return view('incidencias.incidencias_cerradas', ["incidencias_cerradas" => $incidencias_cerradas,"users" => $users]);
    }

    public function incidencias_cerradas_ant(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;


        if ($role == 'admin') {
            if ($request) {
                $query = trim($request->get('searchText'));

                $incidencias_cerradas_union = DB::table('incidencias') //incidencias_resp_ant as incidencias
                    //->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,incidencias.folio,incidencias.estacion,incidencias.fecha_incidencia,incidencias.id_area_estacion,incidencias.id_equipo,incidencias.asunto,incidencias.descripcion,incidencias.id_area_atencion,incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
                    ->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,
                    incidencias.folio,incidencias.estacion,estaciones.nombre_corto,estaciones.zona,incidencias.fecha_incidencia,
                    incidencias.id_area_estacion,areas_estacion.descripcion as area_estacion_descripcion,incidencias.id_equipo,
                    equipos.descripcion as equipo_descripcion,refacciones.descripcion as refaccion_descripcion,incidencias.asunto,
                    incidencias.descripcion,incidencias.id_area_atencion,areas_atencion.descripcion as area_atencion_descripcion,
                    incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,
                    incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,
                    areas_atencion.descripcion as area_atencion_descripcion,incidencias.posicion,"" as Detalle'))    
                    ->join('estaciones','incidencias.estacion','=','estaciones.estacion')    
                    ->join('usuarios_estaciones', 'incidencias.estacion', '=', 'usuarios_estaciones.estacion')
                    ->join('encargados_areas_atencion', 'incidencias.id_area_atencion', '=', 'encargados_areas_atencion.id_area_atencion')
                    ->join('areas_atencion','incidencias.id_area_atencion','=','areas_atencion.id')
                    ->join('areas_estacion', function($join){
                        $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                        $join->on('incidencias.estacion','=','areas_estacion.estacion');
                    })    
                    ->join('equipos', function($join){
                        $join->on('incidencias.id_equipo','=','equipos.id');
                        $join->on('incidencias.estacion','=','equipos.estacion');
                    })
                    ->join('refacciones', function($join){
                        $join->on('incidencias.id_refaccion','=','refacciones.id');
                        $join->on('incidencias.estacion','=','refacciones.estacion');
                    })    
                    //->where('incidencias.estacion', 'like', '%' . $query . '%')
                    ->where('incidencias.estatus_incidencia', '=', 'CERRADA')
                    ->where('incidencias.estacion','=','CORPORATIVO')
                    ->where('encargados_areas_atencion.id_usuario','=',$id_usuario_permiso)
                    ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso);  


                $incidencias_cerradas = DB::table('incidencias') //incidencias_resp_ant as incidencias
                    //->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,incidencias.folio,incidencias.estacion,incidencias.fecha_incidencia,incidencias.id_area_estacion,incidencias.id_equipo,incidencias.asunto,incidencias.descripcion,incidencias.id_area_atencion,incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
                    ->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,
                    incidencias.folio,incidencias.estacion,estaciones.nombre_corto,estaciones.zona,incidencias.fecha_incidencia,
                    incidencias.id_area_estacion,areas_estacion.descripcion as area_estacion_descripcion,incidencias.id_equipo,
                    equipos.descripcion as equipo_descripcion,refacciones.descripcion as refaccion_descripcion,incidencias.asunto,
                    incidencias.descripcion,incidencias.id_area_atencion,areas_atencion.descripcion as area_atencion_descripcion,
                    incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,
                    incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,
                    areas_atencion.descripcion as area_atencion_descripcion,incidencias.posicion,"" as Detalle'))    
                    ->join('estaciones','incidencias.estacion','=','estaciones.estacion')    
                    ->join('usuarios_estaciones', 'incidencias.estacion', '=', 'usuarios_estaciones.estacion')
                    ->join('encargados_areas_atencion', 'incidencias.id_area_atencion', '=', 'encargados_areas_atencion.id_area_atencion')
                    ->join('areas_atencion','incidencias.id_area_atencion','=','areas_atencion.id')
                    ->join('areas_estacion', function($join){
                        $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                        $join->on('incidencias.estacion','=','areas_estacion.estacion');
                    })    
                    ->join('equipos', function($join){
                        $join->on('incidencias.id_equipo','=','equipos.id');
                        $join->on('incidencias.estacion','=','equipos.estacion');
                    })
                    ->join('refacciones', function($join){
                        $join->on('incidencias.id_refaccion','=','refacciones.id');
                        $join->on('incidencias.estacion','=','refacciones.estacion');
                        $join->on('incidencias.id_equipo','=','refacciones.id_equipo');
                    })    
                    //->where('incidencias.estacion', 'like', '%' . $query . '%')
                    ->where('incidencias.estatus_incidencia', '=', 'CERRADA')
                    ->where('encargados_areas_atencion.id_usuario','=',$id_usuario_permiso)
                    ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso)  
                    ->where('incidencias.estacion','<>','CORPORATIVO')  
                    ->union($incidencias_cerradas_union    
                    ->orderBy('incidencias.estacion')
                    ->orderBy('incidencias.fecha_incidencia')
                    ->orderBy('incidencias.prioridad'))
                    //->paginate(20);
                    ->get();
                $users = User::all();
                return view('incidencias.incidencias_cerradas_ant', ["incidencias_cerradas" => $incidencias_cerradas, "searchText" => $query, "users" => $users]);
            }
        } 
        /*else {
            if ($request) {
                $query = trim($request->get('searchText'));

                $incidencias_cerradas_union = DB::table('incidencias')
                    //->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,incidencias.folio,incidencias.estacion,incidencias.fecha_incidencia,incidencias.id_area_estacion,incidencias.id_equipo,incidencias.asunto,incidencias.descripcion,incidencias.id_area_atencion,incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
                    ->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,
                        incidencias.folio,incidencias.estacion,estaciones.nombre_corto,estaciones.zona,incidencias.fecha_incidencia,
                        incidencias.id_area_estacion,areas_estacion.descripcion as area_estacion_descripcion,incidencias.id_equipo,
                        equipos.descripcion as equipo_descripcion,refacciones.descripcion as refaccion_descripcion,incidencias.asunto,
                        incidencias.descripcion,incidencias.id_area_atencion,areas_atencion.descripcion as area_atencion_descripcion,
                        incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,
                        incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,
                        areas_atencion.descripcion as area_atencion_descripcion,incidencias.posicion,"" as Detalle'))    
                    ->join('estaciones','incidencias.estacion','=','estaciones.estacion')    
                    ->join('usuarios_estaciones', 'incidencias.estacion', '=', 'usuarios_estaciones.estacion')
                    ->join('areas_atencion','incidencias.id_area_atencion','=','areas_atencion.id')    
                    ->join('areas_estacion', function($join){
                        $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                        $join->on('incidencias.estacion','=','areas_estacion.estacion');
                    })    
                    ->join('equipos', function($join){
                        $join->on('incidencias.id_equipo','=','equipos.id');
                        $join->on('incidencias.estacion','=','equipos.estacion');
                    })
                    ->join('refacciones', function($join){
                        $join->on('incidencias.id_refaccion','=','refacciones.id');
                        $join->on('incidencias.estacion','=','refacciones.estacion');
                    })    
                    ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso)
                    //->where('incidencias.estatus_incidencia', 'like', '%' . $query . '%')
                    ->where('incidencias.estatus_incidencia', '=', 'CERRADA')
                    ->where('incidencias.estacion','=','CORPORATIVO');

                $incidencias_cerradas = DB::table('incidencias')
                    //->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,incidencias.folio,incidencias.estacion,incidencias.fecha_incidencia,incidencias.id_area_estacion,incidencias.id_equipo,incidencias.asunto,incidencias.descripcion,incidencias.id_area_atencion,incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
                    ->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,
                    incidencias.folio,incidencias.estacion,estaciones.nombre_corto,estaciones.zona,incidencias.fecha_incidencia,
                    incidencias.id_area_estacion,areas_estacion.descripcion as area_estacion_descripcion,incidencias.id_equipo,
                    equipos.descripcion as equipo_descripcion,refacciones.descripcion as refaccion_descripcion,incidencias.asunto,
                    incidencias.descripcion,incidencias.id_area_atencion,areas_atencion.descripcion as area_atencion_descripcion,
                    incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,
                    incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,
                    areas_atencion.descripcion as area_atencion_descripcion,incidencias.posicion,"" as Detalle'))    
                    ->join('estaciones','incidencias.estacion','=','estaciones.estacion')    
                    ->join('usuarios_estaciones', 'incidencias.estacion', '=', 'usuarios_estaciones.estacion')
                    ->join('areas_atencion','incidencias.id_area_atencion','=','areas_atencion.id')    
                    ->join('areas_estacion', function($join){
                        $join->on('incidencias.id_area_estacion','=','areas_estacion.id');
                        $join->on('incidencias.estacion','=','areas_estacion.estacion');
                    })    
                    ->join('equipos', function($join){
                        $join->on('incidencias.id_equipo','=','equipos.id');
                        $join->on('incidencias.estacion','=','equipos.estacion');
                    })
                    ->join('refacciones', function($join){
                        $join->on('incidencias.id_refaccion','=','refacciones.id');
                        $join->on('incidencias.estacion','=','refacciones.estacion');
                        $join->on('incidencias.id_equipo','=','refacciones.id_equipo');
                    })    
                    ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso)
                    //->where('incidencias.estatus_incidencia', 'like', '%' . $query . '%')
                    ->where('incidencias.estatus_incidencia', '=', 'CERRADA')
                    ->where('incidencias.estacion','<>','CORPORATIVO')
                    ->union($incidencias_cerradas_union    
                    ->orderBy('incidencias.estacion')
                    ->orderBy('incidencias.fecha_incidencia')
                    ->orderBy('incidencias.prioridad'))
                    //->paginate(20);
                    ->get();    
                $users = User::all();
                return view('incidencias.incidencias_cerradas', ["incidencias_cerradas" => $incidencias_cerradas, "searchText" => $query, "users" => $users]);
            }
        }*/
    }

    //Nuevo Listado de incidencias
    public function listado_incidencias(Request $request)
    {
        $users = User::all();
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        if ($role == 'admin') {
            $incidencias = DB::table('vw_listado_incidencias as i')
            ->select(DB::raw("case when ir.id_requerimiento is not null then concat('Ligado ',(select folio from incidencias where id=ir.id_requerimiento)) else '' end as 'Detalle',
                i.Detalle as estatus,
                i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,i.nombre_corto,i.zona,i.cantidad,
                i.id_area_estacion,
                i.id_equipo,
                case when i.posicion=0 then (select descripcion from inventario_areas where id=i.id_area_estacion) else i.area_estacion_descripcion end as area_estacion_descripcion,
                case when i.posicion=0 then (select descripcion from inventario_subareas where id=i.id_refaccion) else i.refaccion_descripcion end as refaccion_descripcion,
                case when i.posicion=0 then (select descripcion from inventario_equipos where id=i.id_equipo) else i.equipo_descripcion end as equipo_descripcion,
                i.fecha_incidencia,
                i.asunto,i.descripcion,i.id_area_atencion,i.area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
                i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,i.posicion,ir.id_requerimiento")) 
            ->join('encargados_areas_atencion as ae', 'i.id_area_atencion', '=', 'ae.id_area_atencion')
            ->leftjoin('incidencias_relacion as ir','i.id','=','ir.id_incidencia')
            ->where('i.estatus_incidencia','=','ABIERTA')
            ->where('ae.id_usuario','=',$id_usuario_permiso)
            ->whereRaw('i.id not in (select id_requerimiento from incidencias_relacion)')
            ->get();
        }else{
            $incidencias = DB::table('vw_listado_incidencias as i')
            ->select(DB::raw("case when ir.id_requerimiento is not null then concat('Ligado ',(select folio from incidencias where id=ir.id_requerimiento)) else '' end as 'Detalle',
                i.Detalle as estatus,
                i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,i.nombre_corto,i.zona,i.cantidad,
                i.id_area_estacion,
                i.id_equipo,
                case when i.posicion=0 then (select descripcion from inventario_areas where id=i.id_area_estacion) else i.area_estacion_descripcion end as area_estacion_descripcion,
                case when i.posicion=0 then (select descripcion from inventario_subareas where id=i.id_refaccion) else i.refaccion_descripcion end as refaccion_descripcion,
                case when i.posicion=0 then (select descripcion from inventario_equipos where id=i.id_equipo) else i.equipo_descripcion end as equipo_descripcion,
                i.fecha_incidencia,
                i.asunto,i.descripcion,i.id_area_atencion,i.area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
                i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,i.posicion,ir.id_requerimiento"))
            ->join('usuarios_estaciones as ue', 'i.estacion', '=', 'ue.estacion')
            ->leftjoin('incidencias_relacion as ir','i.id','=','ir.id_incidencia')
            ->where('i.estatus_incidencia','=','ABIERTA')
            ->where('ue.id_usuario_permiso','=',$id_usuario_permiso)    
            ->whereRaw('i.id not in (select id_requerimiento from incidencias_relacion)')               
            ->get();
        }
        return view('incidencias.index', ["incidencias" => $incidencias, "users" => $users, "role"=>$role]);
    }
    
        
    //metodo para cargar solo incidencias por una determinada estacion desde el clic de home.blade de la grafica circular
    public function incidenciasxestacion(Request $request)
    {
        $user = \Auth::user();
        $role = $user->role;
        $id_usuario_permiso = $user->id;
        $estaciones=$request->estacion;//************************************** */
                
        $pagina=$request->ruta;
        if($pagina=="us_grafico")  {
           $condicionid_usuario= 'i.id_usuario';
        }else{
            $condicionid_usuario='ae.id_usuario';
        }

        $incidencias = DB::table('vw_listado_incidencias as i')
            ->select(DB::raw("distinct case when ir.id_requerimiento is not null then concat('Ligado ',(select folio from incidencias where id=ir.id_requerimiento)) else '' end as 'Detalle',
                i.Detalle as estatus,
                i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,i.nombre_corto,i.zona,i.cantidad,
                i.id_area_estacion,
                i.id_equipo,
                case when i.posicion=0 then (select descripcion from inventario_areas where id=i.id_area_estacion) else i.area_estacion_descripcion end as area_estacion_descripcion,
                case when i.posicion=0 then (select descripcion from inventario_subareas where id=i.id_refaccion) else i.refaccion_descripcion end as refaccion_descripcion,
                case when i.posicion=0 then (select descripcion from inventario_equipos where id=i.id_equipo) else i.equipo_descripcion end as equipo_descripcion,
                i.fecha_incidencia,
                i.asunto,i.descripcion,i.id_area_atencion,i.area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
                i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,i.posicion,ir.id_requerimiento")) 
            ->join('encargados_areas_atencion as ae', 'i.id_area_atencion', '=', 'ae.id_area_atencion')
            ->leftjoin('incidencias_relacion as ir','i.id','=','ir.id_incidencia')
            ->where('i.estatus_incidencia','=','ABIERTA')
            //->where('ae.id_usuario','=',$id_usuario_permiso)
            //condicion dependiendo de que pagina venga la peticion (us_grafico) o (home)
            ->where($condicionid_usuario, '=', $id_usuario_permiso)   
            ->where('i.estacion','=',$estaciones)
            ->whereRaw('i.id not in (select id_requerimiento from incidencias_relacion)')
            ->get();
            $users = User::all();
            return view('incidencias.index', ["incidencias" => $incidencias,  "users" => $users, "role"=>$role]);
            //return view('incidencias.incidencias_x_estacion', ["incidencias" => $incidencias,  "users" => $users]);
    }
        // if ($role == 'admin') {
        //     if ($request) {
        //         //$query = trim($request->get('searchText'));
        //         $incidencias = DB::table('incidencias as i')
        //             //->select(DB::raw('i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,i.fecha_incidencia,i.id_area_estacion,i.id_equipo,i.asunto,i.descripcion,i.id_area_atencion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
        //             ->select(DB::raw("distinct case when det.estatus = 'Terminado' then 'SOLVENTADO' ELSE 'En proceso' end as 'estatus','' as Detalle,
        //                 i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,e.nombre_corto,e.zona,i.fecha_incidencia,
        //                 i.id_area_estacion,ae.descripcion as area_estacion_descripcion,i.id_equipo,eq.descripcion as equipo_descripcion,r.descripcion as refaccion_descripcion,
        //                 i.asunto,i.descripcion,i.id_area_atencion,aa.descripcion as area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
        //                 i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,aa.descripcion as area_atencion_descripcion,i.posicion,'' as id_requerimiento"))    
        //             ->join('estaciones as e','i.estacion','=','e.estacion')    
        //             ->join('usuarios_estaciones', 'i.estacion', '=', 'usuarios_estaciones.estacion')
        //             ->join('encargados_areas_atencion', 'i.id_area_atencion', '=', 'encargados_areas_atencion.id_area_atencion')
        //             ->join('areas_atencion as aa','i.id_area_atencion','=','aa.id')
        //             ->join('areas_estacion as ae', function($join){
        //                 $join->on('i.id_area_estacion','=','ae.id');
        //                 $join->on('i.estacion','=','ae.estacion');
        //             })    
        //             ->join('equipos as eq', function($join){
        //                 $join->on('i.id_equipo','=','eq.id');
        //                 $join->on('i.estacion','=','eq.estacion');
        //             })
        //             ->join('refacciones as r', function($join) use ($condicion_idEquipo1, $condicion_idEquipo2,$igual){
        //                 $join->on('i.id_refaccion','=','r.id');
        //                 $join->on('i.estacion','=','r.estacion');
                        
        //                // $condicion_idEquipo; **CORPORATIVO
        //                     $join->on($condicion_idEquipo1,$igual,$condicion_idEquipo2);                                               
                       
        //             })
        //             ->leftjoin('detalle_incidencias as det','i.id','=','det.id_incidencia')    
        //             //->where('i.estacion', 'like', '%' . $query . '%')
        //             ->where('i.estatus_incidencia', '=', 'ABIERTA')
        //             ->where('encargados_areas_atencion.id_usuario','=',$id_usuario_permiso)
        //             ->where('i.estacion','=',$estaciones)
                    
        //             //condicion dependiendo de que pagina venga la peticion (us_grafico) o (home)
        //             ->where($condicionid_usuario, '=', $id_usuario_permiso)     
                         
        //             ->orderBy('i.estacion')
        //             ->orderBy('i.fecha_incidencia')
        //             ->orderBy('i.prioridad')
        //             ->get();  
        //             //->paginate(20);
        //         $users = User::all();
        //         return view('incidencias.incidencias_x_estacion', ["incidencias" => $incidencias,  "users" => $users]);
        //     }
        // }
        
        
    
    // //este metodo carga el listado de incidencias en la vista index.blade
    // public function index(Request $request)
    // {
    //     //Conseguir usuario identificado
    //     $user = \Auth::user();
    //     $role = $user->role;
    //     $id_usuario_permiso = $user->id;

    //     if ($role == 'admin') {
    //         if ($request) {
    //             $query = trim($request->get('searchText'));

    //             //union 
    //             $incidenciasCORP = DB::table('incidencias as i')
    //             //->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,incidencias.folio,incidencias.estacion,incidencias.fecha_incidencia,incidencias.id_area_estacion,incidencias.id_equipo,incidencias.asunto,incidencias.descripcion,incidencias.id_area_atencion,incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
    //             ->select(DB::raw("case when det.estatus = 'Terminado' then 'SOLVENTADO' ELSE 'En proceso' end as 'estatus','' as Detalle,
    //                 i.id,i.id_usuario,i.created_at,
    //                 i.updated_at,i.folio,i.estacion,e.nombre_corto,e.zona,i.fecha_incidencia,i.id_area_estacion,
    //                 ae.descripcion as area_estacion_descripcion,i.id_equipo,eq.descripcion as equipo_descripcion,r.descripcion as refaccion_descripcion,
    //                 i.asunto,i.descripcion,i.id_area_atencion,aa.descripcion as area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
    //                 i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,aa.descripcion as area_atencion_descripcion,i.posicion,'' as id_requerimiento"))    
    //             ->join('estaciones as e','i.estacion','=','e.estacion')    
    //             ->join('usuarios_estaciones', 'i.estacion', '=', 'usuarios_estaciones.estacion')
    //             ->join('encargados_areas_atencion', 'i.id_area_atencion', '=', 'encargados_areas_atencion.id_area_atencion')
    //             ->join('areas_atencion as aa','i.id_area_atencion','=','aa.id')
    //             ->join('areas_estacion as ae', function($join){
    //                 $join->on('i.id_area_estacion','=','ae.id');
    //                 $join->on('i.estacion','=','ae.estacion');
    //             })    
    //             ->join('equipos as eq', function($join){
    //                 $join->on('i.id_equipo','=','eq.id');
    //                 $join->on('i.estacion','=','eq.estacion');
    //             })
    //             ->join('refacciones as r', function($join){
    //                 $join->on('i.id_refaccion','=','r.id');
    //                 $join->on('i.estacion','=','r.estacion');                        
    //             })   
    //             ->leftjoin('detalle_incidencias as det','i.id','=','det.id_incidencia') 
    //             ->where('i.estatus_incidencia', '=', 'ABIERTA')
    //             ->where('encargados_areas_atencion.id_usuario','=',$id_usuario_permiso)
    //             ->where('i.estacion','=','CORPORATIVO')
    //             ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso);


    //             $incidencias = DB::table('incidencias as i')
    //                 ->select(DB::raw("case when det.estatus = 'Terminado' then 'SOLVENTADO' ELSE 'En proceso' end as 'estatus','' as Detalle,
    //                     i.id,i.id_usuario,i.created_at,
    //                     i.updated_at,i.folio,i.estacion,e.nombre_corto,e.zona,i.fecha_incidencia,i.id_area_estacion,
    //                     ae.descripcion as area_estacion_descripcion,i.id_equipo,eq.descripcion as equipo_descripcion,r.descripcion as refaccion_descripcion,
    //                     i.asunto,i.descripcion,i.id_area_atencion,aa.descripcion as area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,i.prioridad,
    //                     i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,aa.descripcion as area_atencion_descripcion,i.posicion,'' as id_requerimiento"))
    //                 ->join('estaciones as e','i.estacion','=','e.estacion')    
    //                 ->join('usuarios_estaciones', 'i.estacion', '=', 'usuarios_estaciones.estacion')
    //                 ->join('encargados_areas_atencion', 'i.id_area_atencion', '=', 'encargados_areas_atencion.id_area_atencion')
    //                 ->join('areas_atencion as aa','i.id_area_atencion','=','aa.id')
    //                 ->join('areas_estacion as ae', function($join){
    //                     $join->on('i.id_area_estacion','=','ae.id');
    //                     $join->on('i.estacion','=','ae.estacion');
    //                 })    
    //                 ->join('equipos as eq', function($join){
    //                     $join->on('i.id_equipo','=','eq.id');
    //                     $join->on('i.estacion','=','eq.estacion');
    //                 })
    //                 ->join('refacciones as r', function($join) {
    //                     $join->on('i.id_refaccion','=','r.id');
    //                     $join->on('i.estacion','=','r.estacion');                        
    //                     $join->on('i.id_equipo','=','r.id_equipo');
    //                 })
    //                 //->where('i.estacion', 'like', '%' . $query . '%')
    //                 ->leftjoin('detalle_incidencias as det','i.id','=','det.id_incidencia') 
    //                 ->where('i.estatus_incidencia', '=', 'ABIERTA')
    //                 ->where('encargados_areas_atencion.id_usuario','=',$id_usuario_permiso)
    //                 ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso)     
    //                 ->where ('i.estacion','<>','CORPORATIVO') 
    //                 ->union
    //                 (
    //                     $incidenciasCORP
    //                     ->orderByRaw('i.fecha_incidencia desc')
    //                 )
    //                 ->get();  
    //                 //->paginate(20);
    //             $users = User::all();
    //             return view('incidencias.index', ["incidencias" => $incidencias, "searchText" => $query, "users" => $users]);
    //         }
    //     } else {
    //         if ($request) {
    //             $query = trim($request->get('searchText'));


    //                 //union 
    //                 $incidenciasCORP = DB::table('incidencias as i')
    //                 //->select(DB::raw('incidencias.id,incidencias.id_usuario,incidencias.created_at,incidencias.updated_at,incidencias.folio,incidencias.estacion,incidencias.fecha_incidencia,incidencias.id_area_estacion,incidencias.id_equipo,incidencias.asunto,incidencias.descripcion,incidencias.id_area_atencion,incidencias.foto_ruta,incidencias.estatus_incidencia,incidencias.tipo_solicitud,incidencias.prioridad,incidencias.fecha_ultima_actualizacion,incidencias.fecha_cierre,incidencias.dias_vida_incidencia,areas_atencion.descripcion as area_atencion_descripcion'))
    //                 ->select(DB::raw("case when det.estatus = 'Terminado' then 'SOLVENTADO' ELSE 'En proceso' end as 'estatus','' as Detalle,
    //                     i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,e.nombre_corto,e.zona,i.fecha_incidencia,i.id_area_estacion,
    //                     ae.descripcion as area_estacion_descripcion,i.id_equipo,eq.descripcion as equipo_descripcion,r.descripcion as refaccion_descripcion,
    //                     i.asunto,i.descripcion,i.id_area_atencion,aa.descripcion as area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,i.tipo_solicitud,
    //                     i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,aa.descripcion as area_atencion_descripcion,i.posicion,'' as id_requerimiento"))    
    //                 ->join('estaciones as e','i.estacion','=','e.estacion')    
    //                 ->join('usuarios_estaciones', 'i.estacion', '=', 'usuarios_estaciones.estacion')
    //                 //->join('encargados_areas_atencion', 'i.id_area_atencion', '=', 'encargados_areas_atencion.id_area_atencion')
    //                 ->join('areas_atencion as aa','i.id_area_atencion','=','aa.id')
    //                 ->join('areas_estacion as ae', function($join){
    //                     $join->on('i.id_area_estacion','=','ae.id');
    //                     $join->on('i.estacion','=','ae.estacion');
    //                 })    
    //                 ->join('equipos as eq', function($join){
    //                     $join->on('i.id_equipo','=','eq.id');
    //                     $join->on('i.estacion','=','eq.estacion');
    //                 })
    //                 ->join('refacciones as r', function($join){
    //                     $join->on('i.id_refaccion','=','r.id');
    //                     $join->on('i.estacion','=','r.estacion');                        
    //                 })    
    //                 ->leftjoin('detalle_incidencias as det','i.id','=','det.id_incidencia')
    //                 ->where('i.estatus_incidencia', '=', 'ABIERTA')
    //                 //->where('encargados_areas_atencion.id_usuario','=',$id_usuario_permiso)
    //                 ->where('i.estacion','=','CORPORATIVO')
    //                 ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso);
                   
    //                 $incidencias = DB::table('incidencias as i')
    //                 ->select(DB::raw("case when det.estatus = 'Terminado' then 'SOLVENTADO' ELSE 'En proceso' end as 'estatus','' as Detalle,
    //                     i.id,i.id_usuario,i.created_at,i.updated_at,i.folio,i.estacion,e.nombre_corto,e.zona,i.fecha_incidencia,
    //                     i.id_area_estacion,ae.descripcion as area_estacion_descripcion,i.id_equipo,eq.descripcion as equipo_descripcion, r.descripcion as refaccion_descripcion,
    //                     i.asunto,i.descripcion,i.id_area_atencion,aa.descripcion as area_atencion_descripcion,i.foto_ruta,i.estatus_incidencia,
    //                     i.tipo_solicitud,i.prioridad,i.fecha_ultima_actualizacion,i.fecha_cierre,i.dias_vida_incidencia,aa.descripcion as area_atencion_descripcion,i.posicion,'' as id_requerimiento"))
    //                 ->join('estaciones as e','i.estacion','=','e.estacion')    
    //                 ->join('usuarios_estaciones', 'i.estacion', '=', 'usuarios_estaciones.estacion')
    //                 ->join('areas_atencion as aa','i.id_area_atencion','=','aa.id')
    //                 ->join('areas_estacion as ae', function($join){
    //                     $join->on('i.id_area_estacion','=','ae.id');
    //                     $join->on('i.estacion','=','ae.estacion');
    //                 })    
    //                 ->join('equipos as eq', function($join){
    //                     $join->on('i.id_equipo','=','eq.id');
    //                     $join->on('i.estacion','=','eq.estacion');
    //                 })
    //                 ->join('refacciones as r', function($join){
    //                     $join->on('i.id_refaccion','=','r.id');
    //                     $join->on('i.estacion','=','r.estacion');
    //                     $join->on('i.id_equipo','=','r.id_equipo');
    //                 })
    //                 ->leftjoin('detalle_incidencias as det','i.id','=','det.id_incidencia')
    //                 ->where('usuarios_estaciones.id_usuario_permiso', '=', $id_usuario_permiso)   
    //                 ->where('i.estacion','<>','CORPORATIVO')   
    //                 ->where('i.estatus_incidencia', '=', 'ABIERTA')
    //                 ->union($incidenciasCORP
    //                  //->orderBy('i.estacion')
    //                  ->orderBy('i.fecha_incidencia')
    //                  ->orderBy('i.prioridad'))
    //                 ->get();   
    //             $users = User::all();
    //             return view('incidencias.index', ["incidencias" => $incidencias, "searchText" => $query, "users" => $users]);
    //         }
    //     }  
    // }

    //este metodo carga la vista captura_incidencia.blade
    public function create()
    {
        //cargo las estaciones a las que el usuario tiene permiso
        //Conseguir usuario identificado
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        $estaciones = DB::table('usuarios_estaciones')
            ->where('id_usuario_permiso', '=', $id_usuario_permiso)
            ->whereRaw('estacion not in ("H. INDIGO")')
            ->orderByRaw('cast(estacion as unsigned)')
            ->get();
        
                
        // $refacciones_estacion = DB::table('refacciones2 as r')
        //     ->select(DB::raw('r.id,r.descripcion,a.descripcion as area,e.descripcion as Equipo,r.prioridad'))    
        //     ->join('equipo as e','r.id_equipo','=','e.id')
        //     ->join('area as a', 'e.id_area_estacion','=','a.id')
        //     ->where('r.id_equipo','<','21')
        //     ->orderBy('r.id')
        //     ->get(); 

        $refacciones_estacion = DB::table('refacciones2 as r')
        ->select(DB::raw('r.id,r.descripcion,c.descripcion as catalogo,r.prioridad'))    
        ->join('refacciones_catalogo as c','r.id_catalogo','=','c.id')
        ->where('r.id_catalogo','<','90')
        ->orderBy('r.id')
        ->get(); 

        return view('incidencias.captura_incidencia', ["estaciones" => $estaciones, "refacciones_estacion" => $refacciones_estacion]);
        
    }
    
    //este metodo es para autorizar la compra cambiando de NO a SI en la columna autorizada_sn de la tabla compras
    public function aut_compra(Request $request){
        //Conseguir usuario identificado
        $user = \Auth::user(); 
        
        $compras = Compras::findOrFail($request->id_compra);
        $compras->autorizada_sn = "SI";
        $compras->usuario_autoriza = $user->id;
        $compras->update();
        //return redirect()->action('IncidenciasController@show',['id' => $request->id_incidencia])->with('status', 'Compra autorizada correctamente');
        return redirect()->action('IncidenciasController@show',$request->id_incidencia)->with('status', 'Compra autorizada correctamente');
    }

    //este metodo es para denegar la compra cambiando de SI a NO en la columna autorizada_sn de la tabla compras
    public function denegar_compra(Request $request){
        //Conseguir usuario identificado
        $user = \Auth::user(); 
        
        $compras = Compras::findOrFail($request->den_id_compra);
        $compras->autorizada_sn = "NO";
        $compras->usuario_autoriza = $user->id;
        $compras->update();
        
        return redirect()->action('IncidenciasController@show',$request->den_id_incidencia)->with('status', 'Compra denegada correctamente');
    }

    //este metodo es para cerrar la compra cambiando de NO a SI en la columna cerrada_sn de la tabla compras
    public function cerrar_compra(Request $request){
        //Conseguir usuario identificado
        $user = \Auth::user(); 
        
        $compras = Compras::findOrFail($request->cerrar_id_compra);
        $compras->cerrada_sn = "SI";
        
        $compras->update();
        return redirect()->action('IncidenciasController@show',$request->cerrar_id_incidencia)->with('status', 'Compra cerrada correctamente');
    }

    public function eliminar_compra(Request $request){
        $id_incidencia = $request->del_id_incidencia;
        try {
            DB::beginTransaction();

            //primero debo borrar los detalles de la compra
            $del_detalles = Compra_Detalle::where('id_compra','=',$request->del_id_compra)->delete();

            //ahora si, procedo a borrar la compra
            $del_compra = Compras::find($request->del_id_compra);
            $del_compra->delete();


            DB::commit();

        } catch (\Exception $e) {
            var_dump($e);
            DB::rollBack();
        }
        return redirect()->action('IncidenciasController@show',$id_incidencia)->with('status', 'Compra eliminada correctamente');       
    }

    public function editar_compra(Request $request){
        $compra = Compras::findOrFail($request->edit_id);

        //Conseguir usuario identificado
        $user = \Auth::user();        
        
        //Validacion del formulario
        $id_incidencia = $request->input('edit_id_incidencia');

        try {
            DB::beginTransaction();           
            
            
            $compra->id_usuario = $user->id;                  
            
            $compra->observaciones = $request->input('edit_observaciones');                     

            $compra->subtotal = $request->input('edit_subtotal');
            $compra->iva = $request->input('edit_iva');
            $compra->total = $request->input('edit_total_final');  
            
            $compra->update(); 
            
            //debo eliminar todos los detalles de la compra en la bd para posteriormente guardar los nuevos detalles
            $delete = Compra_Detalle::where('id_compra','=',$request->edit_id)->delete();
            
            //despues de guardar en compras, debo guardar en compras_detalle 
            //ARRAY DE CONTROLES           
            $id_incidencia_detalle = $request->input('edit_id_incidencia_detalle');            
            $cantidad = $request->input('edit_cantidad');
            $unidad = $request->input('edit_unidad');        
            $producto_descripcion = $request->input('edit_producto_descripcion');

            $tipo_cambio = $request->input('edit_tipo_cambio');
            $moneda = $request->input('edit_moneda');

            $precio_unitario = $request->input('edit_precio_unitario');
            $total = $request->input('edit_total');
           
            $cont = 0;
            while($cont < count($cantidad)){
                $compra_detalle = new Compra_Detalle();
                $compra_detalle->id_compra = $compra->id;
                $compra_detalle->id_incidencia = $id_incidencia_detalle[$cont];               
                $compra_detalle->cantidad = $cantidad[$cont];
                $compra_detalle->unidad = $unidad[$cont];
                $compra_detalle->producto_descripcion = $producto_descripcion[$cont];

                $compra_detalle->tipo_cambio = $tipo_cambio[$cont];
                $compra_detalle->moneda = $moneda[$cont];

                $compra_detalle->precio_unitario = $precio_unitario[$cont];
                $compra_detalle->total = $total[$cont];
                
                $compra_detalle->save();
                $cont = $cont + 1;
            }


            DB::commit();

        } catch (\Exception $e) {
            //var_dump($e);
            DB::rollBack();
        }        
        
        //return redirect()->action('IncidenciasController@show',['id' => $id_incidencia])->with('status', 'Compra editada correctamente');
        return redirect()->action('IncidenciasController@show',$id_incidencia)->with('status', 'Compra editada correctamente');


    }
    
    public function alta_compras(Request $request)
    {                
        $user = \Auth::user();        
                
        /*Obtengo el folio*/ 
        $max_folio_db = DB::table('compras')
            ->select(DB::raw('max(convert(folio,unsigned integer)) max_folio '))                
            ->first();
        
        $max_folio = $max_folio_db->max_folio;
        
        if ($max_folio == null){
            $max_folio = 0;
        }        
        $OUTfolio = $max_folio+1;
        
        try {
            DB::beginTransaction();
            $compras = new Compras;
            $id_incidencia = $request->id_incidencia;

            $compras->id_incidencia = $id_incidencia;
            $compras->id_usuario = $user->id;
            //$detalleincidencia->fecha_detalle_incidencia = $request->input('fecha_detalle');
            $fecha_compra = Carbon::now();
            $compras->fecha_compra = $fecha_compra;
            
            $compras->proveedor = $request->input('proveedor'); //Catalogo proveedores
            $compras->facturar_a = $request->input('facturar_a'); //catalogo companias
            
            $compras->folio = $OUTfolio;        
            
            $compras->observaciones = $request->input('observaciones');
            //$compras->usuario_autoriza = 1;//$request->input('usuario_autoriza'); //Catalogo users            
            $compras->autorizada_sn = 'NO';//$request->input('autorizada_sn');
            $compras->cerrada_sn = 'NO';             

            $compras->subtotal = $request->input('subtotal');
            $compras->iva = $request->input('iva');
            $compras->total = $request->input('total_final');  
            
            $compras->save();               
            
            //despues de guardar en compras, debo guardar en compras_detalle 
            //ARRAY DE CONTROLES           
            $id_incidencia_detalle = $request->input('id_incidencia_detalle');            
            $cantidad = $request->input('cantidad');
            $unidad = $request->input('unidad');        
            $producto_descripcion = $request->input('producto_descripcion');

            $tipo_cambio = $request->input('tipo_cambio');
            $moneda = $request->input('moneda');

            $precio_unitario = $request->input('precio_unitario');
            $total = $request->input('total');
           
            $cont = 0;
            while($cont < count($cantidad)){
                $compra_detalle = new Compra_Detalle();
                $compra_detalle->id_compra = $compras->id;
                $compra_detalle->id_incidencia = $id_incidencia_detalle[$cont];               
                $compra_detalle->cantidad = $cantidad[$cont];
                $compra_detalle->unidad = $unidad[$cont];
                $compra_detalle->producto_descripcion = $producto_descripcion[$cont];

                $compra_detalle->tipo_cambio = $tipo_cambio[$cont];
                $compra_detalle->moneda = $moneda[$cont];

                $compra_detalle->precio_unitario = $precio_unitario[$cont];
                $compra_detalle->total = $total[$cont];
                
                $compra_detalle->save();
                $cont = $cont + 1;
            }


            DB::commit();

        } catch (\Exception $e) {
            //var_dump($e);
            DB::rollBack();
        }        
        
        return redirect()->action('IncidenciasController@show',$id_incidencia)->with('status', 'Compra solicitada correctamente');
    }

    //este metodo guarda la incidencia
    public function store(Request $request)
    {
        $user = \Auth::user();
        $id = $user->id;      

            if($request->input('tipo_solicitud')=="incidencia")
            {
                $asunto = $request->input('asunto');
                $descripcion = $request->input('descripcion');
                $prioridad="alta";
                $id_area_estacion=1;
                $id_equipo=40;
                $posicion=null;
                $id_refaccion=178;
                $cantidad = " ";
            }else{   
                $asunto = " ";
                $descripcion = " ";
                $prioridad=$request->input('prioridad');
                $id_area_estacion=$request->input('id_area_estacion');
                $id_equipo=$request->input('id_equipo');
                $posicion=$request->input('posiciones');
                $id_refaccion=$request->input('refacciones');
                $cantidad = $request->input('cant');
            }
            $incidencia = new Incidencia;
            $incidencia->id_usuario = $id;
            $incidencia->folio = $request->input('folio');
            $incidencia->estacion = $request->input('estacion');
            $incidencia->tipo_solicitud = $request->input('tipo_solicitud');
            $incidencia->fecha_incidencia = Carbon::now();
            $incidencia->id_area_atencion = $request->input('id_area_atencion');
            $incidencia->estatus_incidencia = 'ABIERTA';
            $incidencia->asunto = $asunto;
            $incidencia->descripcion = $descripcion;
            $incidencia->prioridad = $prioridad;
            $incidencia->id_area_estacion = $id_area_estacion;
            $incidencia->id_equipo = $id_equipo;
            $incidencia->posicion = $posicion;
            $incidencia->cantidad = $cantidad;
            $incidencia->id_refaccion = $id_refaccion;        

            //$incidencia->producto = $request->input('producto');
            //Subir la imagen
            $image_path = $request->file('foto_ruta');
            //var_dump($image_path);
            if ($image_path) {
                //poner nombre unico                       
                $image_path_name = time().$image_path->getClientOriginalName();            
                //definir ruta de guardado
                $ruta= storage_path('app/incidencias/'.$image_path_name);            
                //reducir peso de imagen usando el parametro 60 de calidad de imagen 0-100
                //Image::make($image_path->getRealPath())->save($ruta,60);
                
                Image::make($image_path->getRealPath())->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($ruta,60);
                //Guardar en la carpeta storage/app/incidencias
                //Storage::disk('incidencias')->put($image_path_name, File::get($image_path));   
                //seteo el nombre de la imagen en el objeto
                $incidencia->foto_ruta = $image_path_name;
            }
            
            $incidencia->save();

        return Redirect::to('listado_incidencias')->with(['message' => 'Incidencia Generada Correctamente']);

        // $numero_serie=$request->input('tserie');
        
        // if ($numero_serie==''){
        //     $numero_serie='';   //No capturado.
        // }else{
        //     $numero_serie=", num. de serie: " .$request->input('tserie');
        // }

        //Validacion del formulario ***El campo asunto es obligatorio***
        // $validate = $this->validate($request, [
        //     'folio' => 'required|string|max:10',
        //     'asunto' => 'required|string|max:50',
        //     'descripcion' => 'required|string|max:255'
        // ]);
        //$incidencia->descripcion = $request->input('descripcion').$numero_serie ;
    }
    
    //este metodo llena la vista show.blade con los detalles_incidencia
    public function show($id)
    {        
        //Conseguir usuario identificado
        $user = \Auth::user();
        $id_usuario_permiso = $user->id;
        
        $detalles = DB::table('detalle_incidencias')
            ->where('id_incidencia', '=', $id)
            ->paginate(20);
        $log = IncidenciaLog::where("id_incidencia", $id)->paginate(20);
        $users = User::all();
        $proveedores = DB::table('proveedores')->get();
        $companias = DB::table('companias')->get();


        /*$compras_detalle = DB::table('compras_detalle')
            ->select('id_compra')
            ->where('id_incidencia', '=', $id)
            ->get();

        $id_compra = $id_compra_aux->id_compra;    
        
        $compras = DB::table('compras')            
            ->where('id', '=', $id_compra)
            ->paginate(20);*/

        $compras = DB::table('compras_detalle as cd')
                    ->select(DB::raw('c.*'))
                    ->join('compras as c', function($join){
                        $join->on('cd.id_compra','=','c.id');
                    })
                    ->where('cd.id_incidencia','=',$id)
                    ->paginate(20);   
                      
        //var_dump($compras);
        $permisos = DB::table('permisos')
            ->where('usuario_permiso','=',$id_usuario_permiso)
            ->get();

        $estacion_aux = DB::table('incidencias')->select('estacion')->where('id','=',$id)->first();
        $estacion = $estacion_aux->estacion;

        $comp_det = Compra_Detalle::select('id_incidencia')->get()->toArray();

        // $compras_incidencias = DB::table('incidencias as i')
        // ->select(DB::raw('concat(i.id,"-",i.estacion,"-",r.descripcion) as incidencia,i.id'))    
        // ->join('refacciones2 as r', 'i.id_refaccion','=','r.id')
        // ->where('i.estatus_incidencia', '=', 'ABIERTA')
        // ->where('i.estacion','=',$estacion)
        // ->where('i.id_refaccion','<>','178')
        // ->whereNotIn('i.id',$comp_det);

        // $compras_inventario = DB::table('incidencias as i')
        // ->select(DB::raw('concat(i.id,"-",i.estacion,"-",eq.descripcion) as incidencia,i.id'))    
        // ->rightjoin('inventario_equipos as eq', 'i.id_equipo','=','eq.id')
        // ->where('i.estatus_incidencia', '=', 'ABIERTA')
        // ->where('i.estacion','=',$estacion)
        // ->whereNotIn('i.id',$comp_det);

        // $compras_incidencias=$compras_incidencias->Union($compras_inventario)->get();

            $compras_incidencias = DB::table('incidencias as i')
            ->select(DB::raw('concat(i.id,"-",i.estacion,"-",case when i.posicion=0 then (select descripcion from inventario_equipos where id=i.id_equipo) else r.descripcion end ) as incidencia,i.id'))    
            ->join('refacciones2 as r', 'i.id_refaccion','=','r.id')
            ->where('i.estatus_incidencia', '=', 'ABIERTA')
            ->where('i.estacion','=',$estacion)
            ->where('i.id_refaccion','<>','178')
            ->whereNotIn('i.id',$comp_det)
            ->get();

        return view("incidencias.show", ["detalles" => $detalles, "id_incidencia" => $id, "log" => $log, "users" => $users,"proveedores" => $proveedores,"companias" => $companias,"compras" => $compras,"permisos" => $permisos,"compras_incidencias" => $compras_incidencias]);
    }

    // public function show_ant($id)
    // {        
    //     //Conseguir usuario identificado
    //     $user = \Auth::user();
    //     $id_usuario_permiso = $user->id;
        
    //     $detalles = DB::table('detalle_incidencias')
    //         ->where('id_incidencia', '=', $id)
    //         ->paginate(20);
    //     $log = IncidenciaLog::where("id_incidencia", $id)->paginate(20);
    //     $users = User::all();
    //     $proveedores = DB::table('proveedores')->get();
    //     $companias = DB::table('companias')->get();


    //     /*$compras_detalle = DB::table('compras_detalle')
    //         ->select('id_compra')
    //         ->where('id_incidencia', '=', $id)
    //         ->get();

    //     $id_compra = $id_compra_aux->id_compra;    
        
    //     $compras = DB::table('compras')            
    //         ->where('id', '=', $id_compra)
    //         ->paginate(20);*/

    //     $compras = DB::table('compras_detalle as cd')
    //                 ->select(DB::raw('c.*'))
    //                 ->join('compras as c', function($join){
    //                     $join->on('cd.id_compra','=','c.id');
    //                 })
    //                 ->where('cd.id_incidencia','=',$id)
    //                 ->paginate(20);   
                      
    //     //var_dump($compras);
    //     $permisos = DB::table('permisos')
    //         ->where('usuario_permiso','=',$id_usuario_permiso)
    //         ->get();

    //     $estacion_aux = DB::table('incidencias_resp_ant')->select('estacion')->where('id','=',$id)->first();
    //     $estacion = $estacion_aux->estacion;

    //     $comp_det = Compra_Detalle::select('id_incidencia')->get()->toArray();
          
    //     $compras_incidencias = DB::table('incidencias_resp_ant as i')
    //     ->select(DB::raw('concat(i.id,"-",i.estacion,"-",r.descripcion) as incidencia,i.id'))    
    //         ->join('refacciones as r', function($join){
    //             $join->on('i.id_refaccion','=','r.id');
    //             $join->on('i.estacion','=','r.estacion');
    //             $join->on('i.id_equipo','=','r.id_equipo');
    //         })
    //     ->where('i.estatus_incidencia', '=', 'ABIERTA')
    //     ->where('i.estacion','=',$estacion)
    //     ->whereNotIn('i.id',$comp_det)
    //     ->get();

    //     return view("incidencias.show", ["detalles" => $detalles, "id_incidencia" => $id, "log" => $log, "users" => $users,"proveedores" => $proveedores,"companias" => $companias,"compras" => $compras,"permisos" => $permisos,"compras_incidencias" => $compras_incidencias]);
    // }

    public function edit($id)
    {
        return view("incidencias.edit", ["incidencia" => Incidencia::findOrFail($id)]);
    }
    
    public function updateDetalleIncidencia(Request $request){
        $user = \Auth::user();

        $detalle_inc = DetalleIncidencia::findOrFail($request->id);
        
        $detalle_inc->comentarios = $request->get('comentarios');
        $detalle_inc->estatus = $request->get('estatus');
        
        $id = $detalle_inc->id_incidencia;
        //Subir la imagen
        $image_path = $request->file('foto_ruta');
        if ($image_path) {
            //poner nombre unico
            $image_path_name = time() . $image_path->getClientOriginalName();
            
            //definir ruta de guardado
            $ruta= storage_path('app/detalle_incidencias/'.$image_path_name);
            
            //reducir peso de imagen usando el parametro 60 de calidad de imagen 0-100
            //Image::make($image_path->getRealPath())->save($ruta,60);
            Image::make($image_path->getRealPath())->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
              })->save($ruta,60);

            //Guardar en la carpeta storage/app/users
            //Storage::disk('detalle_incidencias')->put($image_path_name, File::get($image_path));

            //seteo el nombre de la imagen en el objeto
            $detalle_inc->foto_ruta = $image_path_name;
            
        }
        $detalle_inc->update();

        $incidencia = Incidencia::findOrFail($id);

        if($request->get('estatus')=='En Proceso')
        {
            $incidencia->estatus_incidencia="ABIERTA";
            $incidencia->update();
        }else{

            if($incidencia->id_usuario == $user->id)
            {
                $fecha_det_inc = Carbon::now();
                $incidencia->estatus_incidencia="CERRADA";
                $incidencia->fecha_cierre = $fecha_det_inc;
                $incidencia->dias_vida_incidencia = $fecha_det_inc->diffInDays($incidencia->fecha_incidencia);
                $incidencia->update();
            }
            
        }
        return redirect()->action('IncidenciasController@show',$id)->with('status', 'Detalle Actualizado Correctamente');
    }

    public function update(Request $request/*, $id*/)
    {
        $user = \Auth::user();

        if($request->input("relacion")==1)
        {
            $validar=DB::table("incidencias_relacion")
            ->where("id_incidencia","=",$request->id)
            ->get(); 

            foreach($validar as $v){
                $id_tabla=$v->id;
            }
            
            if(count($validar)==0){
                $tbl_relacion= new incidencia_relacion;
                $tbl_relacion->id_incidencia=$request->id;
                $tbl_relacion->id_requerimiento=$request->input("requerimiento");
                $tbl_relacion->id_usuario=$user->id;
                $tbl_relacion->save();
            }else{
                $tbl_update = incidencia_relacion::findOrFail($id_tabla);
                $tbl_update->id_requerimiento=$request->input("requerimiento");
                $tbl_update->id_usuario=$user->id;
                $tbl_update->update();
            }
            
        }else
        {
            if($request->input('tipo_solicitud')=="incidencia")
            {
                $asunto = $request->input('asunto');
                $descripcion = $request->input('descripcion');
                $prioridad="alta";
                $id_area_estacion=1;
                $id_equipo=40;
                $posicion=null;
                $id_refaccion=178;
                $area_atencion=3;
                $cantidad = " ";
            }else{   
                $asunto = " ";
                $descripcion = " ";
                $prioridad=$request->input('prioridad');
                $id_area_estacion=$request->input('id_area_estacion');
                $id_equipo=$request->input('id_equipo');
                $posicion=$request->input('posicion');
                $id_refaccion=$request->input('refacciones');
                $area_atencion=3;
                $cantidad = $request->input('cant');
            }
            $incidencia = new Incidencia;
            $incidencia->id_usuario = $user->id;
            $incidencia->folio = $request->input('folio');
            $incidencia->estacion = $request->input('inc_estacion');
            $incidencia->tipo_solicitud = $request->input('tipo_solicitud');
            $incidencia->fecha_incidencia = Carbon::now();
            $incidencia->id_area_atencion = $area_atencion;
            $incidencia->estatus_incidencia = $request->input('estatus_incidencia');
            $incidencia->asunto = $asunto;
            $incidencia->descripcion = $descripcion;
            $incidencia->prioridad = $prioridad;
            $incidencia->id_area_estacion = $id_area_estacion;
            $incidencia->id_equipo = $id_equipo;
            $incidencia->posicion = $posicion;
            $incidencia->cantidad = $cantidad;
            $incidencia->id_refaccion = $id_refaccion;        
            //Subir la imagen
            $image_path = $request->file('foto_ruta');
            if ($image_path) {                  
                $image_path_name = time().$image_path->getClientOriginalName();       
                $ruta= storage_path('app/incidencias/'.$image_path_name);       
                
                Image::make($image_path->getRealPath())->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($ruta,60);
                $incidencia->foto_ruta = $image_path_name;
            }            
            $incidencia->save();

            $max= DB::table('incidencias')
            ->select(DB::raw('max(id) as id'))
            ->where('estacion','=',$request->input('inc_estacion'))
            ->where('estatus_incidencia','=','ABIERTA')
            //->where('id','>','1235')
            ->whereRaw('folio LIKE "req%"')
            ->get();

            foreach($max as $m){
                $id_req=$m->id;
            }

            $tbl_relacion= new incidencia_relacion;
            $tbl_relacion->id_incidencia=$request->id;
            $tbl_relacion->id_requerimiento=$id_req;
            $tbl_relacion->id_usuario=$user->id;
            $tbl_relacion->save();
        }
        
        return Redirect::to('listado_incidencias')->with(['message' => 'Requerimiento Generado Correctamente']);
        // {
        //     $incidencia = Incidencia::findOrFail($request->id);
        //     $incidencia->id_area_estacion = $request->get('id_area_estacion');
        //     $incidencia->id_equipo = $request->get('id_equipo');
        //     $incidencia->asunto = $request->get('asunto');
        //     $incidencia->descripcion = $request->get('descripcion');
        //     $incidencia->id_area_atencion = $request->get('id_area_atencion');
            
        //     //$incidencia->foto_ruta = $request->get('foto_ruta');
        //     //Subir la imagen
        //     $image_path = $request->file('foto_ruta');
        //     //var_dump($image_path);
        //     if ($image_path) {
        //         //poner nombre unico
        //         $image_path_name = time() . $image_path->getClientOriginalName();
                
        //         //definir ruta de guardado
        //         $ruta= storage_path('app/incidencias/'.$image_path_name);
                
        //         //reducir peso de imagen usando el parametro 60 de calidad de imagen 0-100
        //         //Image::make($image_path->getRealPath())->save($ruta,60);
        //         Image::make($image_path->getRealPath())->resize(1280, null, function ($constraint) {
        //             $constraint->aspectRatio();
        //             $constraint->upsize();
        //         })->save($ruta,60);

        //         //Guardar en la carpeta storage/app/users
        //         //Storage::disk('incidencias')->put($image_path_name, File::get($image_path));

        //         //seteo el nombre de la imagen en el objeto
        //         $incidencia->foto_ruta = $image_path_name;
        //     }
            
        //     $incidencia->estatus_incidencia = $request->get('estatus_incidencia');
        //     $incidencia->tipo_solicitud = $request->get('tipo_solicitud');
        //     $incidencia->prioridad = $request->get('prioridad');
            
        //     $incidencia->posicion = $request->input('posiciones');
        //     $incidencia->producto = $request->input('producto');
        //     $incidencia->id_refaccion = $request->input('refacciones');
            
        //     $incidencia->update();
        // }
    }

    public function obtenerFolio(Request $request)
    {        
        $max_folio_db = DB::table('incidencias')
                ->select(DB::raw('max(convert(substring(folio,5),unsigned integer)) max_folio '))
                ->where('estacion','=',$request->estacion)
                ->where('tipo_solicitud','=',$request->tipo_solicitud)
                ->first();
        
        $max_folio = $max_folio_db->max_folio;
        
        if ($max_folio == null){
            $max_folio = 0;
        }
        //var_dump($max_folio);
        $OUTfolio = substr($request->tipo_solicitud,0,3).'-'.strval($max_folio+1);
        //var_dump($OUTfolio);
        
        //return response()->json($max_folio);
        return response($OUTfolio);
    }

    public function obtenerAreas(Request $request)
    {
        /*
        if ($request->ajax()) {
            $estaciones = DB::table('areas_estacion')->where('estacion', '=', $request->estacion)->get();
            return response()->json($estaciones);
        }*/

        $negocio=$request->estacion;

        if($negocio=="CORPORATIVO"){
            $valor="CORPORATIVO";
        }
        elseif($negocio=="H. INDIGO" || $negocio=="H. HILTON"){
            $valor="HOTEL";
        }
        else{
            $valor="GASOLINERA";
        }

        if ($request->ajax()) {
            $area = DB::table('area')->where('giro_negocio', '=', $valor)->get();
            return response()->json($area);
        }
    }
    
    public function obtenercatalogo(Request $request)
    {        
        if ($request->ajax()) {
            $area = DB::table('refacciones_catalogo')
            ->where('estatus','=','1')
            ->get();
            return response()->json($area);
        }
    }


    public function obtenerEquipos(Request $request)
    {
        // if ($request->ajax()) {
        //     $equipos = DB::table('equipos')
        //         ->where('estacion', '=', $request->estacion)
        //         ->where('id_area_estacion', '=', $request->id_area_estacion)
        //         ->get();
        //     return response()->json($equipos);
        // }
        if ($request->ajax()) {
            $equipos = DB::table('equipo')
                ->where('id_area_estacion', '=', $request->id_area_estacion)
                ->where('Activo', '=' , '1')
                ->get();
            return response()->json($equipos);
        }
    }
    
    public function obtenerDetalleEquipo(Request $request){
        if ($request->ajax()){
            $equipo = DB::table('equipos')
                ->where('id', '=', $request->id_equipo)
                ->get();
            return response()->json($equipo);
        }
    }
    
    public function obtenerPosiciones(Request $request){
//select p.estacion,p.id_equipo,p.posicion,d.dispensarios from posiciones p inner join dispensarios d on p.estacion=d.estacion 
//where d.estacion='1040' and p.id_equipo<=d.dispensarios         
        if ($request->ajax()){
            $posiciones = DB::table('posiciones as p')
            ->select(DB::raw('p.estacion,p.id_equipo,p.posicion'))
            ->join('dispensarios as d','p.estacion','=','d.estacion')
            ->whereRaw('p.id_equipo <= d.dispensarios')
            ->where('p.estacion', '=', $request->input("estacion"))
            //->where('p.id_equipo','=', $request->id)
            ->get();
            return response()->json($posiciones);
        }
    }

    public function obtenerRefacciones(Request $request){
        if ($request->ajax()){
            $refacciones = DB::table('refacciones2')
                ->where('id_catalogo', '=', $request->id)  
                ->get();
            return response()->json($refacciones);
        }
    }

    public function obtenerRefaccionesDetalle(Request $request){
        if ($request->ajax()){
            $refacciones = DB::table('refacciones2')
                ->where('id', '=', $request->id)  
                ->get();
            return response()->json($refacciones);
        }
    }

    public function obtenerRequerimiento(Request $request){
        if ($request->ajax()){
            $req = DB::table('incidencias')
                ->where('estacion', '=', $request->estacion)  
                ->where('estatus_incidencia','=','ABIERTA')
                //->where('id','>','1235')
                ->whereRaw('folio LIKE "req%"')
                //where estacion='0437' and estatus_incidencia='ABIERTA' AND folio LIKE 'req%'
                ->get();
            return response()->json($req);
        }
    }

    public function obtenerRequerimiento_detalle(Request $request){
        if ($request->ajax()){
            $req = DB::table('vw_listado_incidencias')
                ->where('id', '=', $request->id)  
                ->where('estatus_incidencia','=','ABIERTA')
                ->get();
            return response()->json($req);
        }
    }
    
    // public function obtenerRefacciones(Request $request){
    //     if ($request->ajax()){
    //         $refacciones = DB::table('refacciones')
    //             ->where('id_equipo', '=', $request->id_equipo)
    //             ->where('estacion', '=', $request->estacion)
    //             //->where('posicion', '=', $request->posicion)    
    //             ->get();
    //         return response()->json($refacciones);
    //     }

    //     /*if ($request->ajax()){
    //         $refacciones = DB::table('refaccion')
    //             ->where('id_equipo', '=', $request->id_equipo)   
    //             ->get();
    //         return response()->json($refacciones);
    //     }*/
    // }
    
    // //***CORPORATIVO 03-12-2020 */
    // //Este metodo carga el catalogo de refacciones
    // public function obtenerCatalogoRefacciones(Request $request){
    //     if ($request->ajax()){
            
    //         $refacciones_estacion = DB::table('refacciones')
    //         ->select(DB::raw('equipos.descripcion as Equipo,refacciones.id,refacciones.estacion,refacciones.descripcion,refacciones.prioridad,refacciones.posicion'))    
    //         ->join('equipos', function($join){
    //                     $join->on('refacciones.id_equipo','=','equipos.id');
    //                     $join->on('refacciones.estacion','=','equipos.estacion');
    //                 })    
    //         ->where('refacciones.estacion','=',$request->estacion) //--'6620' --CAMBIO
    //         ->orderBy('refacciones.id_equipo')
    //         ->orderBy('refacciones.id')->get();
            
           
    //         return response()->json($refacciones_estacion);
            
    //     }
    // }

    // public function obtenerRefaccionesDetalle(Request $request){
    //     if ($request->ajax()){
    //         $refaccionesDetalle = DB::table('refacciones')
    //             ->where('id_equipo', '=', $request->id_equipo)
    //             ->where('estacion', '=', $request->estacion)
    //             //->where('posicion', '=', $request->posicion)  
    //             ->where('id','=',$request->id_refaccion)
    //             ->get();
    //         return response()->json($refaccionesDetalle);
    //     }

    //     /*if ($request->ajax()){
    //         $refaccionesDetalle = DB::table('refaccion')
    //             ->where('id_equipo', '=', $request->id_equipo)  
    //             ->where('id','=',$request->id_refaccion)
    //             ->get();
    //         return response()->json($refaccionesDetalle);
    //     }*/
    // }
    
    // public function obtenerRefaccionesSinPosicion(Request $request){
    //     if ($request->ajax()){
    //         $RefaccionesSinPosicion = DB::table('refacciones')
    //             ->where('id_equipo', '=', $request->id_equipo)
    //             ->where('estacion', '=', $request->estacion)                    
    //             ->get();
    //         return response()->json($RefaccionesSinPosicion);
    //     }
    // }
    
    public function obtenerProductos(Request $request){
        if ($request->ajax()){
//if($request->id==0){   ?
            if($request->id>8){    
                $productos = DB::table('productos_estaciones')  
                ->select(DB::raw('distinct producto'))              
                ->where('estacion', '=', $request->estacion)                 
                ->get();
            }else{
                $productos = DB::table('productos_estaciones')                
                ->where('estacion', '=', $request->estacion) 
                ->where('dispensario','=',$request->id)                   
                ->get();
            }
            return response()->json($productos);
        }
    }

    public function obtenerAreasAtencion(Request $request)
    {
        //var_dump($request->id);
        if ($request->ajax()) {
            if($request->id==''){
                $areas_atencion = DB::table('areas_atencion')
                ->get();
            }else{
                $areas_atencion = DB::table('areas_atencion')
                ->where('id', '=', $request->id)
                ->get();
            }            
            return response()->json($areas_atencion);
        }
    }

    //numero_serie
    public function obtener_numero_serie(Request $request)
    {
        $descripcion='%'.$request->descripcion_refaccion.'%'; //-si funciona

        if ($request->ajax()) {
            $numero_serie = DB::table('inventario_equipos')
                //->where('equipo', '=' ,$request->descripcion_refaccion)
                ->where('equipo', 'like' ,$descripcion)
                ->where('id_estacion','=',$request->estacion) 
                ->get();
            return response()->json($numero_serie);

        }
    }

    public function obtenerEstaciones(Request $request){
        if ($request->ajax()) {
            $estaciones = DB::table('estaciones')
            ->select(DB::raw('estacion,concat_ws(" - ",estacion,nombre_corto) as sucursal'))
            ->where('estacion','<>','H. HILTON')
            ->orderByRaw('cast(estacion as unsigned)')
            ->get();
            return response()->json($estaciones);
        }
    }

    public function destroy()
    { }

    public function VerFotosSisa($filename)
    {
        $file = Storage::disk('img_sisa')->get($filename);
        return new Response($file, 200);
    }

    public function getImage($filename)
    {
        $file = Storage::disk('incidencias')->get($filename);
        return new Response($file, 200);
    }
    
    public function getImageDetalleIncidencias($filename)
    {
        $file = Storage::disk('detalle_incidencias')->get($filename);
        return new Response($file, 200);
    }

    public function imagenDetalleIncidencias($filename){
        $file = Storage::disk('detalle_incidencias')->get($filename);

        return new Response($file, 200);
    }

    public function descargarImagenDetalleIncidencias($filename){
        //definir ruta de guardado
        $ruta= storage_path('app/detalle_incidencias/'.$filename);
        return response()->download($ruta);
    }

    public function status_change($id)
    {
        $incidencia = Incidencia::where("id", $id)->first();

        if (!$incidencia) {
            return response()->json([
                'status' => 'error',
                'code' => '200',
                'message' => 'No existe la incidencia.',
            ], 200);
        }

        $user = Auth::user();

        if ($user->id != $incidencia->id_usuario) {
            $log = IncidenciaLog::where([["id_usuario", $user->id], ["id_incidencia", $id]])->first();

            if (!$log) {
                IncidenciaLog::create([
                    'id_usuario' => $user->id,
                    'id_incidencia' => $id,
                    'estatus' => "VISTO"
                ]);

                return response()->json([
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'Registro de visualización creado correctamente.',
                ], 200);
            }
        }
    }

}
