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

echo '<div class="top_title">';
echo 'Последняя новость
</div>';
echo '<div class="menu">';

$result = mysqli_num_rows(quer("SELECT `id` FROM `news`"));

if ($result > 0)
{
$n = mysqli_fetch_assoc(quer("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 1"));
//$title = iconv('windows-1251','utf-8',$n['title']);
$title = $n['title'];
//$msg = iconv('windows-1251','utf-8',$n['msg']);
$msg = $n['msg'];
$msg = nl2br($msg);
$login = $n['login'];
$time = $n['time'];	
echo '<div class="zag">';
echo $title.'['.get_time($time,5).']';
echo '</div>';
echo '<div class="c">
'.bb_code($msg).'</div>';
echo '<small>Добавил:<b>'.$login.'</b></small><br />';	
echo '<br /><br /><a href="http://'. $_SERVER['HTTP_HOST'] .'/news/">Все новости</a><br />';
}
else
{
	echo 'Новостей еще не было создано!<br />';
}
foot();
?>