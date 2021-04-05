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

$query = quer("SELECT * FROM `anketa` WHERE `u_id` = '".$u_id."'");

$result = mysqli_num_rows($query);
$set['title'] = 'Личное меню - Анкета';
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">';

if ($aut > 0)
{
mc();
}

echo 'Анкета
</div>
';

if ($result == 0)
{
quer("INSERT INTO `anketa` SET `u_id` = '".$u_id."'");
echo 'Обновляем анкетные данные!';
echo '<br /><a href="http://'.$_SERVER['HTTP_HOST'].'/upan/anketa.php">Продолжить</a>';
}
else
{
$d = mysqli_fetch_assoc($query);
if (isset($_GET['act']))
{
$act = trim(stripslashes(htmlspecialchars($_GET['act'])));
if ($act == 'save')
{
$name = mysqli_escape_string($db, htmlspecialchars($_POST['name']));
$fname = mysqli_escape_string($db,htmlspecialchars($_POST['fname']));
$sex = mysqli_escape_string($db,htmlspecialchars(trim($_POST['sex'])));
$model = mysqli_escape_string($db,htmlspecialchars(trim($_POST['model'])));
$operator = mysqli_escape_string($db,htmlspecialchars(trim($_POST['operator'])));
$country = mysqli_escape_string($db,htmlspecialchars(trim($_POST['country'])));
$city = mysqli_escape_string($db,htmlspecialchars(trim($_POST['city'])));
$site = mysqli_escape_string($db,htmlspecialchars(trim($_POST['site'])));

//Обрабатываем ссылку)
if (!preg_match("/[a-z0-9\-]{2,}\.[a-z]{2,4}/i",$site))
{
    $site = ''; //Значит пусто
}
else
{
    $s = str_ireplace("http://","",$site);
    $s = explode("/",$s);
    $site = $s[0];
}

$interes = mysqli_escape_string($db, htmlspecialchars(trim($_POST['interes'])));
$rzan = mysqli_escape_string($db, htmlspecialchars(trim($_POST['rzan'])));

$birth_d = (int)$_POST['birth_d'];
$birth_m = (int)$_POST['birth_m'];
$birth_y = (int)$_POST['birth_y'];
if ($birth_d == '')
{
	$birth_d = $d['birth_d'];
}
if ($birth_m == '')
{
	$birth_m = $d['birth_m'];
}
if ($birth_y == '')
{
	$birth_y = $d['birth_y'];
}
$about = mysqli_escape_string($db,htmlspecialchars($_POST['about']));
$email = mysqli_escape_string($db,htmlspecialchars($_POST['email']));
$icq = (int)$_POST['icq'];
$skype = mysqli_escape_string($db,htmlspecialchars($_POST['skype']));
$jabber = mysqli_escape_string($db,htmlspecialchars($_POST['jabber']));
quer("UPDATE `anketa` SET `name` = '".$name."', `fname` = '".$fname."',`birth_d` = '".$birth_d."',
 `birth_m` = '".$birth_m."', `birth_y` = '".$birth_y."', `sex` = '". $sex ."', `about` = '".$about."',
 `icq` = '".$icq."', `skype` = '".$skype."', `jabber` = '".$jabber."', `email` = '".$email."', `site` = '". $site ."',
 `model` = '". $model ."', `operator` = '". $operator ."', `country` = '". $country ."',
 `city` = '". $city ."', `interes` = '". $interes ."', `rzan` = '". $rzan ."' WHERE `u_id` = '".$id."'");
echo 'Изменения приняты!<br />';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/upan/anketa.php">В анкету</a>';
}
elseif ($act == 'change')
{
	echo '<div class="c"><form action="http://'.$_SERVER['HTTP_HOST'].'/upan/anketa.php?act=save" method="post">';
	echo 'Имя:<br /><input name="name" value="'.$d['name'].'"/>';
	//echo '<br />';
	echo '<br />Фамилия:<br /><input name="fname" value="'.$d['fname'].'"/>';
	echo '<br />';
	echo 'День Рождения:<br />';
	$n = 1;
	if ($d['birth_d'] == 0)
	{
		echo '<select name="birth_d" class="textbox">';
	}
	else
	{
	echo '<select name="birth_d" class="textbox"><option value="'. $d['birth_d'] .'">'.$d['birth_d'].'</option>';
	}
	while ($n <= 31)
	{
	echo '<option value="'.$n.'">'.$n.'</option>';
	$n++;
	}
	echo '</select>&nbsp;';
	if ($d['birth_m'] == 0)
	{
		echo '<select name="birth_m" class="textbox">';
	}
	else
	{
	echo '<select name="birth_m" class="textbox"><option value="'. $d['birth_m'] .'">'.$d['birth_m'].'</option>';
	}
	$m = 1;
	while ($m <= 12)
	{
	echo '<option value="'.$m.'">'.$m.'</option>';
	$m++;
	}
	echo '</select>&nbsp;';
	if ($d['birth_y'] == 0)
	{
		echo '<select name="birth_y" class="textbox">';
	}
	else
	{
	echo '<select name="birth_y" class="textbox"><option value="'. $d['birth_y'] .'">'.$d['birth_y'].'</option>';
	}
	$y = 1930;
	while ($y <= 2000)
	{
	echo '<option value="'.$y.'">'.$y.'</option>';
	$y++;
	}
	echo '</select>&nbsp;';
	echo '<br />';
	echo 'Пол:<br />';
	if ($d['sex'] = 'm')
	{
		echo '<select name="sex">
		<option value="m">Парень</option>
		<option value="f">Девушка</option>
		</select>
		';

	}
	elseif ($d['sex'] = 'f')
	{
		echo '<select name="sex">
		<option value="f">Девушка</option>
		<option value="m">Парень</option>
		</select>
		';
	}
	else
	{
		echo '<select name="sex">
		<option value="m">Парень</option>
		<option value="f">Девушка</option>
		</select>
		';
	}
	echo '<br />';
    echo 'Ваш сайт(<small>с http://</small>):<br /><input name="site" value="http://'.$d['site'].'"/>';
	echo '<br />';
    echo 'Модель Телефона:<br /><input name="model" value="'.$d['model'].'"/>';
	echo '<br />';
    echo 'Оператор:<br /><input name="operator" value="'.$d['operator'].'"/>';
	echo '<br />';
    echo 'Страна:<br /><input name="country" value="'.$d['country'].'"/>';
	echo '<br />';
    echo 'Город:<br /><input name="city" value="'.$d['city'].'"/>';
	echo '<br />';
    echo 'Интересы:<br /><textarea name="interes">'. $d['interes'] .'</textarea>';
	echo '<br />';
    echo 'Род занятий:<br /><input name="rzan" value="'.$d['rzan'].'"/>';
	echo '<br />';
	echo 'О себе:<br /><input name="about" value="'.$d['about'].'"/>';
	echo '<br />';
	echo 'E-Mail:<br /><input name="email" value="'.$d['email'].'"/>';
	echo '<br />';
	echo 'ICQ:<br /><input name="icq" value="'.strip_tags($d['icq']).'"/>';
	echo '<br />';
	echo 'Skype:<br /><input name="skype" value="'.$d['skype'].'"/>';
	echo '<br />';
	echo 'Jabber:<br /><input name="jabber" value="'.$d['jabber'].'"/>';
    echo '<br />';
    echo '<input type="submit" value="Изменить"/></form>';
}
}
else
{
$u = mysqli_fetch_assoc(quer("SELECT `prava`,`time`,`last_time` FROM `users` WHERE `login` = '". $login ."'"));
$time = strip_tags($u['time']);
$last_time = strip_tags($u['last_time']);

echo '<div class="zag">';

if ($d['sex'] == 'm')
{
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/images/icons/male.gif" title="Мужик я)))">';
}
elseif ($d['sex'] == 'f')
{
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/images/icons/female.gif" title="Красотка">';
}
echo $login;
if ($prava == 9)
{
	echo '<font color="blue">[Админ портала]</font>';
}
elseif ($prava == 8)
{
    echo '<font color="blue">[Админ]</font>';
}
elseif ($prava == 7)
{
    echo '<font color="blue">[Модератор]</font>';
}
elseif ($prava == 6)
{
    echo '<font color="blue">[Умник]</font>';
}
elseif ($prava == 0)
{
	echo '<font color="blue">[Пользователь]</font>';
}
echo '</div>';

