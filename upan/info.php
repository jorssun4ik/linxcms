<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит анкету';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{

if (is_numeric($_GET['id']))
{
if (mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `id` = '".mysqli_escape_string($db,$_GET['id'])."'")) > 0)
{	
$id = (int)$_GET['id']; 

if ($id == $u_id)
{
Header("Location: anketa.php");
exit();    
}

$u = mysqli_fetch_assoc(quer("SELECT `login`,`prava`,`time`,`last_time`,`ip`,`browser` FROM `users` WHERE `id` = '". $id ."'"));
$u_login = $u['login'];
$query = quer("SELECT * FROM `anketa` WHERE `u_id` = '".$id."'");

if (isset($u) && !empty($u)){	
$result = mysqli_num_rows($query);	
$set['title'] = 'Личное меню - Анкета';
head();
mc();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Анкета
</div>
';
$ban_query = quer("SELECT * FROM `ban` WHERE `u_id` = '".$id."'");

if (mysqli_num_rows($ban_query))
{
	$res = mysqli_fetch_assoc($ban_query);
	
	$who_id = strip_tags($res['who_id']);
	$dtime = strip_tags($res['dtime']);
	$cause = $res['cause'];
	
	$w = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $who_id ."'"));
	$wlogin = $w['login'];
	
	echo '<div class="top_title">Пользователь в бане!</div>
	<div class="menu">Бан!<br />
	Причина: <b>'. $cause .'</b><br />
	Кто: <b>'. $wlogin .'</b><br />
	Время окончания: <b>'. get_time($dtime,5) .'</b><br />
	</div>';
	
}
if ($result == 0)
{
quer("INSERT INTO `anketa` SET `u_id` = '".$id."'");	
echo 'Обновляем анкетные данные!';
echo '<br /><a href="http://'.$_SERVER['HTTP_HOST'].'/upan/info.php?id='. $id .'">Продолжить</a>';	
}
else
{
$d = mysqli_fetch_assoc($query);	
$on_result = mysqli_num_rows(quer("SELECT `id` FROM `online` WHERE `u_id` = '". $id ."'"));
echo '<div class="top_title">';
if ($d['sex'] == 'm')
{
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/images/icons/male.gif" title="Мужик я)))">';
} 
elseif ($d['sex'] == 'f')
{
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/images/icons/female.gif" title="Красотка">';
}

echo $u_login;
	if ($on_result > 0)
	{
		echo '<font color="green">[on]</font>';
	}
	else
	{
		echo '<font color="red">[off]</font>';
	}
$pprava = strip_tags($u['prava']);
$time = strip_tags($u['time']);
$last_time = strip_tags($u['last_time']);
if ($pprava == 9)
{
	echo '<font color="blue">[Админ портала]</font>';
}
elseif ($pprava == 8)
{
    echo '<font color="blue">[Админ]</font>';
}
elseif ($pprava == 7)
{
    echo '<font color="blue">[Модератор]</font>';
}
elseif ($pprava == 6)
{
    echo '<font color="blue">[Умник]</font>';
}
elseif ($pprava == 0)
{
	echo '<font color="blue">[Пользователь]</font>';
}
echo '</div>';
if (file_exists("avatars/".$u_login.".gif"))
{
	
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/upan/avatars/'. $u_login .'.gif" alt="'. $login .'" title="'. $login .'"></img>';
}
else
{
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/upan/avatars/no_avatar.gif" alt="Аватара нет" title="Аватара нет"/></img>';
}
if (!empty($d['name']))
{
echo '<div class="c">Имя: '.$d['name'];
echo '</div>';
}
if (!empty($d['fname']))
{
echo '<div class="c">Фамилия: '.$d['fname'];
echo '</div>';
}
if (isset($d['sex']) && !empty($d['sex'])){
echo '<div class="c">Пол:';
if ($d['sex'] == 'm'){
    echo '<b>Парень</b>';
}
else{
    echo '<b>Девушка</b>';
}   
echo '</div>';
}

if (!empty($d['birth_d']) && !empty($d['birth_m']) && !empty($d['birth_y']))
{
echo '<div class="c">День Рождения: '.$d['birth_d'].' '.mesyac($d['birth_m']).' '.$d['birth_y'].' года';
echo '</div>';
}

if (!empty($d['site']))
{
    $site = str_ireplace('http://','',$d['site']);
    $d['site'] = 'http://'.$site;
    echo '<div class="c">Cайт: <a href="'. $d['site'] .'" target="_blank">'. $d['site'] .'</a></div>';
}
if (!empty($d['model']))
{
    echo '<div class="c">Модель телефона: '. $d['model'] .'</div>';
}
if (!empty($d['operator']))
{
    echo '<div class="c">Оператор: '. $d['operator'] .'</div>';
}
if (!empty($d['country']))
{
    echo '<div class="c">Страна: '. $d['country'] .'</div>';
}
if (!empty($d['city']))
{
    echo '<div class="c">Город: '. $d['city'] .'</div>';
}
if (!empty($d['interes']))
{
    echo '<div class="c">Интересы: '. nl2br($d['interes']) .'</div>';
}
if (!empty($d['rzan']))
{
    echo '<div class="c">Род занятий: '. $d['rzan'] .'</div>';
}

