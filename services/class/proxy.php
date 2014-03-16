<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
session_start();
require_once 'dbUtil.php';
require_once 'xmlUtil.php';
if(isset($_REQUEST["userInfo"])) {
   //$user="<userInfo><userName>m</userName><password>5813391</password><age>23</age><sex>1</sex><job>��</job><love
//> ��</love><email>caomin8401@126.com</email><qq>394286006</qq></userInfo>";
	$user = trim($_REQUEST["userInfo"]);
	$isSucess="1";
	$rootTagName="USERINFO";
	$xml=xml2KV($user,$rootTagName);
	$operator=$xml[0]->OPERATOR;
    //header('Content-type: text/xml');
	if($operator=="login")
	{
		$login=new Login();
	  $return=$login->getLogin($xml);
	  if($return!=0)
	  $_SESSION['user']=$return;
	  echo $return;
	}
	if($operator=="userSession")
	{
	  if(isset($_SESSION['user']))
	  {
	   echo $_SESSION['user'];
	  }else
	  {
	   echo "0";
	   }
	}
   if($operator=="logOutSession")
	{
	  unset($_SESSION['user']);
	  echo "0";
    }
}
class Login{
	
	function getLogin($xml)
	{
	 $userName=trim($xml[0]->USERNAME);
     $password=trim($xml[0]->PASSWORD);
     $connection=getConnection();
     $sql = "SELECT id ,username FROM user where username='$userName' and password='$password'";
    try{
     $result=@mysql_query($sql,$connection);
     $nu =@mysql_num_rows($result);
     }catch(ErrorException $e)
     {
    	$islogin=0;
    	print_r($e);
     }
   if($nu==0)
   {
     $islogin="<user><id>0</id></user>";
      return $nu;
    }else
    {
    	 $row =@mysql_fetch_row($result);
    	$id=$row[0];
    	$un=$row[1];
    	
    	$u="<user><id>$id</id><userName>$un</userName></user>";
    	 return $u;
    }
	}
	
}
?>