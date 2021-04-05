<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

//Размер файла
function size($file)
{
    global $_SERVER;

    $siz = filesize($file);

    if ($siz >= 1024) {
        $siz = $siz / 1024;
        if ($siz < 1024) {
            $siz = substr($siz, 0, 6);
        }
        $size = $siz . ' кб';
        if ($siz >= 1024) {
            $siz = $siz / 1024;
            if ($siz < 1024) {
                $siz = substr($siz, 0, 6);
            }
            $size = $siz . ' мб';
        }
    } else {
        $size = $siz . ' б';
    }

    return $size;
}

//Узнаем расширение файла
function ext($file)
{
    return strtolower(pathinfo($file, PATHINFO_EXTENSION));
}

//Смотрим статус чела он или офф
function online($id)
{
   global $_SERVER;
    $res = mysqli_fetch_assoc(quer("SELECT `id` FROM `online` WHERE `u_id` = '" . $id .
        "'"));

    if ($res > 0) {
      //return '<font color="green">[online]</font>';
      return '<img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/online.gif" alt = "on"/>';
    } else {
        //return '<font color="red">[offline]</font>';
        return '<img src = "http://'. $_SERVER['HTTP_HOST'] .'/images/icons/offline.gif" alt = "offline"/>';
    }

}

//Херня)
function exten($id)
{
    if ($id == 1) {
        $arr = array('zip', 'rar');
    }
    return $arr;
}


//Упрощаем запрос к базе(чтобы не надо было писать $db каждый раз, mysqli это требует)
function quer($query)
{
    global $db;

    return mysqli_query($db, $query);
}

//Как давно чел отошел
function away($time)
{
    $now = time();
    $m = $now - $time;

    if ($m > 86400) {
        $d = $m / 86400; //Скока дней
        $d = floor($d);
        $h = $m - ($d * 86400);
        $hour = $h / 3600; //Скока часов
        $hour = floor($hour);
        $mn = $m - ($d * 86400) - ($hour * 3600);
        $mn = $mn / 60;
        $min = floor($mn); //Минуты
    } elseif ($m > 3600) {
        $hour = $m / 3600; //Скока часов
        $hour = floor($hour);
        $mn = $m - ($hour * 3600);
        $mn = $mn / 60;
        $min = floor($mn); //Минуты
    } else {
        $mn = $m / 60;
        $min = floor($mn); //Минуты
    }

    $time = ''; //инитиализация

    if ($d > 0) {
        $time .= $d . ' дн.&nbsp;назад';
    } elseif ($hour > 0) {
        $time .= $hour . ' час.&nbsp;назад';
    } elseif ($min > 0) {
        $time .= $min . ' мин.&nbsp;назад';
    } else {
        $time .= 'Только что был тут:)';
    }

    $time = '[<font color="green">' . $time . '</font>]';

    return $time;
}

//Репутация пользователя
function reputation($id)
{
    global $sql;
    /*
    РЕПУТАЦИЯ = 41,26 (Все сообщения пользователя 120 +
    Все темы пользователя 15 + Рейтинг 64 + Общий рейтинг всех сообщений 600+
    Общий рейтинг всех тем 67,5 / Сколько дней на сайте 21)

    */

    $plus = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `type` = 'p' && `l_id` = '" .
        $id . "'"));
    $minus = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `type` = 'm' && `l_id` = '" .
        $id . "'"));
    $prate = $plus - $minus; //приватный рейтинг

    $fcount = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `l_id` = '" . $id .
        "'"));

    $tcount = mysqli_num_rows(quer("SELECT `id` FROM `forum_t` WHERE `l_id` = '" . $id .
        "'"));

    $arate = mysqli_num_rows(quer("SELECT `id` FROM `reputation` WHERE `l_id` = '" .
        $id . "'"));

    $d = mysqli_fetch_assoc(quer("SELECT `time`,`last_time` FROM `users` WHERE `id` = '" .
        $id . "'"));

    $regtime = strip_tags($d['time']);
    $lastime = strip_tags($d['last_time']);

    $time = $lastime - $regtime;
    $day = $time / 3600 / 24;


    if ($tcount > 0) {
        $t = $tcount / $fcount;
    } else {
        $t = 0;
    }

    $pluses = $prate + $t + $arate;
    if ($pluses > 0 && $day > 0) {
        $reput = $pluses / $day;
    } else {
        $reput = 0;
    }
    $r = explode('.', $reput);

    // echo $reput;

    if (isset($r[1]) && strlen($r[1]) > 2) {
        $r2 = substr($r[1], 0, 2);
        $reput = $r[0] . '.' . $r2;
    }

    return $reput;
}

