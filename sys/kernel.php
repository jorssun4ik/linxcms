<?php
  /*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

ini_set('safe_mode',0);
ini_set('display_errors',1); // показ ошибок
ini_set('register_globals', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_trans_sid', 1);
ini_set('arg_separator.output', "&amp;");

# Назначаем константы
define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/');
define('URL','http://'.$_SERVER['HTTP_HOST'].'/');




# Ген страницы
$time1 = microtime();

Error_Reporting(E_ALL & ~ E_NOTICE);
//mb_internal_encoding('UTF-8');
//set_time_limit(60);

define("ROOTURL",$_SERVER['HTTP_HOST']); //ссылка

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/sys/sys.inc'))
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/sys/sys.inc';
}


# Подключаем функции
require_once ROOT.'/sys/func.php';

# Подключаем базу
if (file_exists(ROOT.'sys/db.php')){
    require_once ROOT.'sys/db.php';
}
elseif (!file_exists(ROOT.'sys/db.php') && file_exists(ROOT.'install/index.php')){
    Header('Location: '. URL . 'install/index.php');
}
else{
    die('Ошибка! Обратитесь к администрации');
}


session_name("SID");
session_start();

$sid =mysqli_escape_string($db, session_id());
if (!preg_match('/[a-z0-9]{32}/i',$sid))$sid=md5(rand(9,999999));

# Авторизация
$aut = 0;
if (!empty($_COOKIE['login']) && !empty($_COOKIE['pass']))
{
$login = filtr($_COOKIE['login']);
$pass = mysqli_escape_string($db, htmlspecialchars($_COOKIE['pass']));
$autt = mysqli_query($db,"SELECT * FROM `users` WHERE `login` = '".$login."' && `pass` = '".$pass."'");
$aut = mysqli_num_rows($autt);
$uid = mysqli_fetch_assoc(mysqli_query($db,"SELECT `login`,`id`,`prava`,`browser`,`ip`,`pstyle` FROM `users` WHERE `login` = '". $login ."'"));
$u_id = strip_tags($uid['id']);
$login = strip_tags($uid['login']);
define('LOGIN',$login);
$prava = intval($uid['prava']);
$uip = $uid['ip'];
$ubrows = $uid['browser'];
$upst = $uid['pstyle'];
}
elseif (isset($_SESSION['login']) && isset($_SESSION['pass']))
{
$login = filtr($_SESSION['login']);
$pass = filtr($_SESSION['pass']);
$autt = mysqli_query($db,"SELECT * FROM `users` WHERE `login` = '".$login."' && `pass` = '".$pass."'");
$aut = mysqli_num_rows($autt);
$uid = mysqli_fetch_assoc(mysqli_query($db,"SELECT `id`,`login`,`prava`,`ip`,`browser`,`pstyle` FROM `users` WHERE `login` = '". $login ."'"));
$login = strip_tags($uid['login']);
define('LOGIN',$login);
$u_id = strip_tags($uid['id']);
$prava = intval($uid['prava']);
$uip = $uid['ip'];
$ubrows = $uid['browser'];
$upst = $uid['pstyle'];
}

//Узнаем ip
$ip=false;
if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']!='127.0.0.1' && preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#iU",$_SERVER['HTTP_X_FORWARDED_FOR']))
{
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
if(isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']!='127.0.0.1' && ereg("^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$",$_SERVER['HTTP_CLIENT_IP']))
{
$ip = $_SERVER['HTTP_CLIENT_IP'];
}
if(isset($_SERVER['REMOTE_ADDR']) && preg_match("/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/si",$_SERVER['REMOTE_ADDR']))
{
$ip = $_SERVER['REMOTE_ADDR'];
}

//Узнаем браузер
if (isset($_SERVER['HTTP_USER_AGENT']))
{
$browser= htmlspecialchars(mysqli_escape_string($db,$_SERVER['HTTP_USER_AGENT']));
//$browser=strtok($browser, '/');
//$browser=strtok($browser, '('); // оставляем только то, что до скобки
//$ua=eregi_replace('[^a-z_\./ 0-9\-]', null, $ua); // вырезаем все "левые" символы
}
// Опера мини тоже посылает данные о телефоне :)
if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) && ereg('Opera',$ua))
{
$browser= htmlspecialchars(mysqli_escape_string($db,$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']));
//$ua_om=strtok($ua_om, '/');
//$ua_om=strtok($ua_om, '(');
//$ua_om=eregi_replace('[^a-z_\. 0-9\-]', null, $ua_om);
//$ua='Opera Mini ('.$ua_om.')';
}


$li = mysqli_fetch_assoc(quer("SELECT `id` FROM `users` WHERE `login` = '".$login."'"));
$id = strip_tags($li['id']);

if (mysqli_num_rows(quer("SELECT * FROM `counter` WHERE `u_id` = '".$id."'")) == 0)
{
    quer("INSERT INTO `counter` SET `u_id` = '".$id."'");
}
$sym = 2000;
if ($aut > 0)
{
$sett = mysqli_fetch_assoc(quer("SELECT * FROM `user_set` WHERE `u_id`='". $u_id ."'"));
$num = strip_tags($sett['count']);

if ($num == 0)
{
    $num = 10;
    quer("UPDATE `user_set` SET `count` = '10' WHERE `u_id` = '". $u_id ."'");
}
}
else
{
    $num = 10;
}

$prov = mysqli_num_rows(quer("SELECT * FROM `user_set` WHERE `u_id` = '". $u_id ."'"));
if ($prov == 0)
{
    //Вписываемся в бд настройки
    quer("INSERT INTO `user_set` SET `u_id` = '". $u_id ."', `count` = '10'");
}
$ban_query = quer("SELECT * FROM `ban` WHERE `u_id` = '".$u_id."'");

if (mysqli_num_rows($ban_query))
{
    $res = mysqli_fetch_assoc($ban_query);

    $who_id = strip_tags($res['who_id']);
    $stime = strip_tags($res['stime']);
    $dtime = strip_tags($res['dtime']);
    $cause = $res['cause'];

    $w = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $who_id ."'"));
    $wlogin = $w['login'];

    if ($dtime > time())
    {
    $set['title'] = 'Бан!';
    head();
    echo '<div class="top_title">Бан</div>
    <div class="menu">
    <div class="menu">Вы получили бан!<br />
    Причина: <b>'. $cause .'</b><br />
    Кто: <b>'. $wlogin .'</b><br />
    Время окончания: <b>'. get_time($dtime,5) .'</b><br />
    Теперь: <b>'. get_time(time(),5) .'</b>
    </div>';
    foot();
    exit();
    }
    else
    {
        $set['title'] = 'Конец бана:)';
        head();
        quer("INSERT INTO `ban_log` SET `u_id` = '". $u_id ."', `who_id` = '". $who_id ."', `cause` = '". $cause ."', `stime` = '". $stime ."', `dtime` = '". $dtime ."'"); //Добавляем в логи банов
        quer("DELETE FROM `ban` WHERE `u_id` = '". $u_id ."'"); //Удаляем бан
        echo '<div class="c">Вы успешно разблокированы!<br /><a href="http://'. $_SERVER['HTTP_HOST'] .'">На главную</a></div>';
        foot();
        exit();
    }
}


if ($aut > 0 && isset($_SESSION['online']))
{
    quer("DELETE FROM `online` WHERE `id` = '". mysqli_escape_string($db, $_SESSION['online']) ."'");
    unset($_SESSION['online']);
}
$flimit = 6000;


if (isset($site) && $site == 'off' && $prava < 7)
{
    $set['title'] = 'Обновление сайта';
    head();
    echo '<div class="c">Сайт закрыт</div>
    <div class="c">В данный момент проходит обновление портала, зайдите позже!</div>';

    foot();
    exit();
}

//Удаление пользователей:
/*
$nedl = 3600*24*21; //3 недели

$time = time()-$nedl;

$d = quer("SELECT * FROM `users` WHERE `last_time` < '". $time ."'"); //Список людей которые не заходили на портал 3 недели

if (mysqli_num_rows($d) > 0) //Если есть то будем дальше проверять
{
    while($dd = mysqli_fetch_assoc($d))
    {
        $uid = $dd['id']; //ид пользователя
        $ulogin = $dd['login']; //логин пользователя

        $fcount = mysqli_num_rows(quer("SELECT * FROM `forum` WHERE `log_id` = '". $uid ."' && `type` = 'p'"));

        if ($fcount < 20)
        {

            # заносим чела в лог
            quer("INSERT INTO `users_log` SET `login` = '". $ulogin ."', `time` = '". time() ."'");

            #Удаляем
            quer("DELETE FROM `users` WHERE `id` = '". $uid ."'");
            quer("DELETE FROM `anketa` WHERE `u_id` = '".$uid."'");
            quer("DELETE FROM `counter` WHERE `u_id` = '". $uid ."'");
            quer("DELETE FROM `user_set` WHERE `u_id` = '". $uid ."'");

            //Оптимизируем
            quer("OPTIMIZE TABLE `users`,`anketa`,`counter`,`user_set`,`users_log`");

        }
    }
}
*/
/*$res = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `title` != '' && `site` != ''"));

if ($res > 0)
{
    //тогда выполняем все =)
    quer("UPDATE `reclame` SET `title` = '', `site` = '', `rtime` = '0', `dtime` = '0', `main` = '0' WHERE `dtime` <= '". time() ."'");
}
*/
//Если пользователь зашел недавно и изменен ip адрес его, то изменяем! А также и brows
if ($aut > 0)
{
    if ($ip != $uip)
    {
        quer("UPDATE `users` SET `ip` = '". $ip ."' WHERE `id` = '". $u_id ."'");
    }
    if ($browser != $ubrows)
    {
        quer("UPDATE `users` SET `browser` = '". $browser ."' WHERE `id` = '". $u_id ."'");
    }
} //Конец =)
//Проверяем или заблокирован человек по ip
$ipres = mysqli_num_rows(quer("SELECT `id` FROM `ip_ban` WHERE `ip` = '". $ip ."'"));

