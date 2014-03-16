<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include 'setting.php';
$dom = new DOMDocument("1.0"); 

header("Content-Type: text/plain"); 
// create root element 
$root = $dom->createElement("node"); 
$dom->appendChild($root); 

$item = $dom->createElement("node"); 
$root->appendChild($item); 

 $dom->save(APPROOT.CTYPEMENU); 

?>