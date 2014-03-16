<?
//start a session
session_start();
//check for posted values and register defaults
if (($_POST[sel_font_family]) && ($_POST[sel_font_size])) {
     $font_family = $_POST[sel_font_family];
     $font_size = $_POST[sel_font_size];
     $_SESSION[font_family] = $font_family;
     $_SESSION[font_size] = $font_size;
//check for stored values, extract from $_SESSION superglobal and register
} else if (((!$_POST[sel_font_family]) && (!$_POST[sel_font_size]))
      &&  ($_SESSION[font_family]) && ($_SESSION[font_size])) {
     $font_family = $_SESSION[font_family];
     $font_size = $_SESSION[font_size];
     $_SESSION[font_family] = $font_family;
     $_SES0SION[font_size] = $font_size;

//register defaults
} else {
     $font_family = "sans-serif";
     $font_size = "10";
     $_SESSION[font_family] = $font_family;
     $_SESSION[font_size] = $font_size;
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
<H1>Your Preferences Have Been Set</H1>
<P>As you can see, your selected font family is now <? echo "$font_family";
     ?>, with a base size of <? echo "$font_size" ?> pt.</p>
<P>Please feel free to <a href="session01.php">change your preferences</a>
       again.</p>
</BODY>
</HTML>

