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
echo '<div class="c">
<a href="http://'.$_SERVER['HTTP_HOST'].'/mpan/ban.php">Бан панель</a><br />
</div>
<div class="c">
<a href="http://'.$_SERVER['HTTP_HOST'].'/mpan/library_mod.php">Библиотека</a><br />
</div></div>
</div>';
foot();
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
?>