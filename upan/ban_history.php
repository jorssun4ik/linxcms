<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит историю бана';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0) //Только для зарегистрированных
{
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) //Проверка или нормальное id
{
    $set['title'] = 'История блокировок';
    head();
    $id = abs(intval($_GET['id']));

$res = mysqli_num_rows(quer("SELECT `id` FROM `ban_log` WHERE `u_id` = '". $id ."'"));

if ($res > 0)
{
    $pag = (int)$_GET['p'];
    if (empty($pag) OR $pag == 0)
{
	$start = 0;
	$ii = 1;
} else
{
	$p = $pag - 1;
	$start = $p*$num;
	if ($start > 0)
	{
	$ii = $start;
	$ii+=1;
	}
	else
	{
		$ii = 1;
	}
}

$query = quer("SELECT * FROM `ban_log` WHERE `u_id` = '". $id ."' ORDER BY `id` DESC LIMIT ". $start .", ". $num ."");

$ll = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $id ."'"));
$llogin = $ll['login'];

echo '<div class="all"><div class="main"><div class="text"><div class="title">История блокирований '. $llogin .'</div>';

while ($q = mysqli_fetch_assoc($query))
{
    $whoid = $q['who_id'];
    $cause = $q['cause'];
    $stime = $q['stime'];
    $dtime = $q['dtime'];
    
    $wl = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $whoid ."'"));
    $wlogin = $wl['login'];
    echo '
	<div class="c">
    
	Причина: <b>'. $cause .'</b><br />
	Кто: <b>'. $wlogin .'</b><br />
	Время начала: <b>'. get_time($stime,5) .'</b><br />
    Время окончания: <b>'. get_time($dtime,5) .'</b><br />
    </div><br />';
    
}
nav($res,$num,"id=".$id);
echo '<div class="c">Всего блокирований:'.$res.'</div>';
}
else
{
    echo '<div class="c">Данный человек тихий как мышка, поэтому никаких блокировок не получал</div>
    <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/">На главную</a></div>
    ';
}
echo '</div></div>';
foot();
exit();
}   
else //Не существует либо не цифра
{
    Header("Location: ../index.php");
} 
}
else
{
	Header("Location: ../index.php");
}
?>