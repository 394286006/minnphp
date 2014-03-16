<?
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'dbUtil.php';
require_once 'xmlUtil.php';
if(isset($_REQUEST["userInfo"])) {
   //$user="<userInfo><userName>m</userName><password>5813391</password><age>23</age><sex>1</sex><job>��</job><love
//> ��</love><email>caomin8401@126.com</email><qq>394286006</qq></userInfo>";
	$user = trim($_REQUEST["userInfo"]);
	$isSucess="1";
	$rootTagName="USERINFO";
	$xml=xml2KV($user,$rootTagName);
	$id=$xml[0]->ID;
	$userName=$xml[0]->USERNAME;
	$password=$xml[0]->PASSWORD;
	$age=$xml[0]->AGE;
	$job=$xml[0]->JOB;
	$sex=$xml[0]->SEX;
	$love=$xml[0]->LOVE;
	$email=$xml[0]->EMAIL;
	$qq=$xml[0]->QQ;
	$modifydate=time();
	$sql="update user set username='$userName',password='$password',age='$age',job='$job'
	,sex='$sex',love='$love',email='$email',qq='$qq',modifydate='$modifydate' where id='$id'";
    //echo json_encode($userName);
    
    $connection=getConnection();
    try{
    $result=@mysql_query($sql,$connection);
   
    }catch(ErrorException $e)
    {
  	  $isSucess=0;
  	  echo $e;
    }
      header('Content-type: text/xml');
	echo "<user><id>$isSucess</id></user>";
}
if(isset($_REQUEST["userId"])) {
    $userId = trim($_REQUEST["userId"]);
    $islogin="1";

  $connection=getConnection();
  $sql = "SELECT id ,username,password,age,job,love,sex,createdate,email,qq,modifydate 
          FROM user where id='$userId'";
  try{
  $result=@mysql_query($sql,$connection);
  $row =@mysql_fetch_row($result);
  }catch(ErrorException $e)
  {
  	$islogin=0;
  	print_r($e);
  }
     header('Content-type: text/xml');
   if($row=="")
   {
     $islogin="0";
      echo  "<user><id>$islogin</id></user>";
    }else
    {
    	$id=$row[0];
    	$un=$row[1];
    	$pwd=$row[2];
    	$age=$row[3];
    	$job=$row[4];
    	$love=$row[5];
    	$sex=$row[6];
    	$dt=new DateTime("@$row[7]+8 hours");
    	$createdate=$dt->format('Y-m-d H:i:s');;
    	$email=$row[8];
    	$qq=$row[9];
    	$mdt=new DateTime("@$row[10]+8 hours");
    	$modifydate=$mdt->format('Y-m-d H:i:s');;
    	$u="<user><id>$id</id><userName>$un</userName>
    	   <password>$pwd</password><age>$age</age>
    	   <job>$job</job><love>$love</love>
    	   <sex>$sex</sex><createdate>$createdate</createdate>
    	   <email>$email</email><qq>$qq</qq><modifydate>$modifydate</modifydate>
    	</user>";
        //$u=new User($un,$id);
    	$_SESSION['user']=$u;
    	 echo $u;
    }
  
 
}
?>
