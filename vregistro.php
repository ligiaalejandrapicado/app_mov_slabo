<?php

$usuarioEnviado = $_GET['usuario'];
$passwordEnviado = $_GET['password'];
$ci = $_GET['ci'];
$des = $_GET['des'];
$rep = $_GET['rep'];
$dir = $_GET['dir'];
$tel1 = $_GET['tel1'];
$tel2 = $_GET['tel2'];
$email = $_GET['email'];
$pass = $_GET['pass'];
$pass2 = $_GET['pass2'];

define("dbName","enguiapl_slab");
define("dbUser","enguiapl_slab"); 
define("dbHost","localhost"); 
define("dbPassw","divinoniño1234app");
$DB = mysql_connect(dbHost, dbUser, dbPassw) or die(mysql_error());
mysql_select_db(dbName);


/* Extrae los valores enviados desde la aplicacion movil */


$ci=preg_replace("/[^0-9]/i","",$ci);
$tel1=preg_replace("/[^0-9]/i","",$tel1);
$tel2=preg_replace("/[^0-9]/i","",$tel2);

$des=strtoupper($des);
$rep=strtoupper($rep);
$dir=strtoupper($dir);

$band=0;
if(empty($ci)  ){ $var="*C.I\n"; $band=1;}
if(empty($des) ){ $var.="*Descripción\n"; $band=1;}
if(empty($rep) ){ $var.="*Representante\n";$band=1;}
if(empty($dir) ){ $var.="*Dirección\n";$band=1;}
if(empty($tel1) ){ $var.="*Teléfono 1 $tel1 \n";$band=1;}
if(empty($tel2) ){ $var.="*Teléfono 2 $tel2 \n";$band=1;}
if(empty($pass)  ||  $pass!=$pass2 ){ $var.="*Contraseña\n";$band=1;}
if(empty($pass2) ||  $pass!=$pass2 ){ $var.="*Confirmación de Contraseña\n";$band=1;}

$rese = mysql_query("SELECT * FROM usuario_app WHERE  nom_usu='$email' || ci_cte='$ci'  ORDER BY id_usu  ;", $DB); 
$rowe = mysql_num_rows($rese);   

/* crea un array con datos arbitrarios que seran enviados de vuelta a la aplicacion */
$resultados = array();
$resultados["hora"] = date("F j, Y, g:i a"); 
$resultados["generador"] = "Enviado desde APP LABORATORIO CENTRO CLINICO DIVINO NIÑO" ;
if($rowe>0){
$rege = mysql_fetch_array($rese);
/* verifica que el usuario y password ya existen */
if(  $rege[1] == $email || $rege[4] == $ci){
	/*esta informacion se envia solo si la validacion es correcta */
	$resultados["mensaje"] = "Email u Usuario ya existe en nuestros registros.";
	$resultados["validacion"] = "error";

}}

if($band==1){
	
	$resultados["mensaje"] = "Por favor verificar: \n".$var;
	$resultados["validacion"] = "error";
	
	}



if($rowe==0 && $band==0){
	/*esta informacion se envia  */
	
	$nivel=2;
	
	
	
	
	$sqls1="Insert Into cliente ( ci_clie, des_clie, rep_clie, dir_clie, tel1_clie, tel2_clie, email_clie ) values ('" .$ci. "','" .$des. "','" .$rep. "','" .$dir. "','" .$tel1. "','" .$tel2. "','" .$email. "')"; 
mysql_query($sqls1);   
	
	
	
	/*$sqls1="Insert Into cliente ( ci_cte, nom_cte,ape_cte,dir_cte,tel_cte )
	 values ('" .$ci. "','" .$nom. "','" .$ape. "','" .$dir. "','" .$tel. "')";*/
	 
	$sqls2="Insert Into usuario_app ( nom_usu,pass_usu,niv_usu,ci_cte )
	 values ('" .$email. "','" .$pass. "','" .$nivel. "','" .$ci. "')";
	  
	mysql_query($sqls1); 
	mysql_query($sqls2); 
	
	$resultados["mensaje"] = "Su registro se ha sido exitoso. Puede ingresar a nuestra aplicaci&oacute;n.";
	$resultados["validacion"] = "ok";
}


/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);

/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>