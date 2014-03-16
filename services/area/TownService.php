<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Area.php';
require_once 'IAreaService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
@session_start();
class TownService extends Base implements IAreaService{


/**
	 * 添加商品类型（商品类型）
	 * @param $info
	 */

	public function add($info){
		  $v = new Town();
         $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$v);
          $keyArr=array("date"=>"createDate");
         if($v->_sid==$_SESSION['securitykey']){
          $result=parent::addInfo("town",$v,$keyArr);
          
          if($result==''){
          	 $messageSucess=0;
             $message="添加失败";
          }else{
          	$messageSucess=1;
          	$message="添加成功";
          }
           }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,$v->_explicitType,$message,$v);
	}
	/**
	 * 更新商品类型信息
	 * @param  $info
	 */
	public function update($info) {
		  $v = new Town(); 
		 $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$v);
		 $conn=DBUtil::getConnection();
		$sql="update town set name='$v->name' where id='$v->id'";
//        echo $sql;
		if($v->_sid==$_SESSION['securitykey']){
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
		$sql="delete from town where id='$vid'";
//		echo $sql;
		  if($v->_sid==$_SESSION['securitykey']){
		  	$conn=DBUtil::getConnection();
		$result=@mysql_query($sql,$conn) or die(@mysql_error());
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