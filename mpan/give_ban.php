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
if (isset($_GET['id']) && !empty($_GET['id']))
{
	$id = (int)$_GET['id'];
	$Query = quer("SELECT `login` FROM `users` WHERE `id` = '". $id ."'");
	if (mysqli_num_rows($Query) > 0)
	{
		$log = mysqli_fetch_assoc($Query);
		$ulogin = $log['login'];
	
		echo '<div class="c">
		<form action="http://'.$_SERVER['HTTP_HOST'].'/mpan/give_ban.php?ok" method="POST">
		Логин: <b>'.$ulogin.'</b><br />
		<input type="hidden" name="id" value="'.$id.'">
		Время(Скока):<br /> <input name="numm"/> <select name="dd"/>
		<option value="min">минуты</option>
		<option value="c">часы</option>
		<option value="d">дни</option>
		<option value="m">месяцы</option>
		</select><br />		
		Причина:<br />	
		<textarea name="cause" cols="15" rows="5"></textarea><br />
		<input type="submit" value="Установить бан"/></form></div>';
		} 
}		
	elseif(!empty($_POST['cause']))
{
	/**
 * Параметры времени
 * time()+60 - 1 минута
 * time()+(60*60) - 1 час
 * time()+(60*60*24) - 1 день
 * time()+(60*60*24*30) - 1 месяц
 */
 
 $id = (int)$_POST['id'];
 $numm = (int)$_POST['numm'];
 $dd = htmlspecialchars($_POST['dd']);
 $cause = htmlspecialchars(mysqli_escape_string($db,$_POST['cause']));
 
 $bresult = mysqli_num_rows(quer("SELECT `id` FROM `ban` WHERE `u_id` = '". $id ."'"));
if ($bresult == 0)
{
 if ($dd == 'min')
 {
 	$numm = 60*$numm;
 }
 elseif ($dd == 'c')
 {
 	$numm = 60*60*$numm;
 }
 elseif ($dd == 'd')
 {
 	$numm = 60*60*24*$numm;
 } 
 elseif ($dd == 'm')
 {
 	$numm = 60*60*24*$numm;
 }
$dtime = time()+$numm;

quer("INSERT INTO `ban` SET `u_id` = '". $id ."', `who_id` = '". $u_id ."', `cause` = '". $cause ."', `stime` = '". time() ."', `dtime` = '". $dtime ."'");	echo 'Вы успешно поставили бан данному человеку<br />';
}
else
{
	echo 'Такой пользователь итак заблокирован!<br />';
}		
}	
	else
	{
		echo 'Ощибка<br />';
	    echo '<META HTTP-EQUIV=refresh CONTENT="0;url=http://'.$_SERVER['HTTP_HOST'].'/index.php">';
	}
		
} 
else
{
	echo 'Ощибка<br />';
	echo '<META HTTP-EQUIV=refresh CONTENT="0;url=http://'.$_SERVER['HTTP_HOST'].'/index.php">';
}

echo '<< <a href="http://'.$_SERVER['HTTP_HOST'].'/mpan/?">Модер Панель</a>';
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
echo '</div></div>';
foot();
?>