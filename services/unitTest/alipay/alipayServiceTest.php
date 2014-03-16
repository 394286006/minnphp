<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'alipay/alipayService.php';
require_once 'base/JSON.php';

  $obj=new alipayService();
  echo $obj->getAlipayParamter("");
?>