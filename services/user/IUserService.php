<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
interface IUserService {
	
	/**
	 * 
	 * @param 用户类型参数 $login
	 */
	public function login($login);
	
	/**
	 * 用户退出
	 */
	public function logout();
	
	/**
	 * 修改密码
	 * @param  $login
	 */
	public function updatePwd($login);
	
	
	
}

?>