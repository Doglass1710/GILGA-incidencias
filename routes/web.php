<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::resource('/incidencias', 'IncidenciasController');
Route::get('/captura_incidencia', 'IncidenciasController@create');

Route::get('/listado_incidencias', 'IncidenciasController@listado_incidencias');
//ruta para ver el listado de incidencias_cerradas
Route::get('/incidencias_cerradas', 'IncidenciasController@incidencias_cerradas');
Route::get('/incidencias_cerradas_ant', 'IncidenciasController@incidencias_cerradas_ant');

Route::get('/incidencia/imagenes/{filename}','IncidenciasController@getImage')->name('incidencia.imagenes');
Route::get('/incidencia/detalleimagenes/{filename}','IncidenciasController@getImageDetalleIncidencias')->name('incidencia.detalleimagenes');

Route::get('/incidencias/detalles/imagenes/{filename}','IncidenciasController@imagenDetalleIncidencias')->name('imagenDetalleIncidencias');

Route::get('/incidencias_cerradas/detalles/descargaimagenes/{filename}','IncidenciasController@descargarImagenDetalleIncidencias')->name('descargarImagenDetalleIncidencias');

//No es necesario cambiar la URL en estos casos, con que se cambie el método alcanza.
//esta ruta es usada en show.blade.php aqui se llama a la vista de CREAR DETALLE
Route::get('/incidencia/{id}/detalle/captura','IncidenciasController@captura_detalle_incidencia')->name('detalle');

//esta ruta se usa para guardar en detalle_incidencia
Route::post('/incidencia/{id}/detalle/captura','IncidenciasController@captura_detalleincidencia')->name('detalle_send');

Route::post('/incidencia/actualizar','IncidenciasController@update')->name('detalle_actualizar');
Route::post('/incidencia/actualizar_detalle','IncidenciasController@updateDetalleIncidencia')->name('detalle_incidencia_actualizar');

//rutas para Compras
Route::post('/incidencia/alta_compras','IncidenciasController@alta_compras')->name('alta_compras');
Route::post('/incidencia/editar_compra','IncidenciasController@editar_compra')->name('editar_compra');
Route::post('/incidencia/eliminar_compra','IncidenciasController@eliminar_compra')->name('eliminar_compra');

//esta ruta es usada para guardar en incidencias_logs
Route::post('/incidencia/{id}/status/change','IncidenciasController@status_change')->name('status_change');

//Ruta para obtener los detalles de la compra
Route::get('/incidencias/compras_detalle/{id_compra}','IncidenciasController@getCompras_detalle')->name('getCompras_detalle');

//Ruta para obtener los detalles de la incidencia
Route::get('/incidencias_cerradas/detalles/{id_incidencia}','IncidenciasController@getIncidencias_detalle')->name('getIncidencias_detalle');

//esta ruta es usada para autorizar una compra
//Route::get('/incidencia/compras/{id}/change','IncidenciasController@aut_compra')->name('aut_compra');
Route::post('/incidencia/compras/change','IncidenciasController@aut_compra')->name('aut_compra');
Route::post('/incidencia/compras/denegar','IncidenciasController@denegar_compra')->name('denegar_compra');
Route::post('/incidencia/compras/cerrar','IncidenciasController@cerrar_compra')->name('cerrar_compra');


Route::get('areas','IncidenciasController@obtenerAreas');
Route::get('equipos','IncidenciasController@obtenerEquipos');
Route::get('equipo_detalle','IncidenciasController@obtenerDetalleEquipo');
Route::get('areas_atencion','IncidenciasController@obtenerAreasAtencion');
//numero_serie 
//Route::get('numero_serie','IncidenciasController@obtener_numero_serie');
Route::get('tanques','IncidenciasController@consultartanques');
Route::get('horario','IncidenciasController@activarhorario');
//Route::get('medidas','IncidenciasController@consultar_medidas');
Route::get('dias_anexo','IncidenciasController@dias_anexo');

Route::get('folios','IncidenciasController@obtenerFolio');
Route::get('catalogo_refaccion','IncidenciasController@obtenercatalogo');

Route::get('posiciones','IncidenciasController@obtenerPosiciones');
Route::get('refacciones','IncidenciasController@obtenerRefacciones');
Route::get('productos_estaciones','IncidenciasController@obtenerProductos');
Route::get('refacciones_detalle','IncidenciasController@obtenerRefaccionesDetalle');
Route::get('requerimiento','IncidenciasController@obtenerRequerimiento');
Route::get('requerimiento_detalle','IncidenciasController@obtenerRequerimiento_detalle');

