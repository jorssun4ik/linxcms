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
$pr = $sql->query("SELECT `prava` FROM `users` WHERE `login` = '".$login."'")->fetch_assoc();
$prava = strip_tags($pr['prava']);

if ($prava == 9)
{
$set['title'] = 'Админ панель';
head();
echo '<div class="title">
Админка::Управление баннерами
</div>
<div class="menu">


';
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']);
}
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']);
}
?>