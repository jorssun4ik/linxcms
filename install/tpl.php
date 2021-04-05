<?php

/**
 * @author LinXs
 * @copyright 2010
 */

function split_db($q){
    $q = str_ireplace("-","",$q);
    
    $arr = explode(";",$q);    
    return $arr;
}

function head($title = NULL)
{
	global $set, $_SERVER,$device,$db;
	if ($title == NULL) $title = $set['title'] . '::' . strtoupper($_SERVER['HTTP_HOST']);
	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
Header("Pragma: no-cache"); // HTTP/1.1 
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';

echo '<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="LinX" />';
echo '<title>'.$title.'</title>
<link rel="stylesheet" href="http://'. $_SERVER['HTTP_HOST'] .'/styles/wap/default/style.css" type="text/css" />
</head>
<body>
<div class="page">
<div class="content">
<div class="logo">
<img src="http://'. $_SERVER['HTTP_HOST'] .'/styles/wap/default/logo.gif" alt="" />
</div>';

echo '<!-- LinX CMS 1.0.0 -->';
}

function foot()
{
echo '</div></div></div><div class="foot">';
if ($_SERVER['SCRIPT_NAME'] != "/index.php"){
    echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>&nbsp;|&nbsp;';
}
echo '&copy; <a href="http://linxcms.com">LinX-CMS</a> 2009-2011 Design By <a href="http://design.ohoster.ru">Sergio</a></div>
</div><div id="end"><div style="padding:3px;">';
echo '</div></div></div>
</body></html>';
}

function perm($file) {
            return @ decoct(@ fileperms("$file")) % 1000;
        }

function quer($query){
    global $db;
    $q = mysql_query($query,$db);
    return $q;
}

function rus_to_lat($var){
    $var = strtolower($var);
    $rus_big = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я');
    $rus_small = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я');
    $en = array('a','b','v','g','d','e','jo','zh','z','i','q','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','qw','yi','q','ye','yu','ya');
    
    $var = str_ireplace($rus_big,$en,$var);
    $var = str_ireplace($rus_small,$en,$var);    
    
    return $var;    
}

?>