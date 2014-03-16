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
require_once APPROOT.'base/JSON.php';
class ProvinceService extends Base implements IAreaService{
	
	/**
	 * 添加商品类型（商品类型）
	 * @param $info
	 */

	public function add($info){
		  $v = new Province();
		  $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$v);
          $keyArr=array("date"=>"createDate");
          $result=parent::addInfo("province",$v,$keyArr);
          if($result==''){
          	 $messageSucess=0;
             $message="添加失败";
          }else{
          	$messageSucess=1;
          	$message="添加成功";
          }
          return MessageUtil::getMessage($messageSucess,$v->_explicitType,$message,$v);
	}


	/**
	 * 更新商品类型信息
	 * @param  $info
	 */
	public function update($info) {
		  $v = new Province(); 
		 $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$v);
		 $conn=DBUtil::getConnection();
		$sql="update province set name='$v->name' where id='$v->id'";
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
		 $v = new Province(); 
		 $obj= json_decode($info);
		 
		  MinnUtil::obj2Map($obj,$v);
	  if($v->_sid==$_SESSION['securitykey']){
		try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 $csql="select id from city where p_id='$v->id'";
         	 $cresult=@mysql_query($csql,$conn) or die(@mysql_error());
         	  while ($trow =@mysql_fetch_array($cresult)) {
         	  	$tsql="delete from town where c_id='".$trow['id']."'";
         	  	@mysql_query($tsql,$conn) or die(@mysql_error());
         	  
         	  }
         	 $csql="delete from city where p_id='$v->id'";
         	 @mysql_query($csql,$conn) or die(@mysql_error());
         	  	
		     $sql="delete from province where id='$v->id'";

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