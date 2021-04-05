<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит новости';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Новости';

head();

if ($aut > 0)
{
mc();
}
echo '<div class="title">';
echo 'Новости
</div>';
echo '<div class="all"><div class="menu"><div class="text">';
$all = mysqli_num_rows(quer("SELECT `id` FROM `news`"));

$pag = abs($_GET['p']);
if (empty($pag) && $pag == 0)
{
	$start = 0;
} else
{
	$p = $pag - 1;
	$start = $p*$num;
}
if ($all > 0)
{
$quer = quer("SELECT * FROM `news` ORDER BY `id` DESC LIMIT ".$start.",".$num."");
while($n = mysqli_fetch_assoc($quer))
{

$title = $n['title'];
$msg = $n['msg'];
$msg = nl2br($msg);
$login = $n['login'];
$time = $n['time'];	
echo '<div class="zag"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/rss.png" alt = "rss"/>';
echo $title. ftime($time);
echo '</div>';
echo '<div class="c">
'.bb_code($msg).'</div>';
echo '<small>Добавил:<b>'.$login.'</b></small><br />';	
}
nav($all,$num);
}
else
{
	echo 'Новостей нет!<br />';
}
echo '<br /><div class="c">Новости просматривают: '. onlc("news") .'</div>';
if ($prava >= 8){
    echo '<div class="c"><img src="'. URL .'images/icons/add.png" alt="add"/><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/news.php?act=add">Добавить новость</a></div>';
}
echo '</div></div>';
foot();
?>