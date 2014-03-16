<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class MessageUtil{
	
	/**
	 * 返回客户端的封装信息
	 *
	 * @param 成功与否0=失败，1=增删改成功 $messageSucess 3=查询数据,登录成功
	 * @param 说明$message的类型 $messageType
	 * @param 信息内容主体 $message
	 * @param 可附带的额外信息 $otherInfo
	 * @return $m数组对象
	 */
	public final static function getMessage($messageSucess,$messageType,$message,$otherInfo=''){
		$m=array();
		array_push($m,$messageSucess);
		array_push($m,$messageType);
		array_push($m,$message);
		array_push($m,$otherInfo);
		return $m;
	}
}
?>