//Рейтинг сообщения
function msg_rate($id)
{
    global $sql;

    $id = (int)$id; //на всякий случай

    $count = mysqli_num_rows(quer("SELECT * FROM `reputation` WHERE `msg_id` = '" .
        $id . "'"));

    if ($count > 0) {
        $i = 0;

        $Query = quer("SELECT `ocenka` FROM `reputation` WHERE `msg_id` = '" . $id . "'");

        while ($q = mysqli_fetch_assoc($Query)) {
            $i = $i + $q['ocenka'];
        }

        $res = $i / $count;
        $res = substr($res, 0, 3);
        return $res;
    } else {
        return 0;
    }
}

//Время
function ftime($time, $value = true)
{ //Функция времени для форума и библы:)
    static $month = array('', 'Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг',
        'Сен', 'Окт', 'Ноя', 'Дек');
    $m = date("m", $time);
    $m = str_ireplace("0", "", $m);
    $vr = $value ? date(",H:i", $time) : '';
    return '(' . date("d {$month[$m]}{$vr}", $time) . ')';
}

//Выводим линк ссылкой
function url_replace($u)
{
    if (!isset($u[3])) {
        return '<a href="' . $u[1] . '" target="_blank">' . $u[2] . '</a>';
    } else {
        return '<a href="' . $u[3] . '" target="_blank">' . $u[3] . '</a>';
    }
}

//Выводим линк ссылкой
function links($msg)
{
    return preg_replace_callback("#\[url=(https?://.+)\](.+)\[/url\]|(https?://\S+[^\s.,>)\];'\"!?])#",
        'url_replace', $msg);
}

//Функция вывода статуса
function status($prava)
{
    if ($prava == '6'){
        $stat = '<font color="green">[mod]</font>';
    }
    elseif ($prava == '7') {
        $stat = '<font color="green">[smod]</font>';
    } elseif ($prava == '8') {
        $stat = '<font color="green">[adm]</font>';
    } elseif ($prava == '9') {
        $stat = '<font color="blue">[creator]</font>';
    } else {
        $stat = '';
    }

    return $stat;
}

//------------------ Функция антимата by VantuZ --------------------//
function antimat($string)
{
   global $_SERVER;
    $mat = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/sys/antimat.inc");
    $arr_mat = explode("|", $mat);

    foreach ($arr_mat as $value) {
        if ($value != "") {
            $string = preg_replace("|$value|iu", "<B>[мат]</B>", $string);
        }
    }

    return $string;
}


//Навигация By w1NNt
function nav($all, $num, $str = '')
{
    global $pag;
    if (is_numeric($_GET['p']) && intval($_GET['p']) <= ceil($all / $num)) {
        $p = intval($_GET['p']);
    } elseif (is_numeric($pag) && $pag <= ceil($all / $num) && $pag > 1) {
        $p = $pag;
    } else {
        $p = 1;
    }
    echo '';
    $prev = $p - 2;
    $next = $p + 3;
    $stall = ceil($all / $num);
    if ($prev < $all && $prev > 1) {
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . $str . '&amp;p=1">[1]</a>...';
    }
    for ($i = $prev; $i < $next; $i++) {
        if ($i <= $stall && $i >= 1) {
            if ($p == $i) {
                echo '<b>[' . $i . ']</b>';
            } else {
                echo ' <a href="' . $_SERVER['PHP_SELF'] . '?' . $str . '&amp;p=' . ($i) . '">[' .
                    $i . ']</a> ';
            }
        }
    }
    if ($next <= $stall) {
        echo ' ... <a href="' . $_SERVER['PHP_SELF'] . '?' . $str . '&amp;p=' . $stall .
            '">[' . $stall . ']</a>';
    }
}

