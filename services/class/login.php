<?
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
    $user = $_REQUEST["userInfo"];
    //settype($user,"String");
    $islogin="1";

    $rootTagName="USERINFO";
  $xml=xml2KV($user,$rootTagName);
  
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
   header('Content-type: text/xml');
   if($nu==0)
   {
     $islogin="<user><id>0</id></user>";
      echo $islogin;
    }else
    {
    	 $row =@mysql_fetch_row($result);
    	$id=$row[0];
    	$un=$row[1];
    	
    	$u="<user><id>$id</id><userName>$un</userName></user>";
        //$u=new User($un,$id);
    	$_SESSION['user']=$u;
    	 ///echo $u;
    	 echo $u;
    }
  
 
}

//  class User{
//    public $username;
//    public $id;
//    function User($u,$i)
//    {
//    $this->username=$u;
//    $this->id=$i;
//    }
//  }
  
if(isset($_REQUEST["userSession"])) {
	  header('Content-type: text/xml');
	if(isset($_SESSION['user']))
	{
	   echo $_SESSION['user'];
	}else
	{
	 echo "<user><id>0</id></user>";
	}
}

if(isset($_REQUEST["logOutSession"])) {
	  header('Content-type: text/xml');
	unset($_SESSION['user']);
	echo "<user><id>0</id></user>";
}
?>


