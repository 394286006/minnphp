<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include 'setting.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'util/DBUtil.php';
require_once APPROOT.'base/JSON.php';

class EncryptService {
	
	public function add($info){
		try{
			 $encryptobj= json_decode($info);
		     $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(mysql_error());
         	 $sql="delete from test";
    	     @mysql_query($sql,$conn) or die(@mysql_error());
    	      $sql1="insert into test (privatekey) values('$encryptobj->privatekey')";
    	      echo $sql1;
    	     @mysql_query($sql1,$conn) or die(@mysql_error());
    	     
    	     
    	     @mysql_query('COMMIT',$conn) or die(mysql_error());
    		 $messageSucess=1;
          	 $message="添加成功";
          	 DBUtil::closeConn($conn);
		}catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="添加失败";
             echo $e;
         }
	}
	 	
}
?>