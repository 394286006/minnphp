<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once(APPROOT."test/IpLocation.class.php");
$ip=new Iplocation();//
$arr=$ip->getlocation($ip->get_client_ip());
echo $arr['ip'].",".$arr['country'].$arr['area'];
?>