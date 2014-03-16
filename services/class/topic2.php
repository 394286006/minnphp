<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
//header("Content-type: text/html;charset=utf-8");
require_once 'dbUtil.php';
require_once 'xmlUtil.php';
require_once 'Page.inc';
if(isset($_REQUEST["pageInfo"])) {
//$page = "<pageInfo><curPage>1</curPage><startPage>1</startPage><endPage>3</endPage></pageInfo>";
    $page = trim($_REQUEST["pageInfo"]);
    $islogin="1";
    $pageSize=3;
    $rootTagName="PAGEINFO";
  $xml=xml2KV($page,$rootTagName);
  $curPage=trim($xml[0]->CURPAGE);
  $startPage=trim($xml[0]->STARTPAGE);
  $endPage=trim($xml[0]->ENDPAGE);
  $connection=getConnection();
  $pageC=new Page();
  $pageC->pageSize=$pageSize;
  $_table="topic";
  $pageC->getTotalCount($connection,$_table);
  
   $p=$pageC->getPage($curPage,$startPage,$endPage);
    $rowStart=($curPage-1)*$pageSize;
  // $p=$p."<p></p>";
  header('Content-type: text/xml');
  // echo  $p;
  // echo "";
  $sql = "select t.topicid as topicid,t.topicname as topicname,t.createdate as createdate,t.content as content,
                t.clickcount as clickcount,td.a as disccount,u.username as username  from user as u,topic as t
				 LEFT JOIN (SELECT count(0) as a ,topic_id FROM discussion group by topic_id) 
				 as td on td.topic_id=t.topicid  where u.id=t.user_id order by td.a 
			 desc limit ".$rowStart.",".$pageSize;
 //header('Content-type: text/xml');
  //try{
    
  $result=@mysql_query($sql,$connection);
   
  $u="<topicInfo>";
  $u="$u"."$p"."<topics>";
 
  //$u+=$pageC->getPage($curPage,$startPage,$endPage)+"<lybs>";
  while ($row =@mysql_fetch_array($result)) {
    	$topicId=$row[0];
    	$topicName=$row[1];
    	$cd=$row[2];
    	$dt1=new DateTime("@$cd+8 hours");
    	$createdate=$dt1->format('Y-m-d H:i:s');
    	$content=$row[3];
    	$clickCount=$row[4];
    	$disCount=$row[5];
    	$user_name=$row[6];
    	$u.="<topic><topicId>$topicId</topicId><topicName>$topicName</topicName>
    	  <createdate>$createdate</createdate><content>$content</content>
    	  <clickCount>$clickCount</clickCount><userName>$user_name</userName>
    	  <disCount>$disCount</disCount></topic>";
    	
      }
    	$u="$u"."</topics></topicInfo>";
    	
    echo $u;
 
  //}catch(ErrorException $e)
 // {
  //	$islogin=0;
  //	print_r($e);
 // }
 
}

//if(isset($_REQUEST["topicInfo"])) {
  //$topic="<topic><userId>1242486064</userId><topicName>��</topicName><content>��</content></topic>";
   $topic="<topic><rr>hbh</rr><aa>hjv</aa><dd>sd </dd><userId>dfʿ���</userId></topic>";  
  //$topic = trim($_REQUEST["topicInfo"]);
    $rootTagName="TOPIC";
    $isSucess=1;

	$topicId=time()+rand(200,500);
	$xml=xml2KV($topic,$rootTagName);
	$topicName=trim($xml[0]->TOPICNAME);
	$content=trim($xml[0]->CONTENT);
	$createdate=time();
	$useId=trim($xml[0]->USERID);
	
	 echo $useId;
	$sql="insert into topic(topicid,topicname,content,createdate,user_id) values('$topicId','$topicName','$content','$createdate','$userId')";
    //echo json_encode($userName);
    try{
	$connection=getConnection();
    $result=@mysql_query($sql,$connection);
    }catch(Exception $e)
    {
    	$isSucess=0;
    	echo $e;
    }
    //  header('Content-type: text/xml');
      // echo $sql;
 
	//echo "<user><id>$topicName</id></user>";
//}

if(isset($_REQUEST["clickInfo"])) {
	 $lyb = trim($_REQUEST["clickInfo"]);
    $rootTagName="TOPIC";
    $isSucess=1;
	$xml=xml2KV($lyb,$rootTagName);
	$tid=$xml[0]->ID;
    $sql="select clickCount from topic where topicid='$tid'";
    //echo json_encode($userName);
  
    try{
    $connection=getConnection();
    $result=@mysql_query($sql,$connection);
    $row =@mysql_fetch_row($result);
    $clickCount=$row[0]+1;
    $updateSql="update topic set clickcount='$clickCount' where topicid='$tid'";
     mysql_query($updateSql,$connection);
   
    }catch(ErrorException $e)
    {
  	  echo $e;
  	 
    }
  
}
?>