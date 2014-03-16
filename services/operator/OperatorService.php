<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Operator.php';
require_once APPROOT.'permission/GroupTb.php'; 
require_once 'IOperatorService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'base/JSON.php';
require_once APPROOT.'util/MessageUtil.php';
@session_start();
class OperatorService extends Base implements IOperatorService{
	
	/**
	 * 用户登录
	 * @param 用户类型参数 $login
	 */
	public function login($info){
		$obj= json_decode($info);
	    $operator = new Operator();  
        MinnUtil::obj2Map($obj,$operator);
       // if($operator->_sid==$_SESSION['securitykey']){
        if(true){
        $message= parent::checkNotNullProperty("{'opr_name_en':'登录名不能为空','password':'密码不能为空'}",$operator);
        $messageType=$person->_explicitType;
        if($message==''){
        	$messageSucess=3;
        	$conn=DBUtil::getConnection();
        	 @mysql_query('START TRANSACTION',$conn) or die(mysql_error());
        	$sql="select operator.* from operator where opr_name_en='$operator->opr_name_en' and password='$operator->password'";
            $result=@mysql_query($sql,$conn);
			   if($row =@mysql_fetch_array($result)) {
			  	 $operator->id=$row['id'];
	//		  	 $operator->opr_name_en=$row['opr_name_en'];
			  	 $operator->opr_name_ch=$row['opr_name_ch'];
	//		  	 $operator->password=$row['password'];
			  	 $operator->email=$row['email'];
			  	 $operator->qq_msn=$row['qq_msn'];
			  	 $operator->office_phone=$row['office_phone'];
			  	 $operator->person_phone=$row['person_phone'];
			  	 $operator->address=$row['address'];
			  	 $operator->creator=$row['creator'];
			  	 $operator->pnode=$row['pnode'];
			  	 $operator->node=$row['node'];
			  	 $operator->createDate=$row['createDate'];
			  	  
			  	  
			  	 $grouparr=array();
		  		 $psql="select * from grouptb as a,group_user as ag where a.id=ag.group_id and ag.user_id='".$row['id']."'";  
			  	 $resultp=@mysql_query($psql,$conn) or die(@mysql_error());
			  	 while ($rowp =@mysql_fetch_array($resultp)) {
			  	   	 $av=new GroupTb();
			  		 $av->id=$rowp['id'];
			  		 $av->name=$rowp['name'];
			  		 $av->createDate= $rowp['createDate'];
			  		 $av->descript=$rowp['descript'];
			  		 $av->creator=$rowp['creator'];
			  		 $av->rolenum=$rowp['rolenum'];
			  		 $av->_creatorName=$rowp['_creatorName'];
				  		
				  	 array_push($grouparr,$av);
				  	 if($operator->_roleNums!=''){
				  	 	$operator->_roleNums=$operator->_roleNums.',';
				  	 }
				  	 $operator->_roleNums=$operator->_roleNums.$rowp['rolenum'];
			  	 }
			  	
			  	 $operator->_roles=$grouparr;
			  	 if($operator->_roleNums!=''){
			  	 	$tsql="select DISTINCT actionnum from action a,action_group ag,grouptb gt where a.id=ag.action_id and ag.group_id=gt.id and gt.rolenum in($operator->_roleNums)";
 				
			  	  $resulta=@mysql_query($tsql,$conn) or die(@mysql_error());
			  	 
			  	  while ($rowa =@mysql_fetch_array($resulta)) {
				  	  if($operator->_actionNums!=''){
				  	    $operator->_actionNums=$operator->_actionNums.',';
				  	  }
				  	   $operator->_actionNums=$operator->_actionNums.$rowa['actionnum'];
				  	   
			  	 }
			  	 @mysql_query('COMMIT',$conn) or die(mysql_error());
			  
			  	   $message=$operator;
			  
			  	 }
          	    DBUtil::closeConn($conn);
			  }else{
			  	$messageSucess=0;
            	$message='没有找到该用户，请联系管理员！';
			  }
		     
        }else
        {
        	$messageSucess=0;
        	
        }
        }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		return  MessageUtil::getMessage($messageSucess,$messageType,$message);
	}
	
	/**
	 * 添加用户（用户注册）
	 * @param $login
	 */

