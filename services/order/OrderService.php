<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once APPROOT.'order/Order.php';
require_once 'IOrderService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
class OrderService extends Base implements IOrderService{
	
	
	
	/**
	 * 添加用户（用户注册）
	 * @param $login
	 */

	public function add($info){
	}


	/**
	 * 查找用户
	 * @param  $condition
	 */
	public function query($condition) {
		
		$re=json_decode($condition);
        $_sid=$re->_sid;
       if($_sid==$_SESSION['securitykey']){
		$sql="select ct.*,(select userName_ch from user where id=ct.creator) as _creatorName,(select receiveName from orderaddress where id=ct.oa_id) as receiveName 
             from uorder ct";// where ct.id='$u_id'";
		$conn=DBUtil::getConnection();
        $result=@mysql_query($sql,$conn) or die(@mysql_error());
          $recordCount=0;
		$arr=array();
		 while ($row =@mysql_fetch_array($result)) {
		  		 $ct=new Order();	
		  		 $ct->id=$row['id'];
		  		 $ct->name=$row['name'];
		  		 $ct->out_trade_no=$row['out_trade_no'];
		  		 $ct->createDate=$row['createDate'];
		  		 $ct->getway=$row['getway'];
		  		 $ct->creator=$row['creator'];
		  		 $ct->totalmoney=$row['totalmoney'];
		  		 $ct->totalqty=$row['totalqty'];
		  		 $ct->flag=$row['flag'];
		  		 $ct->totalweight=$row['totalweight'];
		  		 $ct->oa_id=$row['oa_id'];
		  		 $ct->receiveName=$row['receiveName'];
		  		 $ct->_creatorName=$row['_creatorName'];
		  		 array_push($arr,$ct);
		  }
		  $message = json_encode($arr); 
		  $recordCount=count($arr);
		 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		return MessageUtil::getMessage($messageSucess,'array',$message,$recordCount);
	}

	/**
	 * 更新用户信息
	 * @param  $info
	 */
	public function update($info) {
		
         $v = new Province(); 
		 MinnUtil::josonToMap($info,$v);
		
		if($v->_sid==$_SESSION['securitykey']){
		$sql="update category set name='$v->name',descript='$ctype->descript' where id='$ctype->id'";
//        echo $sql;
          $conn=DBUtil::getConnection();
		$result=@mysql_query($sql,$conn);
		if($result==1){
          		$messageSucess=1;
          	$message="更新成功";
          }else{
          	 $messageSucess=0;
             $message="更新失败";
          
          }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		 return MessageUtil::getMessage($messageSucess,$v->_explicitType,$message);
	}
	/**
	 * 删除用户
	 * @param  $info
	 */
	public function delete($info) {
	
		$v=json_decode($info); 
		$vid= $v->id;
		$sql="delete from uorder where id='$vid'";
//		echo $sql;
		if($v->_sid==$_SESSION['securitykey']){
			$conn=DBUtil::getConnection();
		   $result=@mysql_query($sql,$conn) or die(@mysql_error());
//		echo 'dddddddddddd'.$result;
          if($result==''){
          	 $messageSucess=0;
             $message="删除失败";
          }else{
          	$messageSucess=1;
          	$message="删除成功";
          }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,$messageType,$message);
	}

}
?>