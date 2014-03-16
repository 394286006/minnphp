<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'front/product/ProductService.php';
require_once 'base/JSON.php';


echo '*********************Test CtypeService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$searchstr='{"pageIndex":0,"recordCount":-1,"pageSize":20,"_sid":"2cd019e887a1ef10c8c8b3ccd92f2f9b"}';
		$obj=new ProductService();
		$obj->queryProduct($searchstr);
   echo '<br>*********************Test CtypeService.php end **************************';
?>