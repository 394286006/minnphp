<?php
	/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
	 require_once("Log.class.php");
     @session_start();
      Log::doLog();
   
     if(!isset($_SESSION['securitykey'])){
        $key= md5(mt_rand(33,65535));
        @session_id($key);
        $_SESSION['securitykey'] =$key;
     }else{
     	$key=$_SESSION['securitykey'];
     }
     echo $key;

?>