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

if ($prava >= 8)
{
$set['title'] = 'Админ панель';
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Админ Панель
</div>
<div class="c">
<a href="'. URL .'apan/sys.php">Системные настройки</a><br />
</div>
<div class="c">
<a href="'. URL .'apan/reclame.php">Управление Рекламой</a><br />
</div>
<div class="c">
<a href="'. URL .'apan/news.php">Управление Новостями</a><br />
</div>
<div class="c">
<a href="'. URL .'apan/library.php">Управление Библиотекой</a><br />
</div>
<div class="c">
<a href="'. URL .'apan/friends.php">Управление Друзьями</a><br />
</div>
<div class="c">
<a href="'. URL .'apan/users.php">Редактирование пользователей</a>
</div>
</div></div>';
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
foot();
?>