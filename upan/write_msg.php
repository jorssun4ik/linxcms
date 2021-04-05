<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит лс';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{
$set['title'] = 'Написать сообщение';
head();


echo '<div class="all">
<div class="top_title">
Написание сообщение
</div>
<div class="menu">';
if (isset($_GET['id']) && !empty($_GET['id']))
{
	$id = (int)$_GET['id'];
}

if (!empty($_POST['user']) && !empty($_POST['msg']) && !empty($_POST['tema']))
{
	$user = htmlspecialchars(mysqli_escape_string($db,$_POST['user']));
	$msg = htmlspecialchars(mysqli_escape_string($db,$_POST['msg']));
	$tema = htmlspecialchars(mysqli_escape_string($db,$_POST['tema']));	

$userpr = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `login` = '". $user ."'"));
if ($userpr > 0)
{
	$uu = mysqli_fetch_assoc(quer("SELECT `id` FROM `users` WHERE `login` = '". $user ."'"));
	$uu_id = $uu['id'];
	if ($u_id == $uu_id)
	{
		echo '<div class="c"><font color="red">Самому себе писать нельзя!</font></div>';
	}
	else
	{
	quer("INSERT INTO `msg` SET `u_id` = '". $u_id ."', `to` = '". $uu_id ."', `tema` = '". $tema ."', `msg` = '". $msg ."', `time` = '". time() ."', `read` = '1'");
		
	echo '<div class="c"><font color="green">Сообщение успешно отправлено!</font></div>';	
	echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/msg.php">Сообщения</a></div>';
    foot();
    exit();
    }
}
else
{
	echo '<div class="c"><font color="red">Такого логина нет!</font></div>';
}

	
}
$ms = mysqli_fetch_assoc(quer("SELECT * FROM `msg` WHERE `id` = '". $id ."'"));
$iid = $ms['u_id'];
$tema = $ms['tema'];

$l = mysqli_fetch_assoc(quer("SELECT * FROM `users` WHERE `id` = '". $iid ."'"));
$llogin = $l['login'];

echo '<div class="c"><form action="http://'. $_SERVER['HTTP_HOST'] .'/upan/write_msg.php" method="POST">
Логин:<br />';
if (isset($_GET['id']))
{
echo '<input name="user" value="'. $llogin .'"/><br />';
}
elseif (isset($_GET['usi']))
{
  $usi = (int)$_GET['usi'];
  $sl = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $usi ."'"));
  $slog = strip_tags($sl['login']);
  echo '<input name="user" value="'. $slog .'"/><br />';  

}
else
{
echo '<input name="user"><br />';	
}
echo 'Тема:<br />';
if (isset($_GET['id']))
{
    $tema = str_ireplace("Re:","",$tema);
	echo '<input name="tema" value="Re:'. $tema .'"/><br />';
}
else
{
echo '<input name="tema" value="Привет"/><br />';
}
echo 'Сообщение:<br />
<textarea name="msg" cols="15" rows="5"></textarea><br /><br />
<input type="submit" value="Написать"/>
</form></div>
';

echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/msg.php">Сообщения</a></div>';

foot();
} 
else
{
	Header("Location: ../index.php");
}
?>