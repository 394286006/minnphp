<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'base/JSON.php';
    include 'setting.php';
	$uploaddir = APPROOT.'uploadfile/'; 

echo '*********************Test CtypeService.php start **************************<br> \n';
	 $files='[{"level1type":"jpg","mcd_id":"","createDate":"","level2type":"","phone_order":0,"sourcename":"上传文件名:日期.jpg","imgname":"日期","creator":"","id":"","imgpath":"_20101229150229.jpg","_sid":""}]';
	$level1="imglevel1/";
	$level2="imglevel2/";
	  $fileobjs= json_decode($files);
	 echo count($fileobjs);
		  for($i=0;$i<count($fileobjs);$i++){
			  $filename = $fileobjs[$i]->imgpath;
		     
		 	     $uploadfile = $uploaddir.$level1.$filename; 
		    echo "uploadfilename:".$uploadfile;
			  if(file_exists($uploadfile)){
	                  unlink($uploadfile);
	                  echo 'deletefile';
	 	      }
	 	      
	 }
	 
 echo '<br>*********************Test CtypeService.php end **************************';
?>