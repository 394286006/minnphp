<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Domain.php';
require_once 'IDomainService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
class DomainService extends Base implements IDomainService{
	
	
	/**
	 * 添加商品
	 * @param $info
	 */

	public function add($info){
		
		  $vo = new Domain();
          $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
          $keyArr=array("date"=>"createDate");
        if($vo->_sid==$_SESSION['securitykey']){
         try{
         	 $conn=DBUtil::getConnection();
//         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 $vo->id=time().self::getSRM();
         	 $vo->createDate=self::getCurDate();
         	 $msql=MinnUtil::buildInserSql("mydomain",$vo,$keyArr);
         	 
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
		
		  $vo = new Domain();
	      $obj= json_decode($info);
          MinnUtil::obj2Map($obj,$vo);
          if($vo->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
//         	 $vo->modifyDate=self::getCurDate();
         	 $msql="update mydomain set name='$vo->name',descript='$vo->descript' where id='$vo->id'";
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
          return MessageUtil::getMessage($messageSucess,'Domain',$message,$vo);
	}
	/**
	 * 删除商品
	 * @param  $info
	 */
	public function delete($info) {
		 $vo = new Domain();
		 $obj= json_decode($info);
         MinnUtil::obj2Map($obj,$vo);
        if($vo->_sid==$_SESSION['securitykey']){
		 try{
         	 $conn=DBUtil::getConnection();
         	 $msql="delete from mydomain where id='$vo->id'";
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
//		$titlename= $re->titlename;
//		$startdate= $re->startdate;
//		$enddate= $re->enddate;
        $_sid=$re->_sid;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		if($_sid==$_SESSION['securitykey']){
		try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
//			  $recordCount=parent::getTotalCount($conn,'mydomain');
			}
			$msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName
	         from mydomain m ";
          
	        $msql.=" order by m.createDate desc limit $rowStart,$pageSize";
			
	         $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
		  		 $v=new Domain();
		  		 $v->id=$row['id'];
		  		 $v->name=$row['name'];
		  		 $v->createDate= $row['createDate'];
		  		 $v->descript=$row['descript'];
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

	public function generatorDomainMenu($condition) {
	    $re=json_decode($condition);
        $_sid=$re->_sid;
		$sql0="select * from mydomain ";
		if($_sid==$_SESSION['securitykey']){
		 try{
		 	 $conn=DBUtil::getConnection();
			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
			$message=array();
			  while ($row=@mysql_fetch_array($result0)) {
			  	 $v=new Domain();
//		  		 $v->id=$row['id'];
		  		 $v->name=$row['name'];
//		  		 $v->createDate=date("Y年m月d日", strtotime($row['createDate']));
//		  		 $v->content=$row['content'];
//		  		 $v->creator=$row['creator'];
		  		 array_push($message,$v);
			  }
			
			  $messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="生成域名菜单数据失败";
	     }            
        $wenjian = fopen(APPROOT.DOMAINMENUPATH,'w');
            if($wenjian){
                fwrite($wenjian,urlencode(json_encode($message))); 
                $message='生成域名菜单成功!';
            }        
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
        return MessageUtil::getMessage($messageSucess,'array',$message);        
	}
	
}
?>
