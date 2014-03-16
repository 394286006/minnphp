<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Ctype.php';
require_once 'ICtypeService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
@session_start();
class CtypeService extends Base implements ICtypeService{
	
	/**
	 * 添加商品类型（商品类型）
	 * @param $info
	 */

	public function add($info){
		  $ctypeobj= json_decode($info);
		  $ctype = new Ctype();
//		  $person->loginName="minn";
//	      $person->email="chenzhimin84@126.com";
          MinnUtil::obj2Map($ctypeobj,$ctype);
          $keyArr=array("date"=>"createDate");
          $result=parent::addInfo("category",$ctype,$keyArr);
         if($ctype->_sid==$_SESSION['securitykey']){
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
//          $ctMenu=new CtypeMenu();
//           MinnUtil::obj2Map($ctype,$ctMenu);
          return MessageUtil::getMessage($messageSucess,$ctype->_explicitType,$message,$ctype);
	}

	/**
	 * 查找用户
	 * @param  $condition
	 */
	public function query($condition) {
		$conn=DBUtil::getConnection();
		$re=json_decode($condition);
		$_sid=$re->_sid;
		$type_name= $re->type_name;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		if($_sid==$_SESSION['securitykey']){
		if($recordCount==-1){
		  $recordCount=parent::getTotalCount($conn,'category');
		}
		$sql="select ct.*,(select opr_name_ch from operator where id=ct.creator) as _creatorName 
		,(case ct.category
                when  0 then '顶级菜单' 
                when  1 then '一级菜单'
                when  2 then '二级菜单'
             end  ) as _categoryName ,
             if(ct.pid is not null,(select ct0.name from category ct0 where ct0.id=ct.pid),'顶级菜单')
         as _parentName from category ct where ct.name like '%$type_name%'  order by createDate desc limit $rowStart,$pageSize";
		
         $result=@mysql_query($sql,$conn) ;//or die(mysql_error());
          
		$arr=array();
		  while ($row =@mysql_fetch_array($result)) {
		  		 $ct=new Ctype();	
		  		 $ct->id=$row['id'];
		  		 $ct->name=$row['name'];
		  		 $ct->createDate=$row['createDate'];
		  		 $ct->category=$row['category'];
		  		 $ct->creator=$row['creator'];
		  		 $ct->descript=$row['descript'];
		  		 $ct->pid=$row['pid'];
		  		 $ct->_categoryName=$row['_categoryName'];
		  		 $ct->_parentName=$row['_parentName'];
		  		 $ct->_creatorName=$row['_creatorName'];
		  		 array_push($arr,$ct);
		  }
		  $message = json_encode($arr); 
		 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		return MessageUtil::getMessage(3,'array',$message,$recordCount);
	}
	
	/**
	 * 查询商品类别
	 *
	 * @return unknown
	 */
	public function queryCtypeMenu($condition) {
	
		$sql="select ct.* from category ct where ct.category=0 ";
			$re=json_decode($condition);
		$_sid=$re->_sid;
		if($_sid==$_SESSION['securitykey']){
		$conn=DBUtil::getConnection();
		$result=@mysql_query($sql,$conn);
		$message="<node label='顶级菜单' id='' category='0'/>";
		while ($row =@mysql_fetch_array($result)) {
		  	$message.="<node label='".$row['name']."' id='".$row['id']."' category='".$row['category']."'/>";
		  	
		  	
		}
		 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		return MessageUtil::getMessage(3,'array',$message);
	}

	/**
	 * 更新商品类型信息
	 * @param  $info
	 */
	public function update($info) {
		  $ctype = new Ctype(); 
		   $ctypeobj= json_decode($info);
		   MinnUtil::obj2Map($ctypeobj,$ctype);
		 if($ctype->_sid==$_SESSION['securitykey']){
		 $conn=DBUtil::getConnection();
		$sql="update category set name='$ctype->name',descript='$ctype->descript' where id='$ctype->id'";
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
//          $ctMenu=new CtypeMenu();
//           MinnUtil::obj2Map($ctype,$ctMenu);
		 return MessageUtil::getMessage($messageSucess,$ctype->_explicitType,$message,$ctype);
	}
	/**
	 * 删除用户
	 * @param  $info
	 */
	public function delete($info) {
		
		$ctype=json_decode($info); 
		  $type_id= $ctype->id;
		if($ctype->_sid==$_SESSION['securitykey']){
		try{
		  $conn=DBUtil::getConnection();
		  @mysql_query('START TRANSACTION',$conn) or die(mysql_error());
		  $merchsql="select * from merchandise where category_id='$type_id'";
		  $merchresult=@mysql_query($merchsql,$conn) or die(@mysql_error());
		 
		   while ($merchrow =@mysql_fetch_array($merchresult)) {
		  	 $msqlq="select * from mcd_phone where mcd_id='".$merchrow['id']."'";
         	 $result=@mysql_query($msqlq,$conn) or die(@mysql_error());
         	 while ($row =@mysql_fetch_array($result)) {
         	   	   $imgpath1=$row['imgpath'];//.".".$row['level1type'];
         	   	   $imgpath2=$row['imgpath'];//.".".$row['level2type'];
         	   	   $uploadfilet = APPROOT.UPLOADDIR.IMGLEVEL1.$imgpath1; 
			      if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
			      	 $uploadfilet = APPROOT.UPLOADDIR.IMGLEVEL2.$imgpath2; 
			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
         	  }
         	 
//         	 $dsql="delete from discount where dc_id='".$merchrow['id']."'";
//         	 @mysql_query($dsql,$conn) or die(@mysql_error());
         	 $psql="delete from mcd_phone where mcd_id='".$merchrow['id']."'";
         	 @mysql_query($psql,$conn) or die(@mysql_error());
//         	 $msql="delete from merchandise where id='".$merchrow['id']."'";
//         	 @mysql_query($msql,$conn) or die(@mysql_error());
		    }
		  $sql="delete from merchandise where category_id='$type_id'";
		   @mysql_query($sql,$conn) or die(@mysql_error());
		
		  $sql="delete from category where id='$type_id'";
		  @mysql_query($sql,$conn) or die(@mysql_error());
//		echo 'dddddddddddd'.$result;
         
		  @mysql_query('COMMIT',$conn) or die(mysql_error());
    	
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
          return MessageUtil::getMessage($messageSucess,"string",$message);
	}

}
?>