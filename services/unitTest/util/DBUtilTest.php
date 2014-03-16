<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require 'util/DBUtil.php';

	echo '*********************Test DBUtil.php start **************************<br>\n';
//      DBUtil::initDBUtil();
      DBUtil::getConnection();
	$conn=DBUtil::getConnection();
	
	//echo $conn."<br>\n";
	$sql = "select * from operator";
	 $result=@mysql_query($sql,$conn);
     $row =@mysql_num_rows($result);
     echo "num:".$row."<br>\n";
//	$conn=DBUtil::getConnection();
//	echo $conn."<br>";
//	$conn=DBUtil::getConnection();
//	echo $conn."<br>";
     echo '<br>*********************Test DBUtil.php end **************************';
?>