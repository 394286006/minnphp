<?
//start a session
session_start();
//check for stored values and register defaults
if ((!$_SESSION[font_family]) || (!$_SESSION[font_size])) {
     $font_family = "sans-serif";
     $font_size = "10";
     $_SESSION[font_family] = $font_family;
     $_SESSION[font_size] = $font_size;
} else {
     //extract from $_SESSION superglobal if exist
     $font_family = $_SESSION[font_family];
     $font_size = $_SESSION[font_size];
}
?>
<HTML>

<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<TITLE>My Display Preferences</TITLE>

<STYLE type="text/css">
BODY, P, A {font-family:<? echo "$font_family"; ?>;
font-size:<? echo "$font_size"; ?>pt;font-weight:normal;}
H1 {font-family:<? echo "$font_family"; ?>;
font-size:<? echo $font_size + 4; ?>pt;font-weight:bold;}
</STYLE>
</HEAD>
<BODY>
<H1>Set Your Display Preferences</H1>
<FORM METHOD="POST" ACTION="session02.php">
<P>Pick a Font Family:<br>
<input type="radio" name="sel_font_family" value="serif"> serif
<input type="radio" name="sel_font_family" value="sans-serif"
checked> sans-serif
<input type="radio" name="sel_font_family" value="Courier"> Courier
<input type="radio" name="sel_font_family" value="Wingdings"> Wingdings
</p>
<P>Pick a Base Font Size:<br>
<input type="radio" name="sel_font_size" value="8"> 8pt
<input type="radio" name="sel_font_size" value="10" checked> 10pt
<input type="radio" name="sel_font_size" value="12"> 12pt
<input type="radio" name="sel_font_size" value="14"> 14pt
</p>
<P><input type="submit" name="submit" value="Set Display Preferences"></p>
</FORM>
</BODY>
</HTML>