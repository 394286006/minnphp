<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'permission/ActionService.php';
require_once 'base/JSON.php';
/**
 * test case.
 */

		echo '*********************Test OperatorService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$str='{"creator":"","_sid":"cb138c0ea6e0ec0ac07cb501db562b47","actionnum":"34","_color":"","_creatorName":"","descript":"23423","id":"","name":"234","_groups":null,"createDate":""}';
//		echo "\n 模拟客户端传过来的字符处:\n".$operator."<br>\n";
		$obj=new ActionService();
//        $p=$obj->login($login);
//         echo $p->loginName;
//        $login='{"id":"","_sid":"","loginName":"zzxc","password":"zxc"}';
		//测试添加函数
		$obj->add($str);
		
		
		//查询条件{"user_name":"sddf"}
		
	
//		$obj->query($condition);
//      $obj->delete($condition);
//        $updateStr='{"opr_name_ch":"sadfafafda","password":null,"qq_msn":null,"color":2,"person_phone":null,"address":null,"createDate":null,"creator":null,"opr_name_en":"chen","id":"128403544781e3225c6ad49623167a4309eb4b2e75","office_phone":null,"_sid":null,"email":null}';
//		$obj->update($updateStr);
        //测试打印基类函数
//        echo "<br>测试打印基类函数：getSRM（）<br>\n";
//        $r=$obj->getSRM();
//        echo $r."<br>\n";
      echo '<br>*********************Test OperatorService.php end **************************';
?>