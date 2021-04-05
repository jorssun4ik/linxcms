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

if ($prava >= 6)
{
$set['title'] = 'Модер панель';
head();
echo '<div class="all"><div class="menu"><div class="text">
<div class="title">
Модер Панель
</div>';
# Тут выводим кого забанили за что, тип бана и до какого числа!
$all = mysqli_num_rows(quer("SELECT * FROM `ban`"));
if ($all > 0)
{
	if (empty($_GET['p']))
{
	$start = 0;
} else
{
	$p = (int)$_GET['p']-1;
	//if ($num == 0) { $num = 10; }
    $start = $p*$num;
}
//   echo $all;
	$b = quer("SELECT * FROM `ban` ORDER BY `id` DESC LIMIT ".$start.",".$num."");
	while ($ba = mysqli_fetch_assoc($b))
	{
	    $us_id = strip_tags($ba['u_id']);
		$who_id = strip_tags($ba['who_id']);
		$cause = $ba['cause'];
		$stime = strip_tags($ba['stime']);
		$dtime = strip_tags($ba['dtime']);
		//Поиск юзера по id
		$Query1 = quer("SELECT `login` FROM `users` WHERE `id` = '".$us_id."'");		
        $result1 = mysqli_fetch_assoc($Query1);
        $us_login = $result1['login'];
		
		$Query2 = quer("SELECT `login` FROM `users` WHERE `id` = '". $who_id ."'");
		$result2 = mysqli_fetch_assoc($Query2);
		$who_login = $result2['login'];		
		
				
		echo '<div class="c">Кого:'.$us_login.' [<a href="del_ban.php?id='. $ba['id'] .'">убрать</a>]<br />
		Причина: '. $cause .'<br />
		Кто заблокировал: '. $who_login .'<br />
		Время окончания: '. get_time($dtime,4) .'<br /><br />		
		</div>';
    }
	nav($all,$num);
}
else
{
echo '<div class="c">Все спокойно общаются =)</div>';	
}
echo '<br /><< <a href="http://'.$_SERVER['HTTP_HOST'].'/mpan/?">Модер Панель</a>
</div></div>';
}
else
{
Header("Location: ../index.php");
}
}
else
{
    Header("Location: ../index.php");
}
foot();
?>