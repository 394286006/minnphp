<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'payway/PaywayService.php';
require_once 'base/JSON.php';


echo '*********************Test CtypeService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$addstr='{"_sid":"","account":"12312","_color":1,"name":"123","creator":"","createDate":"","_creatorName":"","id":"","descript":"1231231","paynum":0}';
		$obj=new PaywayService();
//	    $obj->add($addstr);
	    $upstr='{"_sid":null,"_color":"2","account":"12312","name":"3333","creator":null,"createDate":"2011-01-03 10:29:29","_creatorName":null,"descript":"1231231","id":"12940217697206ef1be0a3d44c57fa8214fc74421e","paynum":0}';
	    $obj->update($upstr);
//        $obj->query('{"pageIndex":0,"pageSize":3,"type_name":"","recordCount":-1}');
//        $obj->update('{"descript":"嘎登","pid":null,"createDate":"2010-12-25 07:52:14","creator":null,"name":"类别3","color":2,"id":"1293201506c8c3924a3385b6c14b4420f557b60608","category":"0","_sid":null,"_creatorName":null}');
//        $obj->delete('{"id":"123123"}');
//       DateTime::setTimezone('Asia/Shanghai');
 echo '<br>*********************Test CtypeService.php end **************************';
?>