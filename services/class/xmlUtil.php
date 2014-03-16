<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
//header("Content-type: text/html;charset=utf-8");
function xml2KV($data,$rootTagName)
{
	$parser = xml_parser_create_ns("utf-8");
    //xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);
 
    // ���� XML �ṹ
   //echo json_encode($tags);
   // echo "\n";
   echo json_encode($values);
    foreach ($tags as $key=>$val) {
    	//echo json_encode($tags);
        if ($key == $rootTagName) {
        	//echo $key;
            $molranges = $val;
           //echo $molranges;
            // each contiguous pair of array entries are the
            // lower and upper range for each molecule definition
            for ($i=0; $i < count($molranges); $i+=2) {
                $offset = $molranges[$i] + 1;
                $len = $molranges[$i + 1] - $offset;
                $tdb[] = parseMol(array_slice($values, $offset, $len));
            }
        } else {
            continue;
        }
    }
    return $tdb;
	
}
function parseMol($mvalues)
{
    for ($i=0; $i < count($mvalues); $i++) {
        $mol[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
    }
    return new proxyObj($mol);
}
class proxyObj{
	
	function proxyObj($data)
	{
       foreach($data as $k=>$v)
		{
			$this->$k=$data[$k];
		}
	}
}
?>