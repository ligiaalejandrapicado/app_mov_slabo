<?php

$usuarioEnviado = $_GET['usuario'];
$passwordEnviado = $_GET['password'];

define("dbName","enguiapl_slab");
define("dbUser","enguiapl_slab"); 
define("dbHost","localhost"); 
define("dbPassw","divinoniño1234app");
$DB = mysql_connect(dbHost, dbUser, dbPassw) or die(mysql_error());
mysql_select_db(dbName);





/* Extrae los valores enviados desde la aplicacion movil */
$ci= $_GET['ci'];
$turn = $_GET['turn'];
$fecha = $_GET['fecha'];



//$tel=preg_replace("/[^0-9]/i","",$tel);
//$obs=strtoupper($obs);

$band=0;
if(empty($ci) ){ $var="*C.I\n"; $band=1;}
if(empty($turn) ){ $var.="*Turno de la cita\n"; $band=1;}
if(empty($fecha)){ $var.="*Fecha de la cita";$band=1;}

 

/* crea un array con datos arbitrarios que seran enviados de vuelta a la aplicacion */
$resultados = array();
$resultados["hora"] = date("F j, Y, g:i a"); 
$resultados["generador"] = "Enviado desde APP LABORATORIO CENTRO CLINICO DIVINO NIÑO" ;

if($band==1){
	
	$resultados["mensaje"] = "Por favor verificar: \n".$var;
	$resultados["validacion"] = "error";
	
	} else if($band==0){
	/*esta informacion se envia  */
	
	/*$fecha=date('d:m:Y');
	$hora=date('H:i:s');
	$est='PENDIENTE';
	$sqls1="Insert Into solicitud (  ci_cte, fec_sld,hor_sld,est_sld,obs_sld )
	 values ('" .$ci. "','" .$fecha. "','" .$hora. "','" .$est. "','" .$obs. "')";
	 
	  
	mysql_query($sqls1); */
	
	$sde=1;
	$sqls="Insert Into horario ( ci_clie, cod_sde, turn_hro, fec_hro) values ('" .$ci. "','" .$sde. "','" .$turn. "','" .$fecha. "')"; 
mysql_query($sqls);
	 
	
	$resultados["mensaje"] = "Su solicitud ha sido enviada. En breve le confirmaremos.";
	$resultados["validacion"] = "ok";
}


/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);

/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>