<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в гостевой книге';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Гостевая книга';

$rand = rand(1000, 9999);
head();

if ($aut > 0) {
    mc();
}
echo '<div class="all"><div class="menu"><div class="text">';
echo '<div class="title">';
echo 'Гостевая книга
</div>';
if ($aut > 0 && $prava >= 8) {
    $del = $_GET["del"];
    if ($del) {
        echo '<span style="color:green">Пост удален!</span><br/>';
        $ts = quer("delete from `guest` where `id`=" . $del . "");
    }
}
if (isset($_GET['add'])) {
    //Обрабатываем сообщение
    $msg = mysqli_escape_string($db, htmlspecialchars($_POST['msg']));
    $tim = time();
    $tt = time() - 10;

    $t = mysqli_num_rows(quer("SELECT * FROM `guest` WHERE `time` > '" . $tt . "'"));


    if ($t > 0) {
        echo '<font color="red">Интервал между написанием сообщения 40 секунд!</font><br />';
    } elseif ($aut == 0) {
        $code = (int)$_POST['code'];
        $kod = (int)$_POST['kod'];
        if ($code != $kod) {
            echo '<font color="red">Неправильный код!</font><br />';
        } elseif (empty($msg)) {
            echo '<font color="red">Пустое сообщение!</font><br />';
        } else {
            $log = 'Гость';
            quer("INSERT INTO `guest` SET `login` = '" . $log . "', `msg` = '" . $msg .
                "', `browser` = '" . $browser . "', `ip` = '" . $ip . "', `time` = '" . $tim .
                "'");
            echo '<font color="green">Сообщение успешно добавлено!</font><br />';
        }
    } else {
        if (empty($msg)) {
            echo '<font color="red">Пустое сообщение!</font><br />';
        } else {
            quer("UPDATE `counter` SET `gb` = gb+1 WHERE `u_id` = '" . $id . "'");
            quer("INSERT INTO `guest` SET `login` = '" . $login . "', `msg` = '" . $msg .
                "', `browser` = '" . $browser . "', `ip` = '" . $ip . "', `time` = '" . $tim .
                "'");
            echo '<font color="green">Сообщение успешно добавлено!</font><br />';
        }
    }
}
echo '<a href="http://' . $_SERVER['HTTP_HOST'] . '/guest/?' . $rand .
    '">Обновить</a><br />';

if (isset($_GET['l'])) {
    $lname = stripslashes(htmlspecialchars(trim($_GET['l'])));
    $lname = $lname . ', ';
}
echo '<a href="http://' . $_SERVER['HTTP_HOST'] .
    '/faq/bb_code.html">BB-Коды</a>|<a href="http://' . $_SERVER['HTTP_HOST'] .
    '/faq/smiles.html">Смайлы</a><div class="c">
<form action="http://' . $_SERVER['HTTP_HOST'] . '/guest/add/" method="post">
Сообщение<br /><textarea name="msg" cols="15" rows="3">' . $lname .
    '</textarea><br />
';
if ($aut == 0) {
    $code = mt_rand(10000, 99999);
    echo 'Введите код: <font color="red"><b>' . $code .
        '</b></font><br /><input name="code"/><br/>
	<input type="hidden" name="kod" value="' . $code . '"/>
	';

}

echo '<input type="submit" value="Добавить"/></form>
</div><br /><br />';

$all = mysqli_num_rows(quer("SELECT `id` FROM `guest`"));
$query = quer("SELECT `id` FROM `guest`");
$result = mysqli_num_rows($query);
$pag = abs($_GET['p']);
if (empty($pag) && $pag == 0) {
    $start = 0;
} else {
    $p = $pag - 1;
    $start = $p * $num;
}
if ($result > 0) {
    $pr = mysqli_fetch_assoc(quer("SELECT `prava` FROM `users` WHERE `login` = '" .
        $login . "'"));
    $prava = strip_tags($pr['prava']);
    $quer = quer("SELECT * FROM `guest` ORDER BY `id` DESC LIMIT " . $start . "," .
        $num . "");
    while ($n = mysqli_fetch_assoc($quer)) {
        //$title = iconv('windows-1251','utf-8',$n['title']);
        //$msg = iconv('windows-1251','utf-8',$n['msg']);
        $msg = $n['msg'];
        $brouser = $n['browser'];
        $ip = $n['ip'];
        $id = $n['id'];
        $name = $n['login'];
        $time = $n['time'];
        $ll = mysqli_fetch_assoc(quer("SELECT `id`,`prava` FROM `users` WHERE login = '" .
            $name . "'"));
        $res = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `login` = '" . $name .
            "'"));
        $log_id = $ll['id'];
        $prrav = $ll['prava'];
        echo '<div class="zag">';
        if ($aut > 0) {
            $i = mysqli_fetch_assoc(quer("SELECT `sex` FROM `anketa` WHERE `u_id` = '" . $log_id .
                "'"));
            if ($i['sex'] == 'm') {
                echo '<img src="http://' . $_SERVER['HTTP_HOST'] .
                    '/images/icons/male.gif" title="Мужик я)))" alt="m"></img>';
            } elseif ($i['sex'] == 'f') {
                echo '<img src="http://' . $_SERVER['HTTP_HOST'] .
                    '/images/icons/female.gif" title="Красотка" alt="w"></img>';
            }
        }
        if ($log_id != null || $log_id == 0) {
            $uonl = mysqli_num_rows(quer("SELECT `id` FROM `online` WHERE `u_id` = '" . $log_id .
                "'"));
        } else {
            $uonl = 0;
        }
        if (!$p)
            $p = 0;
        if ($aut > 0 && $prava >= 8) {
            echo '<a href=\'http://' . $_SERVER['HTTP_HOST'] . '/guest/?p=' . $p .
                '&amp;del=' . $id . '\'>[X]</a> ';
        }
        if ($uonl > 0 && $name != 'Гость') {
            $status = '&nbsp;<font color="green">[онлайн]</font>';
        } elseif ($uonl == 0 && $name != 'Гость') {
            $status = '&nbsp;<font color="red">[оффлайн]</font>';
        } else {
            $status = '';
        }
        if ($login == $name || $res == 0) {
            echo $name . status($prrav) . $status . '[' . get_time($time, 5) . ']</div>';
        } elseif ($aut > 0) {
            echo '<a href="http://' . $_SERVER['HTTP_HOST'] . '/upan/info.php?id=' . $log_id .
                '">' . $name . '</a>' . $stat . $status . '<a href="http://' . $_SERVER['HTTP_HOST'] .
                '/guest/' . $name . '/">[отв]</a>[' . get_time($time, 5) . ']</div>';
        } else {
            echo $name . status($prrav) . $status . '[' . get_time($time, 5) . ']</div>';
        }
        echo '<div class="c">
' . antimat(nl2br(bb_code(smiles($msg)))) . '';
        if ($aut > 0 && $prava >= 8)
            echo '<br/><span style=\'color:green\'>Браузер</span>: ' . $brouser . '';
        if ($aut > 0 && $prava >= 8)
            echo '<br/><span style=\'color:green\'>Ip</span>: ' . $ip . '';
        echo '</div>';
    }
    nav($all, $num);
} else {
    echo '<div class="c">Сообщений нет, будь первым!</div>';
}
echo '<br /><div class="c">Просматривают гостевую: ' . onlc("gb.php") . '</div>';
echo '</div></div>';


foot();
?>