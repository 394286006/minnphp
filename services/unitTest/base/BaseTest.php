<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'user/UserService.php';
	echo "*********************Test Base.php start **************************<br>\n";
		//模拟客户端传过来的字符处
		$info='{"phone":"13430281045","email":"chenzhimin84@126.com","loginName":"min","password":""}\n';
		$person = new User();  
	    $person->loginName="minn";
	    $person->email="chenzhimin84@126.com";
        MinnUtil::josonToMap($info,$person);
	   $b=new UserService();
	   $str=$b->checkNotNullProperty("{'loginName':'登录名不能为空','password':'密码不能为空'}",$person);
	   echo $str."<br>\n";
     echo "<br>*********************Test Base.php end **************************\n";
?>