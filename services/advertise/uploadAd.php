<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
    include '../setting.php';
	require_once APPROOT.'base/JSON.php';
	require_once APPROOT.'util/MinnUtil.php'; 
	require_once 'Advertise.php';
	require_once APPROOT.'util/DBUtil.php';
	require_once APPROOT.'base/Base.php';
	require_once APPROOT.'util/MessageUtil.php';
    $advertise;
     $message;
     $messageSucess;
	if($_POST['method']=="upload"){
		
		//$filename = $_POST['upfilename'];//."_".date("Ymdhis")."_".rand(100,999).substr($_FILES['Filedata']["name"],strrpos($_FILES['Filedata']["name"],"."));
	

		
	    $temploadfile = $_FILES['Filedata']['tmp_name']; 
	    $obj=json_decode(urldecode($_POST['advertise']));
	    $advertise=new Advertise();
	     MinnUtil::obj2Map($obj,$advertise);
	     
	    $uploadfile = APPROOT.UPLOADADVERTISEPATH.$advertise->filename; 
	    
	    try{
	    	$conn=DBUtil::getConnection();
         	@mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	$keyArr=array("date"=>"createDate");
	    	
    	   if($advertise->id!=''){
    	   	  
    	      $msql="select * from advertise where id='$advertise->id'";
		 	  $result=@mysql_query($msql,$conn) or die(@mysql_error());
		 	   while ($row =@mysql_fetch_array($result)) {
		  		if($row['filename']!=$advertise->filename){
		 		     $uploadfilet = APPROOT.UPLOADADVERTISEPATH.$row['filename']; 
			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	      if(!unlink($uploadfilet))
		                      throw new Exception('更新删除失败!');
		  		}
		  		}
    	     	$upsql="update advertise set filename='$advertise->filename',sourcename='$advertise->sourcename'
    	     	   ,url='$advertise->url'  where id='$advertise->id'";
    	     	@mysql_query($upsql,$conn) or die(@mysql_error());
    	     }
	    	
		    if(!move_uploaded_file($temploadfile , $uploadfile)){
		 		throw new Exception('上传失败!');
		 		//表示返回失败
		 	}
		 	
		 	 @mysql_query('COMMIT',$conn) or die(@mysql_error());
          	 $message="上传成功";
          	 $messageSucess= 1;
          	 DBUtil::closeConn($conn);
	    }catch(Exception $e){
	    	 @mysql_query('ROLLBACK');
	    	$messageSucess=0;
	    	$message='上传失败!';
	    }
//	    $advertise->_explicitType='';
	   echo urlencode(json_encode(MessageUtil::getMessage($messageSucess,'mvc.model.advertise.vo.Advertise',$message,$advertise)));
	 
	}
	
	
?>