//Функция вывода смайлов
function smiles_p()
{

    $dir = opendir("../images/smiles");
    while ($file = readdir($dir)) {
        if (preg_match("#.gif$#iU", "$file")) {
            $a[] = $file;
        }
    }
    closedir($dir);
    sort($a);

    $total = count($a);
    if (empty($_GET['start']))
        $start = 0;
    else
        $start = abs(intval($_GET['start']));
    if ($total < $start + 10) {
        $end = $total;
    } else {
        $end = $start + 10;
    }
    for ($i = $start; $i < $end; $i++) {

        $smkod = str_replace(".gif", "", $a[$i]);

        echo '<img src="http://' . $_SERVER['HTTP_HOST'] . '/images/smiles/' . $a[$i] .
            '" alt=""/>';
        echo '- :' . $smkod . '<br />';
    }


    $a = count($a);
    $ba = ceil($a / 10);
    $ba2 = floor(($a - 1) / 10) * 10;

    echo '<br/>Страницы:';
    $asd = $start - (10 * 4);
    $asd2 = $start + (10 * 5);

    if ($asd < $a && $asd > 0) {
        echo ' <a href="smile.php?start=0">1</a> ... ';
    }

    for ($i = $asd; $i < $asd2; ) {
        if ($i < $a && $i >= 0) {
            $ii = floor(1 + $i / 10);

            if ($start == $i) {
                echo ' <b>' . $ii . '</b>';
            } else {
                echo ' <a href="?start=' . $i . '">' . $ii . '</a>';
            }
        }


        $i = $i + 10;
    }
    if ($asd2 < $a) {
        echo ' ... <a href="?start=' . $ba2 . '">' . $ba . '</a>';
    }

    echo "<br/>";
}

//Шапка
function head($view = true)
{
    global $set, $device, $db, $aut, $upst, $login;
    if ($title == null)
        $title = $set['title'] . '::' . strtoupper($_SERVER['HTTP_HOST']);
        echo '<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">
        <meta name="HandheldFriendly" content="true">
        <meta name="MobileOptimized" content="width">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="Generator" content="">';

    echo '<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="LinX" />';
    echo '<title>' . $title . '</title>';
    if ($aut > 0) {
        if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/styles/wap/' . $upst)) {
            echo '<link rel="stylesheet" href="http://' . $_SERVER['HTTP_HOST'] .
                '/styles/wap/' . $upst . '/style.css" type="text/css" />';
        } else {
            echo '<link rel="stylesheet" href="http://' . $_SERVER['HTTP_HOST'] .
                '/styles/wap/default/style.css" type="text/css" />';
        }
    } else {
        echo '<link rel="stylesheet" href="http://' . $_SERVER['HTTP_HOST'] .
            '/styles/wap/default/style.css" type="text/css" />';
    }
    echo '</head>
<body>
<div class="page">
<div class="content">
<div class="logo">';
    if ($aut > 0) {
        if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/styles/wap/' . $upst)) {
            echo '<img src="http://' . $_SERVER['HTTP_HOST'] . '/styles/wap/' . $upst .
                '/logo.gif" alt="logo" />';
        } else {
            echo '<img src="http://' . $_SERVER['HTTP_HOST'] .
                '/styles/wap/default/logo.gif" alt="logo" />';
        }
    } else {
        echo '<img src="http://' . $_SERVER['HTTP_HOST'] .
            '/styles/wap/default/logo.gif" alt="logo" />';
    }
    echo '</div>';
    //Просьба не удалять!
    echo '<!-- LinX CMS -->';
    //Просьба не удалять!
