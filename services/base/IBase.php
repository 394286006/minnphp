<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
//include_once  '../setting.php';
interface IBase {
	
	/**
	 * 添加信息
	 * @param  $info
	 */
	public function add($info);
	
	/**
	 * 修改信息
	 * @param $info
	 */
	public function update($info);
	
	/**
	 * 查询条件
	 * @param $condition
	 */
	//public function query($condition);
	
	/**
	 * 删除操作
	 * @param  $info
	 */
	public function delete($info);
}

?>