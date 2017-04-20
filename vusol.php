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
$ci = $_GET['ci'];



$ci=preg_replace("/[^0-9]/i","",$ci);



$band=0;
//if(empty($ci) ){ $var="*C.I\n"; $band=1;}


$rese = mysql_query("SELECT * FROM horario WHERE  ci_clie='$ci'  ORDER BY  cod_hro DESC limit 5  ;", $DB); 
$rowe = mysql_num_rows($rese);   

/* crea un array con datos arbitrarios que seran enviados de vuelta a la aplicacion */
$resultados = array();
$resultados["hora"] = date("F j, Y, g:i a"); 
$resultados["generador"] = "Enviado desde APP LABORATORIO CENTRO CLINICO DIVINO NIÑO" ;

if($band==1){
	
	$resultados["mensaje"] = "Por favor verificar: \n".$var;
	$resultados["validacion"] = "error";
	
	} else if($band==0){


			if($rowe>0){
	
					/*CONSULTAR LOS DATOS EN LA BASE DE DATOS*/
					while($rege = mysql_fetch_array($rese)){
						if ($rege[3]=='M') $turno='MAÑANA'; else $turno='TARDE';
						$resultados["mensaje"] .= "Nro: $rege[0] \n Fecha: $rege[4] - Turno: $turno \n\n";
						
					}//END WHILE
			
 			}else if($rowe<=0){
				
						$resultados["mensaje"] = "NO POSEE SOLICITUDES REGISTRADAS EN NUESTRO SISTEMA \n";
				
				}//END IF MENOR A CERO
	
	$resultados["validacion"] = "ok";

	}// BAND igual a cero

/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);

/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>