////Route::get('refacciones_detalle_Corp','IncidenciasController@obtenerRefaccionesDetalle_Corp');
// Route::get('catalogo_refacciones','IncidenciasController@obtenerCatalogoRefacciones'); //corp
// Route::get('refaccionesSinPosicion','IncidenciasController@obtenerRefaccionesSinPosicion');


Route::get('incidencias/estacion/{estacion}','IncidenciasController@incidenciasxestacion');
Route::get('incidencias/estacion/{estacion}/{ruta}','IncidenciasController@incidenciasxestacion');

//ruta para ver el reporte de incidencias
Route::get('/reporte_incidencias', 'IncidenciasController@reporte_incidencias');

Route::post('/reporte_incidencias/generar_reporte', 'IncidenciasController@genReporte');

//Rutas para reporte compras
Route::get('/reporte_compras', 'IncidenciasController@reporte_compras')->name('viewReporteCompras');
Route::post('/reporte_compras/generar_reporte', 'IncidenciasController@genReporteCompras')->name('genReporteCompras');
Route::get('/incidencias/pdf/rptordencompra/{id_compra}','IncidenciasController@rptordencompra')->name('rptordencompra');


//Rutas para catalogo de refacciones
Route::get('captura_refacciones', 'IncidenciasController@agregar_refaccion');
Route::post('captura_refacciones', 'IncidenciasController@agregar_refaccion');
//Route::post('/catalogos/refacciones/agregar_refaccion', 'IncidenciasController@agregar_refaccion')->name('agregar_refaccion');

//Ruta para MedidasDiarias
Route::get('/captura_medidas','IncidenciasController@captura_medidas');
//cambio medias rpt
//Route::post('/captura_medidas/guardar_medidas','IncidenciasController@guardar_medidas');//->name('guardar_medidas');
Route::post('/captura_medidas','IncidenciasController@guardar_medidas');
Route::get('/reporte_medidas','IncidenciasController@mostrar_medidas');
Route::post('/reporte_medidas/genReporteMedidas','IncidenciasController@genReporteMedidas');
Route::get('/abrirDias','IncidenciasController@HabilitarDias');
Route::post('/abrirDias','IncidenciasController@HabilitarDias');

//Rutas Bitacora Motos Elsa
Route::get('/bitacora_altapiezas','IncidenciasController@bitacora_alta');
Route::post('/bitacora_altapiezas','IncidenciasController@bitacora_catalogo')->name('editar');
Route::get('/bitacora_captura','IncidenciasController@bitacora_captura');
Route::post('/bitacora_captura','IncidenciasController@capturar_bitacora');
Route::get('moto','IncidenciasController@bit_moto');
Route::get('fecha','IncidenciasController@bit_fecha');
Route::get('/bitacora_consultas','IncidenciasController@bitacora_consultas');
Route::post('/bitacora_consultas','IncidenciasController@bitacora_consultas_buscar');
Route::get('/reporte_bitacora','IncidenciasController@reporte_bitacora');
Route::post('/reporte_bitacora_excel','IncidenciasController@reporte_bitacora_excel');

//Ruta para ANEXO
Route::get('/anexo_ventas','IncidenciasController@ruta_anexo_ventas');
//Route::post('/anexo_ventas','IncidenciasController@anexo_ventas_admin');
Route::post('/anexo_ventas','IncidenciasController@anexo_ventas_guardar');
Route::get('/anexo_consulta','IncidenciasController@anexo_consulta');
Route::post('/anexo_consulta','IncidenciasController@anexo_consulta');
Route::get('/anexo_compras','IncidenciasController@anexo_compras');
Route::post('/anexo_compras','IncidenciasController@anexo_compras_guardar');
Route::get('/anexo_diferencia','IncidenciasController@anexo_diferencia');
Route::post('/anexo_diferencia','IncidenciasController@anexo_diferencia_guardar');
Route::get('/reporte_anexo1','IncidenciasController@reporte_anexo');
Route::post('/reporte_anexo1','IncidenciasController@reporte_anexo_excel');
Route::get('/reporte_comprasGral','IncidenciasController@reporte_comprasGral');
Route::post('/reporte_comprasGral','IncidenciasController@reporte_comprasGral_Exp');
Route::get('/anexo_inventario','IncidenciasController@anexo_inventario');
Route::post('/anexo_inventario','IncidenciasController@anexo_inventario');
Route::get('/reporte_anexo_graficas','IncidenciasController@ReporteAnexoGraficas');
Route::post('/reporte_anexo_graficas','IncidenciasController@ReporteAnexoGraficas');
Route::get('calcularPromedio','IncidenciasController@calcularPromedio');

