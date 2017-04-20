<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
/* Define los valores que seran evaluados, en este ejemplo son valores estaticos,
en una verdadera aplicacion generalmente son dinamicos a partir de una base de datos */




/* Extrae los valores enviados desde la aplicacion movil */
$usuarioEnviado = $_GET['usuario'];
$passwordEnviado = $_GET['password'];

define("dbName","enguiapl_slab");
define("dbUser","enguiapl_slab"); 
define("dbHost","localhost"); 
define("dbPassw","divinoniño1234app");
$DB = mysql_connect(dbHost, dbUser, dbPassw) or die(mysql_error());
mysql_select_db(dbName);

$rese = mysql_query("SELECT * FROM usuario_app WHERE nom_usu='$usuarioEnviado' ORDER BY id_usu  ;", $DB); 
$rowe = mysql_num_rows($rese);   


/* crea un array con datos arbitrarios que seran enviados de vuelta a la aplicacion */
$resultados = array();
$resultados["hora"] = date("F j, Y, g:i a"); 
$resultados["generador"] = "Enviado desde APP LABORATORIO CENTRO CLINICO DIVINO NIÑO" ;
if($rowe>0){
$rege = mysql_fetch_array($rese);
/* verifica que el usuario y password concuerden correctamente */
if(  $rege[1] == $usuarioEnviado && $rege[2] == $passwordEnviado){
	/*esta informacion se envia solo si la validacion es correcta */
	$resultados["mensaje"] = "Validacion Correcta";
	$resultados["validacion"] = "ok";
	$resultados["ced"] = $rege[4];
	$_SESSION['ced']=$rege[4];

}}else{
	/*esta informacion se envia si la validacion falla */
	$resultados["mensaje"] = "Usuario y password incorrectos";
	$resultados["validacion"] = "error";
	
}


/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);

/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>