<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
 function setSession($ss)
	{
		$_SESSION['user']=$ss;
		session_name("user");
	}
?>