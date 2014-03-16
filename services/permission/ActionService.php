<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'ActionTb.php';
require_once 'GroupTb.php';
require_once 'IPermissionService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
 @session_start();
class ActionService extends Base implements IPermissionService{
	
	
	/**
	 * 添加商品
	 * @param $info
	 */

	public function add($info){
		
		  $vo = new ActionTb();
          $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
          $keyArr=array("date"=>"createDate");
         if($vo->_sid==$_SESSION['securitykey']){
         try{
         	 $conn=DBUtil::getConnection();
//         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 $vo->id=time().self::getSRM();
         	 $vo->createDate=self::getCurDate();
         	 $msql=MinnUtil::buildInserSql("action",$vo,$keyArr);
         	 
    	     @mysql_query($msql,$conn) or die(@mysql_error());
    	     
//    	     @mysql_query('COMMIT',$conn) or die(@mysql_error());
    	     
    		 $messageSucess=1;
          	 $message="添加成功";
          	  DBUtil::closeConn($conn);
         }catch(Exception $e){
//         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="添加失败";
         }
         }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,'action',$message,$vo);
	}
	/**
	 * 更新商品信息
	 * @param  $info
	 */
	public function update($info) {
		
		  $vo = new ActionTb();
	      $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
            if($vo->_sid==$_SESSION['securitykey']){
		 try{
		 	 $conn=DBUtil::getConnection();
//		 	$msql="select * from advertise where id='$vo->id'";
//		 	$result=@mysql_query($msql,$conn) or die(@mysql_error());
//	          
//			 $row =@mysql_fetch_row($result);
//		  		if($row['filename']!=$vo->filename){
//		 		     $uploadfilet = APPROOT.UPLOADADVERTISEPATH.$row['filename']; 
//			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
//			      	 if(!unlink($uploadfilet))
//		                      throw new Exception('更新失败!');
//		  		}
			 
         	
         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
//         	 $vo->modifyDate=self::getCurDate();
         	 $msql="update action set name='$vo->name',actionnum='$vo->actionnum',descript='$vo->descript'
         	        where id='$vo->id'";
         	 @mysql_query($msql,$conn) or die(@mysql_error());
         	 @mysql_query('COMMIT',$conn) or die(@mysql_error());
		     $messageSucess=1;
          	 $message="更新成功";
          	 DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败:".$e;
         }
            }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,'Advertise',$message,$vo);
	}
	/**
	 * 删除商品
	 * @param  $info
	 */
	public function delete($info) {
		 $vo = new ActionTb();
		 $obj= json_decode($info);
         MinnUtil::obj2Map($obj,$vo);
           if($vo->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 $msql="delete from action where id='$vo->id'";
         	 @mysql_query($msql,$conn) or die(@mysql_error());
		     @mysql_query('COMMIT') or die(@mysql_error());
		     
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
          return MessageUtil::getMessage($messageSucess,$vo->_explicitType,$message);
	}
	
	/**
	 * 查找权限
	 * @param  $condition
	 * @param @$type 0=权限设置查找 1=groupaction组/角色权限设置查找
	 * 
	 */
	public function query($condition) {
		
		$re=json_decode($condition);
		$_sid=$re->_sid;
	    $type=$re->type;
	    $incondition=$re->incondition;
		 if($_sid==$_SESSION['securitykey']){
		try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
			  $recordCount=parent::getTotalCount($conn,'advertise');
			}
			$msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName
	         from action m";
			if($type==1){
				$msql=$msql." where m.actionnum not in($incondition)";
			}
			 $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
		  		 $v=new ActionTb();
		  		 $v->id=$row['id'];
		  		 $v->name=$row['name'];
		  		 $v->createDate= $row['createDate'];
		  		 $v->descript=$row['descript'];
		  		 $v->creator=$row['creator'];
		  		 $v->actionnum=$row['actionnum'];
		  		 $v->_creatorName=$row['_creatorName'];
			  		
			  	 array_push($arr,$v);
			  }
			  $message = json_encode($arr); 
			  $messageSucess=1;
		}catch(Exception $e){
			  $messageSucess=0;
			  $message="查询数据失败";
	     }
		  }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		return MessageUtil::getMessage($messageSucess,'array',$message,$recordCount);
	}

	
	
	public function generatorAdvertiseMenu($info) {
		
	     $obj=json_decode($info);
	     $_sid=$obj->_sid;
	      if($_sid==$_SESSION['securitykey']){
		 try{
		 	$adverties=$obj->adverties;
			$message=array();
			$otherNum=count($adverties);
		   for($j=0;$j<count($adverties);$j++){
    	     	$ads=new Advertise();
    	     	 MinnUtil::obj2Map($adverties[$j],$ads);
    	     	
    	     	 array_push($message,$ads);
			  }
			
			  $messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="生成广告数据失败";
	     }            
        $wenjian = fopen(APPROOT.ADVERTISEPATH,'w');
            if($wenjian){
                fwrite($wenjian,urlencode(json_encode($message))); 
                $message='生成广告菜单成功!';
            }        
	}else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
        return MessageUtil::getMessage($messageSucess,'array',$message,$otherNum);        
	}
	
	public function getAdvertiseMenu($info) {
		
		 $obj=json_decode($info);
	     $_sid=$obj->_sid;
	      if($_sid==$_SESSION['securitykey']){
		 try{
            $my_file = file_get_contents(APPROOT.ADVERTISEPATH);
		 	$message=urldecode($my_file);
			  $messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="返回广告菜单失败";
	     }            
	  }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
        return MessageUtil::getMessage($messageSucess,'array',$message);        
	}
	
	
}
?>