if ($view == true){
if ($aut > 0)
{
echo '<div class="header">';
if ($_SERVER['SCRIPT_NAME'] != "/index.php")
{
 echo '<img src="'. URL .'images/icons/home.png" alt="home"/><a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>|';
}
echo '<img src="'. URL .'images/icons/menu.gif" alt="menu"/> <a href="http://'.$_SERVER['HTTP_HOST'].'/upan/?">'. $login .'</a> | ';
echo '<img src="'. URL .'images/icons/exit.gif" alt="exit"/><a href="http://'.$_SERVER['HTTP_HOST'].'/exit.php">Выход</a>
</div>';
} else
{
echo '<div class="header">';
if ($_SERVER['SCRIPT_NAME'] != "/index.php")
{
 echo '<img src="'. URL .'images/icons/home.png" alt="home"/><a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>|';
}
echo '<img src="'. URL .'images/icons/unlock.png" alt="unlock"/><a href="http://'.$_SERVER['HTTP_HOST'].'/registration/">Регистрация</a> | <img src="'. URL .'images/icons/log.png" alt="log"/><a href="http://'.$_SERVER['HTTP_HOST'].'/aut.php">Авторизация</a>
</div>';
}
}

//Модуль рекламы
    $rc = quer("SELECT * FROM `reclame` WHERE `where` = 'u' ORDER BY `position` ASC");

    if (mysqli_num_rows($rc)){

        $r = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = 'u' && `page` = 'main'"));

        if ($r && $_SERVER['SCRIPT_NAME'] == "/index.php"){
            # на главной
            $query = quer("SELECT * FROM `reclame` WHERE `where` = 'u' && `page` = 'main'");
            while ($res = mysqli_fetch_assoc($query)){
                $title = explode('|',$res['title']);
                $c = array_rand($title);
                echo '<div class="reklama"><a href="'. $res['site'] .'">'. $title[$c] .'</a></div>';
            }
        }
        $r = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = 'u' && `page` = 'other'"));
        if ($r && $_SERVER['SCRIPT_NAME'] != "/index.php"){
            # на остальных страницах
            $query = quer("SELECT * FROM `reclame` WHERE `where` = 'u' && `page` = 'other'");
            while ($res = mysqli_fetch_assoc($query)){
                $title = explode('|',$res['title']);
                $c = array_rand($title);
                echo '<div class="reklama"><a href="'. $res['site'] .'">'. $title[$c] .'</a></div>';
            }
        }
        $r = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = 'u' && `page` = 'all'"));
        if ($r){
            # на всех страницах
            $query = quer("SELECT * FROM `reclame` WHERE `where` = 'u' && `page` = 'all'");
            while ($res = mysqli_fetch_assoc($query)){
                $title = explode('|',$res['title']);
                $c = array_rand($title);
                echo '<div class="reklama"><a href="'. $res['site'] .'">'. $title[$c] .'</a></div>';
            }
        }
    }

}