if ($ipres > 0)
{
    $set['title'] = 'Bad Request';
    head();
    echo '<div class="c">Your ip adress don\'t allow for this site!</div>';
    foot();
}
//Еси непоказано где находится, то пишем неопознаная зона
if (!isset($set['where']) || empty($set['where']))
{
    $set['where'] = 'на главной';
}

//Удаляем старые файлы
//old($_SERVER['DOCUMENT_ROOT'].'/temp',10);
//old($_SERVER['DOCUMENT_ROOT'].'/forum/temp',10);



//Подсчет онлайна
$vremya = 60 * 5; //Время засчитываемое нахождение человека на сайте, если он есть
$time = time() - $vremya;
# Проверяем или сессия что чел онлайн существует
//Если зареган проверяем по id
$page = $_SERVER['SCRIPT_NAME'];
if ($aut > 0) {
    $r = quer("SELECT `id` FROM `users` WHERE `login` = '" . $login . "'");
    $rw = mysqli_fetch_assoc($r);
    $u_id = $rw['id'];
    if (mysqli_num_rows(quer("SELECT * FROM `online` WHERE `u_id` = '" . $u_id . "'")) >
        0) {
        quer("UPDATE `online` SET `ip`='" . $ip . "',`browser`= '" . $browser .
            "',`time` = '" . time() . "', `where` = '" . $set['where'] . "', `page` = '" . $page .
            "' WHERE `u_id` = '" . $u_id . "'");
        quer("UPDATE `users` SET `last_time` = '" . time() . "' WHERE `id` = '" . $u_id .
            "'");
    } else {

        quer("INSERT INTO `online` SET `u_id` = '" . $u_id . "', `ip` = '" . $ip .
            "', `browser` = '" . $browser . "', `time` = '" . time() . "', `where` = '" . $set['where'] .
            "', `page` = '" . $page . "'");
        $_SESSION['id'] = mysqli_insert_id($db);
        //Логи людей
        quer("UPDATE `users` SET `last_time` = '" . time() . "' WHERE `id` = '" . $u_id .
            "'");
    }

} else {
    //Тут про гостей)
    if (isset($_SESSION['online']) && !empty($_SESSION['online'])) {
        $q = quer("SELECT * FROM `online` WHERE `id` = '" . mysqli_escape_string($db, $_SESSION['online']) .
            "'");
        $i = quer("SELECT * FROM `online` WHERE `ip` = '" . $ip . "'");
        if (mysqli_num_rows($q) > 0) {
            $on = mysqli_fetch_assoc($q);
            $_SESSION['online'] = $on['id'];
            quer("UPDATE `online` SET `ip` = '" . $ip . "', `browser` = '" . $browser .
                "', `time` = '" . time() . "', `where` = '" . $set['where'] . "', `page` = '" .
                $page . "' WHERE `id` = '" . mysqli_escape_string($db, $_SESSION['online']) .
                "'");
        } elseif (mysqli_num_rows($i) > 0) {
            $ii = mysqli_fetch_assoc($i);
            $_SESSION['online'] = $ii['id'];
            quer("UPDATE `online` SET `time` = '" . time() . "', `where` = '" . $set['where'] .
                "', `page` = '" . $page . "' WHERE `id` = '" . $ii['id'] . "'");

        } else {
            $q = quer("INSERT INTO `online` SET `ip` = '" . $ip . "', `browser` = '" . $browser .
                "', `time` = '" . time() . "', `where` = '" . $set['where'] . "', `page` = '" .
                $page . "'");
            $n = quer("SELECT `id` FROM `online` ORDER BY `id` DESC LIMIT 1");
            $nn = mysqli_fetch_assoc($n);
            $_SESSION['online'] = $nn['id'];
        }
    } else {
        $q = quer("INSERT INTO `online` SET `ip` = '" . $ip . "', `browser` = '" . $browser .
            "', `time` = '" . time() . "', `where` = '" . $set['where'] . "', `page` = '" .
            $page . "'");
        $n = quer("SELECT `id` FROM `online` ORDER BY `id` DESC LIMIT 1");
        $nn = mysqli_fetch_assoc($n);
        $_SESSION['online'] = $nn['id'];

    }
}
//Удаляем всех онлайн, кого сессия прошла
quer("DELETE FROM `online` WHERE `time` < '" . $time . "'");

# Удаляем рекламу, которая закончилась
quer("DELETE FROM `reclame` WHERE `time` < '". time() ."'");


# Подсчет
$p = isset($_GET['p']) ? abs(intval($_GET['p'])) : 1;

if ($p > 1)   $start = ($p - 1) * $num;
else   $start = 0;


$q = quer("SELECT * FROM `users`");

//Узнаем с чего чел зашел
$h_ua = str_replace('windows ce', 'windows', strtolower(getenv('HTTP_USER_AGENT')));
if (strpos($h_ua, 'windows') !== false || strpos($h_ua, 'linux') !== false ||
    strpos($h_ua, 'bsd') !== false || strpos($h_ua, 'x11') !== false || strpos($h_ua,
    'unix') !== false || strpos($h_ua, 'macintosh') !== false || strpos($h_ua,
    'macos') !== false) {
    define('DEVICE','Computer');
} else {
    if ((strpos(getenv('HTTP_ACCEPT'), "text/html") !== false) || (strpos(getenv('HTTP_USER_AGENT'),
        "Mozilla") !== false)) {
        define('DEVICE','Mobile');
    } else {
        define('DEVICE','Mobile');
    }
}

?>