$about = str_ireplace("\n","<br />",$d['about']);
if (!empty($about))
{
echo '<div class="c">О себе: '.$about;
echo '</div>';
}
if (!empty($d['email']))
{
echo '<div class="c">E-Mail: <img src="email.php?email='.$d['email'].'"alt="x"/>';
echo '</div>';
}
if (!empty($d['icq']))
{
echo '<div class="c">ICQ: '.$d['icq'];
echo '</div>';
}
if (!empty($d['skype']))
{
echo '<div class="c">Skype: '.$d['skype'];
echo '</div>';
}
if (!empty($d['jabber']))
{
echo '<div class="c">Jabber: '.$d['jabber'];
echo '</div>';
}
$fcount = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `l_id` = '". $id ."'"));
$tcount = mysqli_num_rows(quer("SELECT `id` FROM `forum_t` WHERE `l_id` = '". $id ."'"));
$gcount = mysqli_num_rows(quer("SELECT `id` FROM `guest` WHERE `login` = '". $u_login ."'"));

//проверить сколько у тебя сообщений на форуме
$myfcount = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `l_id` = '". $u_id ."'"));

echo '<div class="c">Репутация:&nbsp;'.reputation($id).'</div>';

//Место для рейтинга
$plus = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $id ."' && `type` = 'p'"));
$minus = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $id ."' && `type` = 'm'"));

echo '<div class="c">Рейтинг: ';
if ($plus > 0 OR $minus > 0)
{
    echo '[<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/rating/'. $id .'/">+'. $plus .'/-'. $minus .'</a>]';
}
else
{
    echo '[+'. $plus .'/-'. $minus .']';
}

$res = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $id ."' && `w_id` = '". $u_id ."'"));

$rep_count = 50;

if ($myfcount >= $rep_count && $res == 0 OR $prava >= 7 && $res == 0)
{
    echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/crating/'. $id .'/?type=plus">+</a>/<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/crating/'. $id .'/type=minus">-</a>';
}
echo '</div>';


// Количество блокирований
$bcount = mysqli_num_rows(quer("SELECT `id` FROM `ban_log` WHERE `u_id` = '". $id ."'"));

echo '<div class="c">Блокирований:&nbsp;';
    echo '<a href="ban_history.php?id='. $id .'">'.$bcount.'</a>';
echo '</div>';

echo '<div class="c">Тем на форуме: '. $tcount .'</div>
<div class="c">Постов на форуме: '. $fcount .'</div>
<div class="c">Постов в гостевой: '. $gcount .'</div>
';
echo '<div class="c">Дата регистрации: '.get_time($time,5);
echo '</div>';
echo '<div class="c">Дата последнего посещения: '.get_time($last_time,5);
echo '</div>';
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/msg.php?act=chat&amp;id='. $id .'">Общение с '. $u_login .'</a></div>';
$c = (quer("SELECT `type` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."'"));
if (mysqli_num_rows($c) > 0){
    $ct = mysqli_fetch_assoc($c);
    if ($ct['type'] == 0){
        echo '<div class="c"><a href="msg.php?act=add_contact&amp;id='. $id .'">Добавить в контакты</a></div>';
    }
    else{
        echo '<div class="c"><a href="msg.php?act=add_ignore&amp;id='. $id .'">Добавить в игнор</a></div>';
    }
}
else{
echo '<div class="c"><a href="msg.php?act=add_contact&amp;id='. $id .'">Добавить в контакты</a></div>
<div class="c"><a href="msg.php?act=add_ignore&amp;id='. $id .'">Добавить в игнор</a></div>';
}
$prav = mysqli_fetch_assoc(quer("SELECT `prava` FROM `users` WHERE `id` = '". $u_id ."'"));
$prava = strip_tags($prav['prava']);
if ($prava >= 7)
{
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/mpan/give_ban.php?id='. $id .'">Забанить</a></div>';
}
if ($prava >= 9)
{
    $res = $u['ip'] != '' ? '[<a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/ip_ban.php?ip='. $u['ip'] .'">BAN</a>]' : '';
    echo '<div class="c">IP: '. $u['ip'] . $res .'</div>
    <div class="c">Soft: '. $u['browser'] .'</div>';
}
}
echo '</div></div>';
foot();
}
}
else{
    $set['title'] = 'Ошибка!';
    head();
    mc();
    echo '<div class="all"><div class="menu"><div class="text"><div class="title">
    Анкета
    </div>';
    echo '<div class="c">Пользователь не найден!</div>';
    echo '</div></div>';
    foot();

}
} else
{
	Header("Location: ../index.php");
}
} else
{
	Header("Location: ../index.php");
}
?>