//Ноги сайта
function foot()
{
    global $time1, $db, $mbann, $obann, $aut, $upst,$_SERVER;
    require_once $_SERVER['DOCUMENT_ROOT'] . '/sys/end.php';

    $gen = substr($gen, 0, 9);
    $allonl = mysqli_num_rows(quer("SELECT * FROM `online` GROUP BY `ip`,`browser`"));
    $uonl = mysqli_num_rows(quer("SELECT * FROM `online` WHERE `u_id` != ''"));
    $gonl = mysqli_num_rows(quer("SELECT * FROM `online` WHERE `u_id` = '' GROUP BY `ip`,`browser`"));
    echo '</div>';
    //Модуль рекламы
    $rc = quer("SELECT * FROM `reclame` WHERE `where` = 'd' ORDER BY `position` ASC");

    if (mysqli_num_rows($rc)){

        $r = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = 'd' && `page` = 'main'"));

        if ($r && $_SERVER['SCRIPT_NAME'] == "/index.php"){
            # на главной
            $query = quer("SELECT * FROM `reclame` WHERE `where` = 'd' && `page` = 'main'");
            while ($res = mysqli_fetch_assoc($query)){
                $title = explode('|',$res['title']);
                $c = array_rand($title);
                echo '<div class="reklama"><a href="'. $res['site'] .'">'. $title[$c] .'</a></div>';
            }
        }
        $r = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = 'd' && `page` = 'other'"));
        if ($r && $_SERVER['SCRIPT_NAME'] != "/index.php"){
            # на остальных страницах
            $query = quer("SELECT * FROM `reclame` WHERE `where` = 'd' && `page` = 'other'");
            while ($res = mysqli_fetch_assoc($query)){
                $title = explode('|',$res['title']);
                $c = array_rand($title);
                echo '<div class="reklama"><a href="'. $res['site'] .'">'. $title[$c] .'</a></div>';
            }
        }
        $r = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = 'd' && `page` = 'all'"));
        if ($r){
            # на всех страницах
            $query = quer("SELECT * FROM `reclame` WHERE `where` = 'd' && `page` = 'all'");
            while ($res = mysqli_fetch_assoc($query)){
                $title = explode('|',$res['title']);
                $c = array_rand($title);
                echo '<div class="reklama"><a href="'. $res['site'] .'">'. $title[$c] .'</a></div>';
            }
        }
    }

    echo '<div class="foot">';

    if ($_SERVER['SCRIPT_NAME'] != "/index.php") {
        echo '<img src="'. URL .'images/icons/home.png" alt="home"/><a href="http://' . $_SERVER['HTTP_HOST'] . '/?">На главную</a>&nbsp;';
    }
    echo '</div>';
    echo '</div><div id="end">';
    if ($aut > 0) {
        if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/styles/wap/' . $upst) && file_exists($_SERVER['DOCUMENT_ROOT'] .
            '/styles/wap/' . $upst . '/end.gif') && $upst != 'default') {
            echo '<img align="left" src="http://' . $_SERVER['HTTP_HOST'] . '/styles/wap/' .
                $upst . '/end.gif" alt="" />';
        }
    } else {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/styles/wap/default/end.gif')) {
            echo '<img align="left" src="http://' . $_SERVER['HTTP_HOST'] .
                '/styles/wap/default/end.gif" alt="" />';
        }
    }
    echo '<div style="padding:2px;"><a href="http://' . $_SERVER['HTTP_HOST'] .
        '/online.php">Online:&nbsp;' . $uonl;

    echo '/' . $allonl . '</a><br />
';
    if ($_SERVER['SCRIPT_NAME'] == "/index.php") {
        $all = mysqli_num_rows(quer("SELECT * FROM `users`"));
        $time = time();

        echo '</div></div>';
        if (isset($mbann) && !empty($mbann)) {
            echo '<div class="stat">' . $mbann . '</div>';
        }
    } else {
        echo '</div></div>';
        if (isset($obann) && !empty($obann)) {
            echo '<div class="stat">' . $obann . '</div>';
        }
    }
    echo '</div></body></html>';
}

//Новые сообщения
function mc()
{
    global $u_id, $sql;
    $mc = mysqli_num_rows(quer("SELECT * FROM `msg` WHERE `cid` = '" . $u_id .
        "' && `read` = '1'"));
    if ($mc > 0) {
        echo '<div class="title"><a href="http://' . $_SERVER['HTTP_HOST'] .
            '/upan/msg.php?act=contacts">Новое сообщение</a>[' . $mc . ']</div>';
    }
}

