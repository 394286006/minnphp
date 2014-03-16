<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'PublicMessage.php';
require_once 'IPublicMessageService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
@session_start();
class PublicMessageService extends Base implements IPublicMessageService{
	
	
	/**
	 * 添加商品
	 * @param $info
	 */

	public function add($info){
		
		  $vo = new PublicMessage();
          $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
          $keyArr=array("date"=>"createDate");
           $keyArr=array("date"=>"modifyDate");
        if($vo->_sid==$_SESSION['securitykey']){
         try{
         	 $conn=DBUtil::getConnection();
//         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 $vo->id=time().self::getSRM();
         	 $vo->createDate=self::getCurDate();
         	 $msql=MinnUtil::buildInserSql("publicmessage",$vo,$keyArr);
         	 
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
          return MessageUtil::getMessage($messageSucess,'PublicMessage',$message,$vo);
	}
	/**
	 * 更新商品信息
	 * @param  $info
	 */
	public function update($info) {
		
		  $vo = new PublicMessage();
	      $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
         if($vo->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 $vo->modifyDate=self::getCurDate();
         	 $msql="update publicmessage set title='$vo->title',content='$vo->content',modifyDate=date_format('$vo->modifyDate','%Y-%c-%d %H:%i:%s') where id='$vo->id'";
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
          return MessageUtil::getMessage($messageSucess,'PublicMessage',$message,$vo);
	}
	/**
	 * 删除商品
	 * @param  $info
	 */
	public function delete($info) {
		 $vo = new PublicMessage();
		 $obj= json_decode($info);
         MinnUtil::obj2Map($obj,$vo);
        if($vo->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 $msql="delete from publicmessage where id='$vo->id'";
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
//			  $recordCount=parent::getTotalCount($conn,'publicmessage');
			}
			$msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName
	         from publicmessage m where m.title like '%$titlename%' ";
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
			  		 $v=new PublicMessage();
			  		 $v->id=$row['id'];
			  		 $v->title=$row['title'];
			  		 $v->createDate= $row['createDate'];
			  		 $v->modifyDate= $row['modifyDate'];
			  		 $v->content=$row['content'];
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

	public function generatorPublicMessae($condition) {
		$re=json_decode($condition);
		$_sid=$re->_sid;
		if($_sid==$_SESSION['securitykey']){
		$sql0="select * from publicmessage m  order by modifyDate desc limit 10";
		 try{
		 	 $conn=DBUtil::getConnection();
			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
			$message=array();
			  while ($row=@mysql_fetch_array($result0)) {
			  	 $v=new PublicMessage();
//		  		 $v->id=$row['id'];
//		  		 $v->title=$row['title'];
//		  		 $v->createDate=date("Y年m月d日", strtotime($row['createDate']));
		  		 $v->modifyDate=date("Y年m月d日", strtotime($row['modifyDate']));
		  		 $v->content=$row['content'];
//		  		 $v->creator=$row['creator'];
		  		 array_push($message,$v);
			  }
			
			  $messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="生成公告数据失败";
	     }            
        $wenjian = fopen(APPROOT.PUBLICMESSAGEPATH,'w');
            if($wenjian){
                fwrite($wenjian,urlencode(json_encode($message))); 
                $message='生成公告成功!';
            }        
	  }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
        return MessageUtil::getMessage($messageSucess,'array',$message);        
	}
	
}
?>