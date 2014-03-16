<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'user/UserService.php';
/**
 * test case.
 */
//	echo '*********************Test UserService.php start **************************<br>\n';
//
//     echo '<br>*********************Test UserService.php end **************************\n';

		echo '*********************Test UserService.php start **************************<br>\n';
		//模拟客户端传过来的字符处
//		$login='{"phone":"13430281045","email":"chenzhimin84@126.com","loginName":"min","password":""}';
		echo "模拟客户端传过来的字符处".$login."<br>\n";
		$obj=new UserService();
//        $p=$obj->login($login);
//         echo $p->loginName;
        $login='{"id":"","_sid":"","loginName":"zzxc","password":"zxc"}';
		//测试添加函数
		$login='{"homePhone":"","officePhone":"","detailAddress":"","zip":"","color":0,"createDate":"","userName_ch":"123","password":"123","city":"","id":"","country":"","_sid":"","userName_en":"123","email":"123","qq_msn":"123"}
		';
//		$obj->add($login);

//		$info='{"pageSize":3,"recordCount":-1,"pageIndex":0,"user_name":""}';
//		$obj->query($info);
		$upStr='{"userName_en":"minn","createDate":"","color":0,"email":"12234@qq.com","zip":"","qq_msn":"21234","backemail":"","userName_ch":"陈志民","country":"","detailAddress":"","homePhone":"","city":"","id":"","officePhone":"","password":"1234","_sid":"55928a73a2d340dccaac3b08bfdbca32"}';
        $obj->update($upStr);
		//测试打印基类函数
//        echo "<br>测试打印基类函数：getSRM（）<br>\n";
//        $r=$obj->getSRM();
//        echo $r."<br>\n";
      echo '<br>*********************Test UserService.php end **************************';
	
?>

