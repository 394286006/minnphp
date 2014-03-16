<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'operator/OperatorService.php';
require_once 'base/JSON.php';
/**
 * test case.
 */

		echo '*********************Test OperatorService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$operator='{"id":"12958839739d64e8c6bacfd2a98415686a6af75cfd","operator":{"node":"","pnode":"011","id":"","_roles":[{"creator":"12958839739d64e8c6bacfd2a98415686a6af75cfd","mx_internal_uid":"C5EFBD7C-FDDB-7787-F140-7DBAB028B881","rolenum":"02","id":"1307535102118f120e664e30d7394651a361a902f2","_color":null,"_creatorName":"minn","descript":"只有查询数据的权限","_sid":null,"name":"测试用户","_actions":[{"creator":"","id":"1307534996285eb263edf5cb049f3f4cc7fa0d2182","_explicitType":"mvc.model.permission.vo.ActionTb","_color":null,"actionnum":"04","_creatorName":null,"descript":"查找功能","_sid":null,"name":"查询","createDate":null}],"createDate":"2011-06-08 20:11:42"}],"_actions":"","_creatorName":"minn","address":"1231","_color":"1","person_phone":"213123","creator":"12958839739d64e8c6bacfd2a98415686a6af75cfd","opr_name_ch":"123123","_sid":"a345fae088d30a663c02ec3b38192fd4","password":"123","opr_name_en":"23","createDate":"","office_phone":"123123","email":"123@qq.com","qq_msn":"123"},"_sid":"a345fae088d30a663c02ec3b38192fd4"}';
//		echo "\n 模拟客户端传过来的字符处:\n".$operator."<br>\n";
		$obj=new OperatorService();
		$loignstr='{"office_phone":"","person_phone":"","address":"","_creatorName":"","pnode":"","_roleNums":"","_roles":null,"_actions":"","password":"1234","email":"","qq_msn":"","_actionNums":"","node":"","id":"","_color":"","creator":"","_sid":"a5bad363fc47f424ddf5091c8471480a","createDate":"","opr_name_en":"chenzhimin","opr_name_ch":""}';
        $p=$obj->login($loignstr);
//         echo $p->loginName;
//        $login='{"id":"","_sid":"","loginName":"zzxc","password":"zxc"}';
		//测试添加函数
		//$obj->add($operator);
		
		
		//查询条件{"user_name":"sddf"}
		$condition='{"user_name":"","node":"011","_sid":"3c8aad396b170bc506e316d9225735b0","pageSize":3,"pageIndex":0,"recordCount":-1}';
	
		//$obj->query($condition);
//      $obj->delete($condition);
        $updateStr='{"id":"12958839739d64e8c6bacfd2a98415686a6af75cfd","operator":{"node":"0111","pnode":"011","id":"1307779852e9287a53b94620249766921107fe70a3","_roles":[{"creator":"12958839739d64e8c6bacfd2a98415686a6af75cfd","mx_internal_uid":"A6B3B16C-5C1B-77C2-A2D9-7EB658A49620","rolenum":"01","id":"1307535054264b04062f16e0a09354779b624c1eff","_color":null,"_creatorName":"minn","descript":"拥有所有权限","_sid":null,"name":"超级用户","_actions":[{"creator":"","id":"13075349486b387ebbcb8020ce186644d4a4669c6a","_color":null,"actionnum":"01","_creatorName":null,"descript":"添加权限","_sid":null,"name":"添加","_explicitType":"mvc.model.permission.vo.ActionTb","createDate":null},{"creator":"","id":"130753496451d92be1c60d1db1d2e5e7a07da55b26","_color":null,"actionnum":"02","_creatorName":null,"descript":"删除权限","_sid":null,"name":"删除","_explicitType":"mvc.model.permission.vo.ActionTb","createDate":null},{"creator":"","id":"1307534980b37245bd5e22836dea166c9bf1ce3715","_color":null,"actionnum":"03","_creatorName":null,"descript":"修改权限","_sid":null,"name":"修改","_explicitType":"mvc.model.permission.vo.ActionTb","createDate":null},{"creator":"","id":"1307534996285eb263edf5cb049f3f4cc7fa0d2182","_color":null,"actionnum":"04","_creatorName":null,"descript":"查找功能","_sid":null,"name":"查询","_explicitType":"mvc.model.permission.vo.ActionTb","createDate":null},{"creator":"","id":"13075350119453e74d5030bb8351cdb998b5ac2a65","_color":null,"actionnum":"05","_creatorName":null,"descript":"文件上传功能","_sid":null,"name":"上传","_explicitType":"mvc.model.permission.vo.ActionTb","createDate":null}],"createDate":"2011-06-08 20:10:54"}],"_actions":null,"_creatorName":"minn","address":"35","_color":"2","person_phone":"3535","creator":"12958839739d64e8c6bacfd2a98415686a6af75cfd","opr_name_ch":"345","_sid":"c9b03be66eee564123ecf4b66c25986a","password":"345","opr_name_en":"345","createDate":"2011-06-11 16:10:52","office_phone":"3535","email":"345@qq.com","qq_msn":"354"},"_sid":"c9b03be66eee564123ecf4b66c25986a"}';
	   // $obj->update($updateStr);
        //测试打印基类函数
//        echo "<br>测试打印基类函数：getSRM（）<br>\n";
//        $r=$obj->getSRM();
//        echo $r."<br>\n";
      echo '<br>*********************Test OperatorService.php end **************************';
?>