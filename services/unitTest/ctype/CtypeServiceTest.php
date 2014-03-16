<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'ctype/CtypeService.php';
require_once 'base/JSON.php';


echo '*********************Test CtypeService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$ctype='{"descript":"嘎登","pid":"","createDate":"2010-11-27 22:48:09","creator":"","name":"阿发","color":1,"id":"","category":"0","_sid":"","_creatorName":""}';
		$obj=new CtypeService();
//	    $obj->add($ctype);
//        $obj->query('{"pageIndex":0,"pageSize":3,"type_name":"","recordCount":-1}');
//        $obj->update('{"descript":"嘎登","pid":null,"createDate":"2010-12-25 07:52:14","creator":null,"name":"类别3","color":2,"id":"1293201506c8c3924a3385b6c14b4420f557b60608","category":"0","_sid":null,"_creatorName":null}');
//        $obj->delete('{"id":"123123"}');
        $delstr='{"_sid":"","_color":"","createDate":"","name":"","creator":"","descript":"","pid":"","id":"1295967621dcb11c8709d0fa789e651fdb3a4cf26a","category":"0","_creatorName":"","_categoryName":"","_parentName":""}';
        $obj->delete($delstr);
 echo '<br>*********************Test CtypeService.php end **************************';
?>