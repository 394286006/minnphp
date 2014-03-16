<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Advertise.php';
require_once 'AdvertiseSet.php';
require_once 'IAdvertiseService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
 @session_start();
class AdvertiseService extends Base implements IAdvertiseService{
	
	
	/**
	 * 添加商品
	 * @param $info
	 */

	public function add($info){
		
		  $vo = new Advertise();
          $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
          $keyArr=array("date"=>"createDate");
         if($vo->_sid==$_SESSION['securitykey']){
         try{
         	 $conn=DBUtil::getConnection();
//         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 $vo->id=time().self::getSRM();
         	 $vo->createDate=self::getCurDate();
         	 $msql=MinnUtil::buildInserSql("advertise",$vo,$keyArr);
         	 
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
          return MessageUtil::getMessage($messageSucess,'advertise',$message,$vo);
	}
	/**
	 * 更新商品信息
	 * @param  $info
	 */
	public function update($info) {
		
		  $vo = new Advertise();
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
         	 $msql="update advertise set title='$vo->title',content='$vo->content',filename='$vo->filename'
         	       ,sourcename='$vo->sourcename',url='$vo->url' where id='$vo->id'";
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
		 $vo = new Advertise();
		 $obj= json_decode($info);
         MinnUtil::obj2Map($obj,$vo);
           if($vo->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 $msql="delete from advertise where id='$vo->id'";
         	 @mysql_query($msql,$conn) or die(@mysql_error());
		     @mysql_query('COMMIT') or die(@mysql_error());
		     
			   
			      	 $uploadfilet = APPROOT.UPLOADADVERTISEPATH.$vo->filename; 
			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
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
	 * 查找商品
	 * @param  $condition
	 */
	public function query($condition) {
		
		$re=json_decode($condition);
		$_sid=$re->_sid;
		$titlename= $re->titlename;
		$startdate= $re->startdate;
		$enddate= $re->enddate;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		 if($_sid==$_SESSION['securitykey']){
		try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
//			  $recordCount=parent::getTotalCount($conn,'advertise');
			}
			$msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName
	         from advertise m where m.title like '%$titlename%' ";
		    if($startdate!=''){
            	$msql.=" and m.createDate>=date_format('$startdate','%Y-%c-%d %H:%i:%s') ";
            }
            
		    if($enddate!=''){
            	$msql.=" and  m.createDate<=date_format('$enddate','%Y-%c-%d %H:%i:%s') ";
            }
            
	        $msql.=" order by m.createDate desc limit $rowStart,$pageSize";
			
	         $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
		  		 $v=new Advertise();
		  		 $v->id=$row['id'];
		  		 $v->title=$row['title'];
		  		 $v->createDate= $row['createDate'];
		  		 $v->content=$row['content'];
		  		 $v->filename=$row['filename'];
		  		 $v->sourcename=$row['sourcename'];
		  		 $v->url=$row['url'];
		  		 $v->creator=$row['creator'];
		  		 $v->_creatorName=$row['_creatorName'];
			  		
			  	 array_push($arr,$v);
			  }
			  $recordCount=count($arr);
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
