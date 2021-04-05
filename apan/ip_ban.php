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
$set['title'] = 'Админ панель::Блокирование по IP';
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Админ Панель::Блокирование по IP
</div>';

$ip = htmlspecialchars(stripslashes($_GET['ip']));

$iip = explode('.',$ip);

$ipres = quer("SELECT `login`,`prava` FROM `users` WHERE `prava` >= 6 && `ip` = '". $ip ."'");

$ipban = mysqli_num_rows(quer("SELECT `id` FROM `ip_ban` WHERE `ip` = '". $ip ."'"));

if (count($iip) > 4 OR count($iip) < 4)
{
echo '<div class="c">Это не IP адрес!
</div>
';
}
elseif (mysqli_num_rows($ipres) > 0)
{
    echo '<div class="c">Такой IP использует лица из администрации, а именно:<br />';
    while ($q = mysqli_fetch_assoc($ipres))
    {
        echo '&nbsp;'.$q['login'] . status($q['prava']) .'<br />';
    }
    echo '</div>';
}
elseif ($ipban > 0)
{
    echo '<div class="c">Такой IP адрес итак заблокирован!</div>';
}
else
{
    quer("INSERT INTO `ip_ban`(`ip`) VALUES('". $ip ."')");
    echo '<div class="c">Вы успешно заблокировали IP адрес('. $ip .')!</div>';
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