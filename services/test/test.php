<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
    include '../setting.php';
	require_once APPROOT.'base/JSON.php';
	require_once APPROOT.'util/MinnUtil.php'; 
	require_once APPROOT.'util/DBUtil.php';
	require_once APPROOT.'util/MessageUtil.php';
	require_once("Log.class.php");
	
// 	echo $_SERVER['REQUEST_URI'];
// 	echo $_SERVER['HTTP_HOST'];
// 	echo $_SERVER['SERVER_NAME'];
// 	echo $HTTP_SESSION_VARS[0];
     @session_start();
 	 //  Log::doLog();
 	if($_POST['method']=="encrypt"){
 		$messageSucess=1;
 		$message=$_SESSION['securitykey'];
 		
// 		if($_SERVER['HTTP_HOST']=='127.0.0.1:8009'){
       // if($message==$_POST['skey']){
        if($_POST['skey']!=''){
        	try{
        		
        	 $conn=DBUtil::getConnection();
        	 $sql="select * from test ";
             $result=@mysql_query($sql,$conn) or die(@mysql_error());
          
		    while ($row =@mysql_fetch_array($result)) {
		    	$message=$row['privatekey'];
		    }
		    DBUtil::closeConn($conn);
        	}catch(Exception $e){
        		$messageSucess=0;
        		$message="返回信息有误!";
        		
        	}
        	
 		}else{
 			$message="非法操作!".$message.",".$_POST['skey'];
 			$messageSucess=0;
 		}
 		//echo 'hello';
 	 echo urlencode(json_encode(MessageUtil::getMessage($messageSucess,'key',$message)));
// 	  echo MessageUtil::getMessage($messageSucess,'key',$message);
 	}
?> 
