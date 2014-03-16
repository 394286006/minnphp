<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
 require_once 'util/SecurityUserMap.php';
 
 	 echo '*********************Test SecurityUserMap.php start **************************<br>\n';

 	 //测试生成的随机数
 	 $r=SecurityUserMap::getSecurityRandom();
 	 echo "测试生成的随机数<br>\n";
 	 echo $r."<br>\n";
 	 
     echo '<br>*********************Test SecurityUserMap.php end **************************';
?>