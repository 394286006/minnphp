<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
    include '../setting.php';
	require_once APPROOT.'base/JSON.php';
//	$uploaddir = APPROOT.'uploadfile/'; 
	require_once APPROOT.'util/MinnUtil.php'; 
	require_once APPROOT.'photo/Photo.php';
	require_once APPROOT.'util/DBUtil.php';
	require_once APPROOT.'base/Base.php';
	require_once APPROOT.'util/MessageUtil.php';
//	$level1="imglevel1/";
//	$level2="imglevel2/";
	$uploadfile="";
	$message="";
	$messageSucess=0;
	if($_POST['method']=="upload"){
		
		$filename = $_POST['upfilename'];//."_".date("Ymdhis")."_".rand(100,999).substr($_FILES['Filedata']["name"],strrpos($_FILES['Filedata']["name"],"."));
		 if($_POST['level']=="level1"){
		 	$uploadfile = APPROOT.UPLOADDIR.IMGLEVEL1.$filename; 
		 }
		 if($_POST['level']=="level2"){
		 	$uploadfile = APPROOT.UPLOADDIR.IMGLEVEL2.$filename; 
	     }
	    $temploadfile = $_FILES['Filedata']['tmp_name']; 
	    $photoobj=json_decode(urldecode($_POST['photo']));
	    $photo=new Photo();
	     MinnUtil::obj2Map($photoobj,$photo);
	    try{
	    	$conn=DBUtil::getConnection();
         	@mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	$keyArr=array("date"=>"createDate");
    	  
    	    if($photo->mcd_id!=''&&$photo->id==''){
    	     	 $photo->id=time().Base::getSRM();
	    	     $psql=MinnUtil::buildInserSql("mcd_phone",$photo,$keyArr);
	    	     @mysql_query($psql,$conn) or die(@mysql_error());
    	     }else if($photo->mcd_id!=''&&$photo->id!=''){
    	     	$upsql="update mcd_phone set imgname='$photo->imgname',level1type='$photo->level1type',level2type='$photo->level2type',descript='$photo->descript'
    	     	     where id='$photo->id'";
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
	    
	   echo urlencode(json_encode(MessageUtil::getMessage($messageSucess,$photo->_explicitType,$message,$photo)));
	 
	}
	
	if($_POST['method']=="delfile"){
		
		$files=$_POST['files'];
//		$fileobjs=json_decode(urldecode($files));
		$photoobjs=json_decode(urldecode($_POST['photos']));
		$photo='';
		try{
    	    $conn=DBUtil::getConnection();
		    for($i=0;$i<count($photoobjs);$i++){
		    	$photo=new Photo();
		        MinnUtil::obj2Map($photoobjs[$i],$photo);
				$filename = $photo->imgpath;
				 if($_POST['level']=="all"){
			      	 $uploadfilet = APPROOT.UPLOADDIR.IMGLEVEL1.$filename; 
			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
			      	 $uploadfilet = APPROOT.UPLOADDIR.IMGLEVEL2.$filename; 
			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
				 }else{
		    	          @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
					      if($_POST['level']=="level1"){
					 	     $uploadfile = APPROOT.UPLOADDIR.IMGLEVEL1.$filename; 
					       }
					      if($_POST['level']=="level2"){
					 	     $uploadfile = APPROOT.UPLOADDIR.IMGLEVEL2.$filename; 
					      }
					     
					     if(file_exists($uploadfile)&&is_file($uploadfile)){
				                  if(!unlink($uploadfile))
				                      throw new Exception('删除失败!');
				 	      }
						
			    	     $psql="";
			    	     if($photo->id!=''){
			    	     if(($photo->level1type==''&&$photo->level2type=='')||
				    	    ($_POST['level']=="level1"&&$photo->level2type=='')||
				    	    ($_POST['level']=="level2"&&$photo->level1type=='')){
				    	    	  $psql="delete from mcd_phone where id='$photo->id'";
				    	    }else if($_POST['level']=="level1"){
					 	          $psql="update mcd_phone set level1type='' where id='$photo->id'";
				    	    }else if($_POST['level']=="level2"){
				    	    	  $psql="update mcd_phone set level2type='' where id='$photo->id'";
				    	    }
					        @mysql_query($psql,$conn) or die(@mysql_error());
			    	    }
		     }
		     @mysql_query('COMMIT',$conn) or die(@mysql_error());
		     }
            $message="删除文件成功！";
            $messageSucess= 1;
            DBUtil::closeConn($conn);
		}catch(Exception $e){
			@mysql_query('ROLLBACK');
	    	$messageSucess=0;
	    	$message='删除失败!';
		}

       echo urlencode(json_encode(MessageUtil::getMessage($messageSucess,$photo->_explicitType,$message)));
	}
?>