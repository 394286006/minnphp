<?
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
header("Content-type: text/html;charset=utf-8");
//require_once 'JSON.php';
require_once 'dbUtil.php';
require_once 'xmlUtil.php';
if(isset($_REQUEST["userName"])) {
$username = trim($_REQUEST["userName"]);
$isExit="1";
  $connection=getConnection();
  $sql = "SELECT * FROM user where username='$username'";
  try{
  $result=@mysql_query($sql,$connection);
  $row =@mysql_num_rows($result);
  }catch(ErrorException $e)
  {
  	$isExit=0;
  	echo $e;
  }
  
   if($row==0)
   {
     $isExit="0";
    }
 //header('Content-type: text/xml');
  echo "<user><id>$isExit</id></user>";
}
//$json = new Services_JSON();
if(isset($_REQUEST["userInfo"])) {
   //$user="<userInfo><userName>m</userName><password>5813391</password><age>23</age><sex>1</sex><job>��</job><love
//> ��</love><email>caomin8401@126.com</email><qq>394286006</qq></userInfo>";
	$user = trim($_REQUEST["userInfo"]);
	$isSucess="1";
	$rootTagName="USERINFO";
	$xml=xml2KV($user,$rootTagName);
	$id=time()+rand(200,500);
	$userName=$xml[0]->USERNAME;
	$password=$xml[0]->PASSWORD;
	$age=$xml[0]->AGE;
	$job=$xml[0]->JOB;
	$sex=$xml[0]->SEX;
	$love=$xml[0]->LOVE;
	$email=$xml[0]->EMAIL;
	$qq=$xml[0]->QQ;
	$createdate=time();
	$sql="insert into user(id,username,password,age,job,sex,love,email,qq,createdate,modifydate) 
	values('$id','$userName','$password','$age','$job','$sex','$love','$email','$qq','$createdate','$createdate')";
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

?>