//Anexo_Corregir
Route::get('/anexo_corregir','IncidenciasController@anexo_borrar');
Route::post('/anexo_corregir','IncidenciasController@anexo_borrar_ejecutar');
Route::get('/anexo_validar','IncidenciasController@anexo_compras_consultar');
Route::post('/anexo_validar','IncidenciasController@anexo_compras_consultar');
Route::post('/anexo_compras_eliminar','IncidenciasController@anexo_compras_eliminar')->name('anexo_compras_eliminar');

//Ruta para SISA
Route::get('/captura_sisa','IncidenciasController@captura_sisa');
Route::post('/captura_sisa','IncidenciasController@captura_sisa_guardar');
Route::get('/reporte_sisa','IncidenciasController@reporte_sisa');
Route::post('/reporte_sisa','IncidenciasController@reporte_sisa');
Route::get('companias','IncidenciasController@consultar_companias');
Route::get('gerentes','IncidenciasController@consultar_gerentes');
Route::get('/reporte_sisa/pdf/{id}','IncidenciasController@pdf_sisa')->name('pdf_sisa');
Route::post('/reporte_sisa/pdf','IncidenciasController@subir_pdf')->name('subir_pdf');
Route::get('/firmados','IncidenciasController@firmados_sisa')->name('firmados_sisa');
Route::post('/firmados','IncidenciasController@firmados_sisa');
Route::get('/firmados/pdf/{pdf}','IncidenciasController@firmados_visualizar')->name('firmados_visualizar');

Route::get('/sisa/imagenes/{filename}','IncidenciasController@VerFotosSisa')->name('incidencia.sisa');
Route::post('/sisa/{filename}','IncidenciasController@VerFotosSisa')->name('incidencia.sisa');

//Ruta para Bitcora de Dispensarios
Route::get('/dispensarios_bitacora','IncidenciasController@dispensarios');
Route::post('/dispensarios_bitacora','IncidenciasController@dispensarios_bit');
Route::get('/reporte_dispensarios','IncidenciasController@dispensarios_rpt');
Route::post('/reporte_dispensarios','IncidenciasController@dispensarios_rpt');
Route::get('/reporte_dispensarios/pdf/{id}','IncidenciasController@orden_trabajo')->name('orden_trabajo');
Route::get('/bitacora_dispensarios/{estacion}/{fecha1}/{fecha2}','IncidenciasController@dispensarios_pdf')->name('dispensarios_pdf');
//Route::post('/reporte_dispensarios/pdf','IncidenciasController@dispensarios_pdf')->name('dispensarios_pdf');
Route::get('catalogo','IncidenciasController@obtener_descripcion');
Route::get('eventos','IncidenciasController@obtener_factor');
Route::get('dispensarios','IncidenciasController@obtener_dispensarios');
Route::get('orden','IncidenciasController@obtener_ordentrabajo');

//Modulo Usuarios
Route::get('/us_grafico','IncidenciasController@usuarios_grafico')->name('us_grafico');
Route::get('/usuario', 'IncidenciasController@RegistrarUsuario');
Route::post('/usuario', 'IncidenciasController@RegistrarUsuario');
Route::get('/ver_usuarios', 'IncidenciasController@ConsultarUsuario');
Route::get('estaciones','IncidenciasController@obtenerEstaciones');

//Comentarios
Route::post('insertar_comentarios', 'IncidenciasController@insertar_comentarios');
Route::get('index/comentarios/{id}','IncidenciasController@leer_comentarios')->name('leer_comentarios');

// INCIDENCIAS DE SISTEMAS - INVENTARIO-
Route::get('/captura_incidencia_sistemas', 'InventarioController@incidencias_sistemas');
Route::post('/captura_incidencia_sistemas', 'InventarioController@incidencias_sistemas');
Route::get('subareas','InventarioController@GetSubareas');
Route::get('inventario_equipos','InventarioController@tabla_equipos');
Route::get('folio_equipos','InventarioController@folio_equipos');

//Consolidado
Route::get('/captura_consolidado','IncidenciasController@consolidado');