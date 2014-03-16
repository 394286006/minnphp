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
class CityService extends Base implements IAreaService{
	
	/**
	 * 添加商品类型（商品类型）
	 * @param $info
	 */

	public function add($info){
		  $v = new City();
          $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$v);
          $keyArr=array("date"=>"createDate");
          $result=parent::addInfo("city",$v,$keyArr);
       if($v->_sid==$_SESSION['securitykey']){
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
		  $v = new City(); 
		 $obj= json_decode($info);
		 MinnUtil::obj2Map($obj,$v);
       if($v->_sid==$_SESSION['securitykey']){
		 $conn=DBUtil::getConnection();
		$sql="update city set name='$v->name' where id='$v->id'";
//        echo $sql;
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
		  if($v->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
		     $tsql="delete from town where c_id='$vid'";
//		echo $sql;
             @mysql_query($tsql,$conn) or die(@mysql_error());
         
		     $sql="delete from city where id='$vid'";
//		echo $sql;
	         @mysql_query($sql,$conn) or die(@mysql_error());
             @mysql_query('COMMIT',$conn) or die(@mysql_error());
		     $messageSucess=1;
          	 $message="删除成功";
          	 DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="删除失败";
         }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,$messageType,$message);
	}

}
?>