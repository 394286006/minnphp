<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'entrypt/EncryptService.php';
require_once 'base/JSON.php';


echo '*********************Test CtypeService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$info='{"privatekey":"adfafsdfafa中国人"}';
		$obj=new EncryptService();
	    $obj->add($info);

 echo '<br>*********************Test CtypeService.php end **************************';
?>