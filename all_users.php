<?php

  /*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в списке пользователей';

 
require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Все пользователи';

head();

if ($aut > 0) {
    mc();
}

echo '<div class="all"><div class="menu"><div class="text"><div class="title">';
echo 'Все Пользователи
</div>';
$all = mysqli_num_rows(quer("SELECT `id` FROM `users`"));

$num = 10;
$pag = abs($_GET['p']);
if (empty($pag) or $pag == 0) {
    $start = 0;
} else {
    $p = $pag - 1;
    $start = $p * $num;
}

$query = quer("SELECT * FROM `users` ORDER BY `last_time` DESC LIMIT " . $start .
    "," . $num . "");

if (mysqli_num_rows($query) > 0) {
    while ($o = mysqli_fetch_assoc($query)) {
        $name = $o['login'];
        $id = strip_tags($o['id']);

        //Онлайн пользователь или нет
        $uonl = mysqli_num_rows(quer("SELECT `id` FROM `online` WHERE `u_id` = '" . $id .
            "'"));
        if ($aut > 0 && $login == $name) {
            echo '<div class="c"><a href="http://' . $_SERVER['HTTP_HOST'] .
                '/upan/anketa.php">' . $name . '</a>' . status($o['prava']);
        } elseif ($aut > 0) {
            echo '<div class="c"><a href="http://' . $_SERVER['HTTP_HOST'] .
                '/upan/info.php?id=' . $id . '">' . $name . '</a>' . status($o['prava']);
        } else {
            echo '<div class="c">' . $name . status($o['prava']);
        }
        if ($uonl > 0) {
            echo '&nbsp;<font color="green">[on]</font>';
        } else {
            echo '&nbsp;<font color="red">[off]</font>&nbsp;' . away($o['last_time']);
        }
        echo '</div>';
    }
    nav($all, $num);
} else {
    echo 'Пользователей в базе сайта нет!<br />';
}
echo '</div></div>';
foot();

?>