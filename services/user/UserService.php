<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'User.php';
require_once 'IUserService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
  @session_start();
class UserService extends Base implements IUserService{
	
	/**
	 * 用户登录
	 * @param 用户类型参数 $login
	 */
	public function login($info){
	  
	    $user = new User();  
        $obj= json_decode($info);
		MinnUtil::obj2Map($obj,$user);
//        "{'userName_en':'登录名不能为空','password':'密码不能为空'}"
     if($_SESSION['securitykey']==$user->_sid){
        $message= parent::checkNotNullProperty("{'userName_en':'登录名不能为空','password':'密码不能为空'",$user);
        $messageType=$user->_explicitType;
		if($message==''){
	        	$messageSucess=1;
	        	$conn=DBUtil::getConnection();
	        	$sql="select * from user where userName_en='$user->userName_en' and password='$user->password'";
	            $result=@mysql_query($sql,$conn);
				   if($row =@mysql_fetch_array($result)) {
				  	 $user->id=$row['id'];
				  	 $user->userName_en=$row['userName_en'];
				  	 $user->userName_ch=$row['userName_ch'];
				  	 $operator->password=$row['password'];
				  	 $user->email=$row['email'];
				  	 $user->qq_msn=$row['qq_msn'];
				  	 $user->officePhone=$row['officePhone'];
				  	 $user->homePhone=$row['homePhone'];
				  	 $user->detailAddress=$row['detailAddress'];
				  	 $user->createDate=$row['createDate'];
				  	 $user->backemail=$row['backemail'];
				  	 $user->backpwd=$row['backpwd'];
				  	  $message=$user;
				  }else{
				  	$messageSucess=0;
	            	$message='没有找到该用户，请检查您的用户名或密码是否正确！';
				  }
			     
	        }else
	        {
	        	$messageSucess=0;
	        	
	        }
     }
	        else{
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
		  $user = new User();
          $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$user);
		  if($_SESSION['securitykey']==$user->_sid){
	          $keyArr=array("date"=>"createDate");
	          $result=parent::addInfo("user",$user,$keyArr);
	          if($result==''){
	          	 $messageSucess=0;
	             $message="注册失败";
	          }else{
	          	$messageSucess=1;
	          	$message="注册成功";
	          }
		   }else{
		   	    $messageSucess=0;
	            $message='非法操作！';
		   }
          return MessageUtil::getMessage($messageSucess,$user->_explicitType,$message,$user);
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
		 $user = new User();  
		 $obj= json_decode($info);
		 MinnUtil::obj2Map($obj,$user);
	   if($user->_sid==$_SESSION['securitykey']){
	    try{
           $conn=DBUtil::getConnection();
		   $sql="update user set password='$user->password'  where id='$user->id'";
//        echo $sql;
		   @mysql_query($sql,$conn) or die(@mysql_error());
		
          	$messageSucess=1;
          	$message="更新密码成功";
        
	   	  }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新密码失败:".$e;
         }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		 return MessageUtil::getMessage($messageSucess,$user->_explicitType,$message);
	}
	
	public function checkBackEmail($info){
		 $user = new User();
          $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$user);
		 
		  if($_SESSION['securitykey']==$user->_sid){
		   try{
	         	$sql="select *  from user op where op.backemail='$user->backemail'";
	         	 $conn=DBUtil::getConnection();
                 $result=@mysql_query($sql,$conn) or die(@mysql_error());
                 $num_rows = @mysql_num_rows($result);
		          if($num_rows>0){
		          	 $messageSucess=0;
		             $message="该邮箱已被注册!";
		          }else{
			           $messageSucess=1;
				       $message="可以注册!";
		          }
		   }catch(Exception $e){
         	 $messageSucess=0;
             $message="查找失败";
             echo $e;
            }
		  }else{
		   	    $messageSucess=0;
	            $message='非法操作！';
		   }
		 
