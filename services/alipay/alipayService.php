<?php
include '../setting.php';
require_once APPROOT."alipay/class/alipay_service.php";
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
class alipayService{
			/*以下参数是需要通过下单时的订单数据传入进来获得*/
		//必填参数
		var $out_trade_no	='20110108';// date(Ymdhms);			//请与贵网站订单系统中的唯一订单号匹配
		var $subject		='test';// $_POST['aliorder'];		//订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
		var $body			= 'test1';//$_POST['alibody'];		//订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
		var $price			='0.01' ;//$_POST['alimoney'];		//订单总金额，显示在支付宝收银台里的“应付总额”里
		
		var $logistics_fee		= "0.00";				//物流费用，即运费。
		var $logistics_type		= "EXPRESS";			//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
		var $logistics_payment	= "BUYER_PAY";			//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		
		var $quantity		= "1";						//商品数量，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品。
		
		//扩展参数——买家收货信息（推荐作为必填）
		//该功能作用在于买家已经在商户网站的下单流程中填过一次收货信息，而不需要买家在支付宝的付款流程中再次填写收货信息。
		//若要使用该功能，请至少保证receive_name、receive_address有值
		//收货信息格式请严格按照姓名、地址、邮编、电话、手机的格式填写
		var $receive_name		= "收货人姓名";			//收货人姓名，如：张三
		var $receive_address	= "收货人地址";			//收货人地址，如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
		var $receive_zip		= "123456";				//收货人邮编，如：123456
		var $receive_phone		= "0571-81234567";		//收货人电话号码，如：0571-81234567
		var $receive_mobile		= "13312341234";		//收货人手机号码，如：13312341234
		
		//扩展参数——第二组物流方式
		//物流方式是三个为一组成组出现。若要使用，三个参数都需要填上数据；若不使用，三个参数都需要为空
		//有了第一组物流方式，才能有第二组物流方式，且不能与第一个物流方式中的物流类型相同，
		//即logistics_type="EXPRESS"，那么logistics_type_1就必须在剩下的两个值（POST、EMS）中选择
		var $logistics_fee_1	= "";					//物流费用，即运费。
		var $logistics_type_1	= "";					//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
		var $logistics_payment_1= "";					//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		
		//扩展参数——第三组物流方式
		//物流方式是三个为一组成组出现。若要使用，三个参数都需要填上数据；若不使用，三个参数都需要为空
		//有了第一组物流方式和第二组物流方式，才能有第三组物流方式，且不能与第一组物流方式和第二组物流方式中的物流类型相同，
		//即logistics_type="EXPRESS"、logistics_type_1="EMS"，那么logistics_type_2就只能选择"POST"
		var $logistics_fee_2	= "";					//物流费用，即运费。
		var $logistics_type_2	= "";					//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
		var $logistics_payment_2= "";					//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
		
		//扩展功能参数——其他
		var $buyer_email		= '';					//默认买家支付宝账号
		var $discount	 		= '';					//折扣，是具体的金额，而不是百分比。若要使用打折，请使用负数，并保证小数点最多两位数
		
		
		/////////////////////////////////////////////////
	public function getAlipayParamter($info){
		//构造要请求的参数数组
		 $obj=json_decode($info);
		 $out_trade_no=$obj->out_trade_no;//$obj->out_trade_no;
		 $subject=$obj->subject;
		 $body=$obj->body;
		 $price=$obj->price;
		 $receive_name=$obj->receive_name;
		 $receive_address=$obj->receive_address;
		 $receive_zip=$obj->receive_zip;
		 $receive_phone=$obj->receive_phone;
		 $receive_mobile=$obj->receive_mobile;
		 
		 $ac=new alipay_config();
		 $parameter = array(
		        "service"			=> "create_partner_trade_by_buyer",	//接口名称，不需要修改
		        "payment_type"		=> "1",               				//交易类型，不需要修改
		
		        //获取配置文件(alipay_config.php)中的值
		        "partner"			=> $ac->partner,
		        "seller_email"		=> $ac->seller_email,
		        "return_url"		=> $ac->return_url,
		        "notify_url"		=> $ac->notify_url,
		        "_input_charset"	=> $ac->_input_charset,
//		        "show_url"			=> $ac->show_url,
		
		        //从订单数据中动态获取到的必填参数
		        "out_trade_no"		=> $out_trade_no,
		        "subject"			=> $subject,
		        "body"				=> $body,
		        "price"				=> $price,
				"quantity"			=> $this->quantity,
				
				"logistics_fee"		=> $this->logistics_fee,
				"logistics_type"	=> $this->logistics_type,
				"logistics_payment"	=> $this->logistics_payment,
				
				//扩展功能参数——买家收货信息
				"receive_name"		=> $receive_name,
				"receive_address"	=> $receive_address,
				"receive_zip"		=> $receive_zip,
				"receive_phone"		=> $receive_phone,
				"receive_mobile"	=> $receive_mobile,
				
				//扩展功能参数——第二组物流方式
		//		"logistics_fee_1"	=> $logistics_fee_1,
		//		"logistics_type_1"	=> $logistics_type_1,
		//		"logistics_payment_1"=> $logistics_payment_1,
		//		
				//扩展功能参数——第三组物流方式
		//		"logistics_fee_2"	=> $logistics_fee_2,
		//		"logistics_type_2"	=> $logistics_type_2,
		//		"logistics_payment_2"=> $logistics_payment_2,
		
				//扩展功能参数——其他
		//		"discount"			=> $discount,
		//		"buyer_email"		=> $buyer_email
		);
		$alipay = new alipay_service($parameter,$ac->key,$ac->sign_type);
		$message=$alipay->build_action();
		return MessageUtil::getMessage(1,'url',$message);
	}
}

class alipay_config{
var  $partner		= "2088102928539379";

//安全检验码，以数字和字母组成的32位字符
var $key   			= "l6mczym8h9118d5ljgcvzs0dpqg8ao7b";

//签约支付宝账号或卖家支付宝帐户
var $seller_email	= "freemanfreelift@gmail.com";

//交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
//var $notify_url		= "http://www.minnblog.com/index.html";
var $notify_url		= "http://www.minwwls.com/services/alipay/notify_url.php";
//付完款后跳转的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
var $return_url		= "http://www.minwwls.com/services/alipay/return_url.php";

//网站商品的展示地址，不允许加?id=123这类自定义参数
var $show_url		= "http://www.alipay.com";

//收款方名称，如：公司名称、网站名称、收款人姓名等
var $mainname		= "minn";

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑



//签名方式 不需修改
var $sign_type		= "MD5";

//字符编码格式 目前支持 GBK 或 utf-8
var $_input_charset	= "utf-8";

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
var $transport		= "http";


}
?>