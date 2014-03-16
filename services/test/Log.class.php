<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
 require_once("IpLocation.class.php");
 date_default_timezone_set('Asia/Shanghai'); 
class Log
{
     
	public static function doLog()
	{
		//获取地址
	 
		$il=new Iplocation();
		$arr=$il->getlocation();
		$address = $arr['country'] . $arr['area'];
		//$address = mb_convert_encoding($address, 'UTF-8', 'GBK');
		$address = iconv('GBK', 'UTF-8', $address);

		$filename = date('Y-m-d') . '.txt';
		$currentTime = date('H:i:s');
		$ip = "";//empty($arr['ip']) ? '127.0.0.1' : $arr['ip'];
		if(empty($arr['ip'])){
			$ip='127.0.0.1';
		}else{
			$ip=$arr['ip'];
		}
		
		$content = "$ip , $address , $currentTime "."\r\n";

	    if (!$handle = fopen('log/' . $filename, 'a'))
	    {
	         exit('Cannot open file' . $filename);
	    }
	    if (!fwrite($handle, $content))
	    {
	        exit('Cannot write to file' . $filename);
	    }
	    fclose($handle);

		return TRUE;
	}
}
?>