          return MessageUtil::getMessage($messageSucess,$user->_explicitType,$message,$user);
	}
	
	public function backPwd($info){
		 $user = new User();  
		 $obj= json_decode($info);
		 MinnUtil::obj2Map($obj,$user);
	   if($user->_sid==$_SESSION['securitykey']){
	    try{
            $conn=DBUtil::getConnection();
            $sql="select *  from user op where op.backemail='$user->backemail' and op.userName_en='$user->userName_en'";
             $result=@mysql_query($sql,$conn) or die(@mysql_error());
             $num_rows = @mysql_num_rows($result);
            if($num_rows==0){
          	 $messageSucess=0;
             $message="该邮箱或用户不存在!";
            }else{
            
			    $sql="update user set backpwd='1'  where backemail='$user->backemail'";
			    @mysql_query($sql,$conn) or die(@mysql_error());
			
	          	$messageSucess=1;
	          	$message="3天内将会把密码发到您的邮箱，请注意查收!";
            }
	   	  }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败:".$e;
         }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		 return MessageUtil::getMessage($messageSucess,$user->_explicitType,$message);
	}
	/**
	 * 查找用户
	 * @param  $condition
	 */
	public function query($condition) {
		$conn=DBUtil::getConnection();
		$re=json_decode($condition);
		$_sid=$re->_sid;
		$user_name= $re->user_name;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		if($_sid==$_SESSION['securitykey']){
		if($recordCount==-1){
		  $recordCount=parent::getTotalCount($conn,'user'," where userName_en like '%$user_name%' or userName_ch like '%$user_name%'");
		}
		$sql="select * from user op where op.userName_en like '%$user_name%' or op.userName_ch like '%$user_name%' order by createDate desc limit $rowStart,$pageSize";
//		echo $sql;
		$result=@mysql_query($sql,$conn);
		$arr=array();
		  while ($row =@mysql_fetch_array($result)) {
		  	 $user=new User();	
		  	 $user->id=$row['id'];
		  	 $user->userName_en =$row['userName_en'];
		  	 $user->userName_ch=$row['userName_ch'];
		  	 $user->password=$row['password'];
		  	 $user->email=$row['email'];
		  	 $user->qq_msn=$row['qq_msn'];
		  	 $user->address=$row['detailAddress'];
		  	 $user->createDate=$row['createDate'];
		  	 $user->backpwd=$row['backpwd'];
		  	 $user->backemail=$row['backemail'];
		  	array_push($arr,$user);
		  }
		  $message = json_encode($arr); 
		 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		  return MessageUtil::getMessage(3,'array',$message,$recordCount);
	}

	/**
	 * 更新用户信息
	 * @param  $info
	 */
	public function update($info) {
		 $user = new User();  
		 $obj= json_decode($info);
		 MinnUtil::obj2Map($obj,$user);
	   if($user->_sid==$_SESSION['securitykey']){
	    try{
           $conn=DBUtil::getConnection();
		   $sql="update user set userName_en='$user->userName_en',userName_ch='$user->userName_ch',
		password='$user->password',email='$user->email',qq_msn='$user->qq_msn',backemail='$user->backemail'  where id='$user->id'";
//        echo $sql;
		   @mysql_query($sql,$conn) or die(@mysql_error());
		
          	$messageSucess=1;
          	$message="更新成功";
        
	   	  }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败:".$e;
         }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		 return MessageUtil::getMessage($messageSucess,$user->_explicitType,$message);
	}
	/**
	 * 删除用户
	 * @param  $info
	 */
	public function delete($info) {
		
	}
	public function userCheck($info){
		  $user = new User();
          $obj= json_decode($info);
		  MinnUtil::obj2Map($obj,$user);
		 
		  if($_SESSION['securitykey']==$user->_sid){
		   try{
	         	$sql="select *  from user op where op.userName_en='$user->userName_en'";
	         	 $conn=DBUtil::getConnection();
                 $result=@mysql_query($sql,$conn) or die(@mysql_error());
                 $num_rows = @mysql_num_rows($result);
		          if($num_rows>0){
		          	 $messageSucess=0;
		             $message="该用户名已经存在!";
		          }else{
			           $messageSucess=1;
				       $message="可以注册!";
		          }
		   }catch(Exception $e){
         	 $messageSucess=0;
             $message="查找失败";
             echo $e;
            }
		  }else{
		   	    $messageSucess=0;
	            $message='非法操作！';
		   }
		 
          return MessageUtil::getMessage($messageSucess,$user->_explicitType,$message,$user);
	}
}
?>