//Онлайн
function onlc($page)
{
    global $aut, $u_id;
    $on = quer("SELECT * FROM `online` WHERE `page` LIKE '%" . $page . "%'");

    if (mysqli_num_rows($on) > 0) {
        $o = '';
        $all = mysqli_num_rows($on);
        $i = 1;
        while ($onn = mysqli_fetch_assoc($on)) {
            if ($onn['u_id'] != '0') {
                $n = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '" . $onn['u_id'] .
                    "'"));
                if ($u_id == $onn['u_id'] or $aut == 0) {
                    $o .= $n['login'];
                } else {
                    $o .= '<a href="http://' . $_SERVER['HTTP_HOST'] . '/upan/info.php?id=' . $onn['u_id'] .
                        '">' . $n['login'] . '</a>';
                }
                if ($i < $all) {
                    $o .= ',';
                }
            } else {
                $o .= 'Гость';
                if ($i < $all) {
                    $o .= ',';
                }
            }
            $i++;
        }
    } else {
        $o .= 'Никого нет!';
    }
    return $o;
}

//Время
function get_time($time, $par)
{
    if ($par == 5) {
        $time = date("d.m.Y H:i", $time);
    } elseif ($par == 4) {
        $time = date("d.m.Y", $time);
    } elseif ($par == 3) {
        $time = date("d.m H:i", $time);
    } elseif ($par == 2) {
        $time = date("d.m", $time);
    } elseif ($par == 1) {
        $time = date("H:i:s", $time);
    }
    return $time;
}

//За последние 24 часа
function last_24($time)
{
    $time = $time - 86400;
    return $time;
}

//Подсветка
function highlight_code1($code)
{
    $code = str_ireplace("<br />", "", $code);
    $code = trim($code[1]);
    return $code;
}
//Подсветка
function highlight_code2($code)
{
    $code = str_ireplace("<br />", "", $code);
    $cod = highlight_string(html_entity_decode($code[0], ENT_QUOTES, 'UTF-8'), 1);
    $cod = '<div class="phpcode">' . $cod . '</div>';
    return $cod;
}



function del($text)
{
    $text = str_replace('&', '', $text);
    $text = str_replace('$', '', $text);
    $text = str_replace('>', '', $text);
    $text = str_replace('<', '', $text);
    $text = str_replace('~', '', $text);
    $text = str_replace('`', '', $text);
    $text = str_replace('#', '', $text);
    $text = str_replace('*', '', $text);
    return $text;
}

//Теги
function bb_code($text)
{
    $text = preg_replace('/\[b\](.+)\[\/b\]/sU', '<b>\1</b>', $text);
    $text = preg_replace('/\[i\](.+)\[\/i\]/sU', '<i>\1</i>', $text);
    $text = preg_replace('/\[u\](.+)\[\/u\]/sU', '<u>\1</u>', $text);
    $text = preg_replace('/\[s\](.+)\[\/s\]/sU', '<s>\1</s>', $text);
    $text = str_ireplace(array('[br]', '[br/]'), '<br />', $text);
    $text = preg_replace('/\[quote\](.+)\[\/quote\]/sU', '<div class="quote">\1</div>',
        $text);
    $text = preg_replace('/\[center\](.+)\[\/center\]/sU', '<center>\1</center>', $text);
    $text = preg_replace('/\[blue\](.+)\[\/blue\]/sU', '<font color="blue">\1</font>',
        $text);
    $text = preg_replace('/\[red\](.+)\[\/red\]/sU', '<font color="red">\1</font>',
        $text);
    $text = preg_replace('/\[url=(.+)\](.+)\[\/url\]/sU', '<a href="http://\1" target="_blank">\2</a>',
        $text);
    $text = preg_replace_callback('#\[code\](.+?)\[/code\]#sui', 'highlight_code1',
        $text);
    $text = preg_replace_callback('#&lt;\?(.+?)\?&gt;#sui', 'highlight_code2', $text);

    return $text;
}

