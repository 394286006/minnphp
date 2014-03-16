<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
header("Content-type: text/html;charset=utf-8");
require_once 'dbUtil.php';
require_once 'xmlUtil.php';
require_once 'Page.inc';
if(isset($_REQUEST["pageInfo"])) {
//$page = "<pageInfo><curPage>3</curPage><startPage>1</startPage><endPage>3</endPage></pageInfo>";
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
  $pageC->getTotalCount($connection);
  
   $p=$pageC->getPage($curPage,$startPage,$endPage);
    $rowStart=($curPage-1)*$pageSize;
  // $p=$p."<p></p>";
  header('Content-type: text/xml');
  // echo  $p;
  // echo "";
  $sql = "SELECT id ,name,createdate,content FROM lyb order by createdate desc limit $rowStart,$pageSize";
 //header('Content-type: text/xml');
  //try{
  	
  $result=@mysql_query($sql,$connection);
  $u="<lybInfo>";
  $u="$u"."$p"."<lybs>";
  
  //$u+=$pageC->getPage($curPage,$startPage,$endPage)+"<lybs>";
  while ($row =@mysql_fetch_array($result)) {
    	$id=$row[0];
    	$name=$row[1];
    	$cd=$row[2];
    	$dt1=new DateTime("@$cd+8 hours");
    	$createdate=$dt1->format('Y-m-d H:i:s');
    	$content=$row[3];
    	$u.="<lyb><id>$id</id><name>$name</name><createdate>$createdate</createdate><content>$content</content></lyb>";
    	
      }
    	$u="$u"."</lybs></lybInfo>";
        //$u=new User($un,$id);
    	$_SESSION['user']=$u;
    	 echo $u;
 
  //}catch(ErrorException $e)
 // {
  //	$islogin=0;
  //	print_r($e);
 // }
 
}

if(isset($_REQUEST["lybInfo"])) {
 //$page = "<pageInfo><curPage>1</curPage><startPage>1</startPage><endPage>0</endPage></pageInfo>";
    $lyb = trim($_REQUEST["lybInfo"]);
    $rootTagName="LYB";
    $isSucess=1;
	$xml=xml2KV($lyb,$rootTagName);
	$id=time()+rand(200,500);
	$name=$xml[0]->NAME;
	$content=$xml[0]->CONTENT;
	$createdate=time();
	$sql="insert into lyb(id,name,content,createdate) values('$id','$name','$content','$createdate')";
    //echo json_encode($userName);
   
    $connection=getConnection();
    try{
    $result=@mysql_query($sql,$connection);
   
    }catch(ErrorException $e)
    {
  	  $isSucess=0;
  	  echo $e;
    }
      header('Content-type: text/xml');
      // echo $sql;
	echo "<user><id>$isSucess</id></user>";
}
?>