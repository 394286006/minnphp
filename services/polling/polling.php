<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'util/DBUtil.php';
require_once APPROOT.'base/JSON.php';
$rto=true;
set_time_limit(200);
$strtime=time();
$temptime=0;
	try{
	  	while ($rto)
          {
          	sleep(10);
	    	$sql="select ct.* from uorder ct where ct.out_trade_no='".$_POST['out_trade_no']."' and ct.ispay=1";
	  	    $conn=DBUtil::getConnection();
            $result=@mysql_query($sql,$conn) or die(mysql_error());
            $num_rows = @mysql_num_rows($result);
            if($num_rows>0){
	  	         $rto =false;
	  	          $message="付款成功!";
                 $messageSucess=1;
            }
            $temptime=time()-$strtime;
            if($temptime>120){
            	  $rto=false;
            	  $message="付款超时!";
                 $messageSucess=0;
            }
          
            
         }
         
	  }catch(Exception $e){
         	 $rto=false;
         	 $messageSucess=0;
             $message="订单操作有误:"+$e;
//          echo urlencode(json_encode(MessageUtil::getMessage($messageSucess,'string',$message)));
         }
   echo urlencode(json_encode(MessageUtil::getMessage($messageSucess,'string',$message)));






?>