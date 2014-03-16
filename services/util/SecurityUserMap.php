<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class SecurityUserMap{
	
	public static function getSecurityRandom(){
		$r=md5(mt_rand(33,65535));
		return $r;
	}
	
}
SecurityUserMap::getSecurityRandom();
?>