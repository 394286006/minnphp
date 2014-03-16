<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'user/User.php';
require_once 'util/MinnUtil.php';
		echo '*********************Test MinnUtilTest.php start **************************<br>\n';
		//模拟客户端传过的字符串
		$login='{"_sid":"sidtest",phone":"13430281045","email":"chenzhimin84@126.com","loginName":"min","password":"123456"}';
		echo "模拟客户端传过的字符串:".$login."<br>\n";
		$person = new User(); 
		MinnUtil::josonToMap($login,$person);
//          echo $person->loginName;
         //测试生成的插入sql语句
         echo "测试生成的插入sql语句<br>\n";
		 $sql=MinnUtil::buildInserSql("user",$person);
          echo $sql."<br>\n";
       //测试生成的更新sql语句
        echo "测试生成的更新sql语句<br>\n";
        $sql=MinnUtil::buildUpdateSql("user",$person,"id");
        echo $sql."<br>\n";
      echo '*********************Test MinnUtilTest.php end **************************';
?>