if (file_exists("avatars/".$login.".gif"))
{

	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/upan/avatars/'. $login .'.gif" alt="'. $login .'" title="'. $login .'"></img>';

	echo '<br /><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/arefr.php">Обновить</a><br /><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/adel.php">Удалить</a>';
}
else
{
	echo '<img src="http://'. $_SERVER['HTTP_HOST'] .'/upan/avatars/no_avatar.jpg" alt="Аватара нет" title="Аватара нет"/>';
	echo '<br/><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/aadd.php">Загрузить</a>';
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

$plus = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $u_id ."' && `type` = 'p'"));
$minus = mysqli_num_rows(quer("SELECT * FROM `rating` WHERE `l_id` = '". $u_id ."' && `type` = 'm'"));

$fcount = mysqli_num_rows(quer("SELECT * FROM `forum_m` WHERE `l_id` = '". $u_id ."'"));
$tcount = mysqli_num_rows(quer("SELECT * FROM `forum_t` WHERE `l_id` = '". $u_id ."'"));
$gcount = mysqli_num_rows(quer("SELECT * FROM `guest` WHERE `login` = '". $login ."'"));

echo '<div class="c">Репутация:&nbsp;'.reputation($u_id).'</div>';

echo '<div class="c">Рейтинг: ';
if ($plus > 0 OR $minus > 0)
{
    echo '[<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/rating/'. $id .'/">+'. $plus .'/-'. $minus .'</a>]';
}
else
{
    echo '[+'. $plus .'/-'. $minus .']';
}

// Количество блокирований
$bcount = mysqli_num_rows(quer("SELECT `id` FROM `ban_log` WHERE `u_id` = '". $u_id ."'"));

echo '</div><div class="c">Блокирований:&nbsp;';
    echo '<a href="ban_history.php?id='. $u_id .'">'.$bcount.'</a>';
echo '</div>';
echo '<div class="c">Тем на форуме: '. $tcount .'</div>
<div class="c">Постов на форуме: '. $fcount .'</div>
<div class="c">Постов в гостевой: '. $gcount .'</div>
';
echo '<div class="c">Дата регистрации: '.get_time($time,5);
echo '</div>';
echo '<div class="c">Дата последнего посещения: '.get_time($last_time,5);
echo '</div>';
echo '<br />';
echo '&nbsp;&nbsp;<a href="anketa.php?act=change">Изменить</a></div>';
}
}
//echo '</div>';
foot();
} else
{
	Header("Location: ../index.php");
}

?>
