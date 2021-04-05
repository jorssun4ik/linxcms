<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в запретной зоне';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{
$pr = mysqli_fetch_assoc(quer("SELECT `prava` FROM `users` WHERE `login` = '".$login."'"));
$prava = strip_tags($pr['prava']);

if ($prava >= 8)
{
$set['title'] = 'Редактор Новостей';
head();
if ($aut == 0)
{
	echo '<div class="top_title">';
}
else
{
echo '<div class="title">';
}
echo 'Добавление новости
</div>
<div class="menu">';
if (!empty($_POST['title']) && !empty($_POST['msg']))
{
	$title = mysqli_escape_string($db,htmlspecialchars($_POST['title']));
	$msg = mysqli_escape_string($db,htmlspecialchars($_POST['msg']));
    if (strlen($title) > 255)
    {
    	echo 'Слишком большое название новости!<br />';
    }
    else
    {
    
	//$title = iconv("utf-8","windows-1251",$title);
	//$msg = iconv("utf-8","windows-1251",$msg);
	$time = time();
	//$login = $_SESSION['login'];
	quer("INSERT INTO `news` SET `title` = '".$title."', `msg` = '".$msg."', `login` = '".$login."', `time` = '".$time."'");
	echo 'Новость успешно добавлена под id:'.mysqli_insert_id($db);
	echo '<META HTTP-EQUIV=refresh CONTENT="1;url=http://'.$_SERVER['HTTP_HOST'].'/apan/index.php">';			
    }
}
 else
 {
echo '<form action="http://'.$_SERVER['HTTP_HOST'].'/apan/add_news.php" method="POST">';
echo 'Название Новости:<br />';
echo '<input name="title"/><br />';
echo 'Текст Новости:<br />';
echo '<textarea name="msg"></textarea><br />';
echo '<input type="submit" value="[add]"/></form>';	
}
}
else
{
	echo '<div class="all">
<div class="title">
Ошибка
</div>
<div class="menu">';
	echo 'Ощибка<br />';
	echo '<META HTTP-EQUIV=refresh CONTENT="0;url=http://'.$_SERVER['HTTP_HOST'].'/index.php">';
}
}
else
{
	echo '<div class="all">
<div class="title">
Ошибка
</div>
<div class="menu">';
	echo 'Ощибка<br />';
	echo '<META HTTP-EQUIV=refresh CONTENT="0;url=http://'.$_SERVER['HTTP_HOST'].'/index.php">';
}
foot();

?>