	public function add($info){
		  $obj= json_decode($info);
		  $vo = new Operator();
           MinnUtil::obj2Map($obj->operator,$vo);
         // MinnUtil::obj2Map($operatorobj,$operator);
          $keyArr=array("date"=>"createDate");
            $_roles=$person->_roles;
         if($vo->_sid==$_SESSION['securitykey']){
           //if(true){
         	try{
         		$conn=DBUtil::getConnection();
         		$pnode=$vo->pnode;
              
         		$sql="select max(node) node from operator where pnode='$pnode'";
               
         		$result=@mysql_query($sql,$conn)  or die(@mysql_error());
				  $node="-1";
				  while ($row =@mysql_fetch_array($result)) {
				    $node=$row['node'];
				  }
				 	
				  if($node==''){
				  	$node=$pnode.'1';
				  }else{
				  	 
				     $childnode=((int)substr($node,strlen($pnode),1))+1;
				     $node=$pnode.$childnode;
				  }
				  
	         	 $vo->node=$node;
	         	 $vo->id=time().self::getSRM();
	         	 $vo->createDate=self::getCurDate();
	         	 $msql=MinnUtil::buildInserSql("operator",$vo,$keyArr);
	         	 
	    	     @mysql_query($msql,$conn) or die(@mysql_error());
	    	     
	    	     
	         	for($j=0;$j<count($_roles);$j++){
			    $gv=new GroupTb();
	    	    MinnUtil::obj2Map($_roles[$j],$gv);
	    	    $psql="insert into group_user(user_id,group_id,creator) values('$person->id','$gv->id','$obj->id')";
	//    	    echo $psql;
		        @mysql_query($psql,$conn) or die(@mysql_error());
			   }
		
	    	      $messageSucess=1;
	          	 $message="添加成功";
          	     DBUtil::closeConn($conn);
       //  $result=parent::addInfo("operator",$operator,$keyArr);
          }catch(Exception $e){
//         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="添加失败";
         }
           }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,$operator->_explicitType,$message,$vo);
	}
	/**
	 * 用户退出
	 */
	public function logout() {
		
	}
	/**
	 * 更新密码
	 * @param  $login
	 */
	public function updatePwd($info) {
		
	}
	/**
	 * 查找用户
	 * @param  $condition
	 */
	public function query($condition) {
		$conn=DBUtil::getConnection();
		$re=json_decode($condition);
		$_sid=$re->_sid;
		$node=$re->node;
		$user_name= $re->user_name;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		$curoperatorid=$re->id;
	if($_sid==$_SESSION['securitykey']){
	//if(true){
		if($recordCount==-1){
		  $recordCount=parent::getTotalCount($conn,'operator'," where (opr_name_en like '%$user_name%' or opr_name_ch like '%$user_name%') and node like '$node%'");
		}
//		$
//		parentFound($conn,$curoperatorid);
		
		$sql="select op.*,(select opr_name_ch from operator where id=op.creator) as _creatorName from operator op where (op.opr_name_en like '%$user_name%' or op.opr_name_ch like '%$user_name%') and op.node like '$node%' order by createDate desc limit $rowStart,$pageSize";
	
		$result=@mysql_query($sql,$conn);
		$arr=array();
		  while ($row =@mysql_fetch_array($result)) {
		  	 $operator=new Operator();	
		  	 $operator->id=$row['id'];
		  	 $operator->opr_name_en=$row['opr_name_en'];
		  	 $operator->opr_name_ch=$row['opr_name_ch'];
		  	 $operator->password=$row['password'];
		  	 $operator->email=$row['email'];
		  	 $operator->qq_msn=$row['qq_msn'];
		  	 $operator->office_phone=$row['office_phone'];
		  	 $operator->person_phone=$row['person_phone'];
		  	 $operator->address=$row['address'];
		  	 $operator->creator=$row['creator'];
		  	 $operator->pnode=$row['pnode'];
		  	 $operator->node=$row['node'];
		  	 $operator->createDate=$row['createDate'];
		  	 $operator->_creatorName=$row['_creatorName'];
		  	 
		     $grouparr=array();
		  		 $psql="select * from grouptb as a,group_user as ag where a.id=ag.group_id and ag.user_id='".$row['id']."'";  
			  	 $resultp=@mysql_query($psql,$conn) or die(@mysql_error());
			  	 while ($rowp =@mysql_fetch_array($resultp)) {
			  	   	 $av=new GroupTb();
			  		 $av->id=$rowp['id'];
			  		 $av->name=$rowp['name'];
			  		 $av->createDate= $rowp['createDate'];
			  		 $av->descript=$rowp['descript'];
			  		 $av->creator=$rowp['creator'];
			  		 $av->rolenum=$rowp['rolenum'];
			  		 $av->_creatorName=$rowp['_creatorName'];
				  		
				  	 array_push($grouparr,$av);
			  	 }
			  	 $operator->_roles=$grouparr;
		  	array_push($arr,$operator);
		  }
		  $message = json_encode($arr); 
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		  return MessageUtil::getMessage(3,'array',$message,$recordCount);
	}

	private function parentFound($conn,$parentid){
		
		
		$sql='select op.* operator op';
		
		
	}
	
	/**
	 * 更新用户信息
	 * @param  $info
	 */
	public function update($info) {
		 $obj= json_decode($info);
		 $person = new Operator();  
		   MinnUtil::obj2Map($obj->operator,$person);
		  $_roles=$person->_roles;

		 if($obj->_sid==$_SESSION['securitykey']){
		//if(true){
		 	  try{
//		$result= parent::updateInfo("operator",$person,"id");
        $conn=DBUtil::getConnection();
		$sql="update operator set opr_name_en='$person->opr_name_en',opr_name_ch='$person->opr_name_ch',password='$person->password',email='$person->email',qq_msn='$person->qq_msn',office_phone='$person->office_phone',person_phone='$person->person_phone',address='$person->address' where id='$person->id'";
      
		@mysql_query($sql,$conn);
		$sql="delete from group_user where user_id='$person->id'";
		
		@mysql_query($sql,$conn) or die(@mysql_error());
		for($j=0;$j<count($_roles);$j++){
		    $gv=new GroupTb();
    	   MinnUtil::obj2Map($_roles[$j],$gv);
    	   $psql="insert into group_user(user_id,group_id,creator) values('$person->id','$gv->id','$obj->id')";
    	  
	       @mysql_query($psql,$conn) or die(@mysql_error());
		}
	
          	$messageSucess=1;
          	$message="更新成功";
         
		 }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败";
//             echo $e;
         }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		 return MessageUtil::getMessage($messageSucess,$person->_explicitType,$message);
	}
	/**
	 * 删除用户
	 * @param  $info
	 */
	public function delete($info) {
	
		$operator=json_decode($info); 
		$user_id= $operator->id;
		$_sid=$operator->_sid;
		 if($_sid==$_SESSION['securitykey']){
		$sql="delete from operator where id= '$user_id'";
			$conn=DBUtil::getConnection();
		$result=@mysql_query($sql,$conn) or die(@mysql_error());
//		echo 'dddddddddddd'.$result;
          if($result==1){
          		$messageSucess=1;
          	$message="删除成功";
          }else{
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