//Удление тегов
function del_code($text)
{
    $text = str_ireplace(array('[center]', '[/center]', '[code]', '[/code]',
        '[url=]', '[/url]', '[s]', '[/s]', '[red]', '[/red]', '[u]', '[/u]', '[i]',
        '[/i]', '[b]', '[/b]', '[blue]', '[/blue]', '<br />'), '', $text);
    return $text;
}

//Функция запрета рекламы!
function antilink($var)
{
    ////////////////////////////////////////////////////////////
    // Маскировка ссылок в тексте                             //
    ////////////////////////////////////////////////////////////
    $var = preg_replace("#((https?|ftp)://)([[:alnum:]_=/-]+(\\.[[:alnum:]_=/-]+)*(/[[:alnum:]+&._=/~%]*(\\?[[:alnum:]?+&_=/;%]*)?)?)#iU",
        "[реклама]", $var);
    $var = strtr($var, array(".ru" => "***", ".com" => "***", ".net" => "***",
        ".org" => "***", ".info" => "***", ".mobi" => "***", ".wen" => "***", ".kmx" =>
        "***", ".h2m" => "***"));
    return $var;
}

//Месяца
function mesyac($mes)
{
    $m = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 =>
        'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 =>
        'октября', 11 => 'ноября', 12 => 'декабря');

    return $m[$mes];
}

function filtr($var){
    global $db;
    if (is_numeric($var)) return (int)$var;
    elseif (!is_array($var)) return trim(htmlspecialchars(mysqli_real_escape_string($db,$var)));
    else return $var;
}

//Смайлы
function smiles($string)
{
    $dir = opendir($_SERVER['DOCUMENT_ROOT'] . "/images/smiles");
    while ($file = readdir($dir)) {
        if (preg_match("/.gif$/i", "$file")) {
            $file2 = str_replace(".gif", "", $file);
            $string = str_replace(":$file2", '<img src="http://' . $_SERVER['HTTP_HOST'] .
                '/images/smiles/' . $file . '" alt=""/>', $string);
        }
    }
    closedir($dir);
    return $string;
}
//byvlad, дописано мной))
function countf($dir) {
static $count;
static $ncount;
foreach(glob($dir) as $obj) {
if(is_file($obj) and $obj != '.' and $obj != '..' and !stripos($obj,'_desc.txt')) {
    $time = filemtime($obj);
    if ($time >= last_24(time())){
        $ncount += 1;
    }
    $count += 1;
} else {
countf($obj . '/*');
}
}
return $count.($ncount > 0 ? '/<span style="color:blue">+'. $ncount .'</span>' : '');
}

function age($date)
 {
  $date=explode('.',$date);
  $m=date('m');
  $year=date('Y')-$date[2]-1;
  if ($m>$date[1])
   $year++;
  if ($m==$date[1])
   if (date('d')>=$date[0])
    $year++;
  return $year;
 }

 //by Tidus
 function old($papka,$times){
//var_dump($papka);
$old_time = time()-60*$times;
$dir = opendir ($papka);
while ($file = readdir ($dir)) {
if (( $file != ".") && ($file != ".."))
$files[]="$papka/$file";
$time[]=filemtime("$papka/$file" );
}
closedir ($dir);
if ($files != NULL){
$count_files = count($files);
for($i = 1; $i< $count_files; $i++){
if($time[$i] <= $old_time){
@unlink($files[$i]);
}
}
}
}

function rus_to_lat($var){
    $var = strtolower($var);
    $rus_big = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я');
    $rus_small = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я');
    $en = array('a','b','v','g','d','e','jo','zh','z','i','q','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','qw','yi','q','ye','yu','ya');

    $var = str_ireplace($rus_big,$en,$var);
    $var = str_ireplace($rus_small,$en,$var);

    return $var;
}

?>
