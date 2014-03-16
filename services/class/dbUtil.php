<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
date_default_timezone_set("PRC");

 function getConnection()
{
  $host="localhost";
  $name="root";
 $pwd="5813391";
 $db="minn";
try{
	
  $conn=@mysql_connect(trim($host),trim($name),trim($pwd));
  $db = @mysql_select_db(trim($db), $conn);
  mysql_query("set names utf8;");
  }catch(ErrorException $e)
  {
  	//$isExit="0";
  	echo $e;
   die(mysql_error());
  }
  return $conn;
}


?>