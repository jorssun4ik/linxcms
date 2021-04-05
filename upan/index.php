<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в личном меню';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{
$set['title'] = 'Личное меню';
head();
echo '<div class="all"><div class="menu"><div class="text">
<div class="title">
Личное меню '. $login .'
</div>
';
echo '<div class="c"><img src="'. URL .'images/icons/info.png" alt="info"/>
<a href="http://'.$_SERVER['HTTP_HOST'].'/upan/anketa.php">Анкета</a><br />
</div>';
echo '<div class="c"><img src="'. URL .'images/icons/mess.png" alt="mess"/>
<a href="http://'.$_SERVER['HTTP_HOST'].'/upan/msg.php">Личные сообщения</a><br />
</div>';
echo '<div class="c">
<img src="'. URL .'images/icons/options.gif" alt="options"/><a href="http://'.$_SERVER['HTTP_HOST'].'/settings.php">Настройки</a><br />
</div>';
if ($prava >= 6)
{
	echo '<div class="c">
<img src="'. URL .'images/icons/mod.png" alt="mod"/><a href="http://'.$_SERVER['HTTP_HOST'].'/mpan/?">Модер-Панель</a><br />
</div>';
}
if ($prava >= 8)
{
	echo '<div class="c">
<img src="'. URL .'images/icons/adm.png" alt="adm"/><a href="http://'.$_SERVER['HTTP_HOST'].'/apan/">Админ-Панель</a><br />
</div>';
}
echo '</div></div>';

foot();
} 
else
{
	Header("Location: ../index.php");
}
?>