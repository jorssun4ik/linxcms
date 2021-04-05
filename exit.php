<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'выходит с сайта';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Выход';

if ($aut > 0)
{

setcookie('login','',time()-3600);
setcookie('pass','',time()-3600);
session_destroy();
head(false);

echo '<div class="all"><div class="menu"><div class="text">
<div class="title">Выход</div>
';
echo '<div class="c">Вы успешно вышли из аккаунта!</div>';
}
else
{
 Header("Location: index.php");	
}
quer("DELETE FROM `online` WHERE `u_id` = '". $u_id ."'");
echo '</div></div>';
foot();
?>