<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Payway.php';
require_once 'IPaywayService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
class PaywayService extends Base implements IPaywayService{
	
	
	
	/**
	 * 添加支付方式
	 * @param $info
	 */

	public function add($info){
		  $v = new Payway();
          $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$v);
          $keyArr=array("date"=>"createDate");
          $result=parent::addInfo("payway",$v,$keyArr);
          $messageType=$v->_explicitType;
          if($result==''){
          	 $messageSucess=0;
             $message="添加失败";
          }else{
          	$messageSucess=1;
          	$message="添加成功";
          }
          return MessageUtil::getMessage($messageSucess,$messageType,$message,$v);
	}
	
	/**
	 * 查找
	 * @param  $condition
	 */
	public function query() {
		$recordCount=-1;
			try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
			  $recordCount=parent::getTotalCount($conn,'payway');
			}
			$sql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName 
			  from payway m";
			  $result=@mysql_query($sql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
			  		 $v=new Payway();	
			  		 $v->id=$row['id'];
			  		 $v->name=$row['name'];
			  		 $v->createDate=$row['createDate'];
			  		 $v->creator=$row['creator'];
			  		 $v->descript=$row['descript'];
			  		 $v->_creatorName=$row['_creatorName'];
			  		 $v->account=$row['account'];
				  array_push($arr,$v);
			  }
			  $message = json_encode($arr); 
			  $messageSucess=1;
		}catch(Exception $e){
			  $messageSucess=0;
			  $message="查询数据失败";
	     }
		 
		return MessageUtil::getMessage($messageSucess,'array',$message,$recordCount);
	}

	/**
	 * 更新支付信息
	 * @param  $info
	 */
	public function update($info) {
		 $v = new Payway(); 
		 $obj= json_decode($info);
		 MinnUtil::obj2Map($obj,$v);
		 $conn=DBUtil::getConnection();
		$sql="update payway set name='$v->name',descript='$v->descript',account='$v->account' where id='$v->id'";
//        echo $sql;
		$result=@mysql_query($sql,$conn);
		if($result==1){
          		$messageSucess=1;
          	$message="更新成功";
          }else{
          	 $messageSucess=0;
             $message="更新失败";
          
          }
		 return MessageUtil::getMessage($messageSucess,$v->_explicitType,$message);
	}
	/**
	 * 删除用户
	 * @param  $info
	 */
	public function delete($info) {
		$v = new Payway();
		$obj= json_decode($info);
		$id= $obj->id;
		$sql="delete from Payway where id='$id'";
//		echo $sql;
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
          return MessageUtil::getMessage($messageSucess,$messageType,$message);
	}

}
?>