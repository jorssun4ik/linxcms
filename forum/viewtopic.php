<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит Форум';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = htmlspecialchars(stripslashes($_GET['type']));
    if ($type = 'theme') {
        require 'theme.php';
    } else {
        Header("Location:index.php");
        exit();
    }
} else {
    $set['title'] = 'Форум';
    head();

    if ($aut > 0) {
        mc();
    }
    
    echo '<div class="all"><div class="menu"><div class="text">';
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        $rid = (int)$_GET['id'];
        $ac = mysqli_num_rows(quer("SELECT `id` FROM `forum_r` WHERE `id` = '" . $rid .
            "'"));
        if ($ac > 0) {

            $r = mysqli_fetch_assoc(quer("SELECT `title` FROM `forum_r` WHERE `id` = '" . $rid .
                "'"));

            echo '<div class="title"><a href="index.php">Форум</a>::' . $r['title'] .
                '</div>';

            $all = mysqli_num_rows(quer("SELECT `id` FROM `forum_t` WHERE `rid` = '" . $rid .
                "'"));

            $pag = intval($_GET['p']);
            if (empty($pag) && $pag == 0) {
                $start = 0;
            } else {
                $p = $pag - 1;
                $start = $p * $num;
            }

            if ($all > 0) {
                $t = quer("SELECT `t`.`spec` AS `tspec`,`t`.`close` AS `tclose`,`t`.`id` AS `tid`, `t`.`title` AS `ttitle`, `t`.`time` AS `ttime`, COUNT(`m`.`id`) AS `mcount`, 
                   `u`.`id` AS `uid`,`u`.`login` AS `ulog`,`u`.`prava` AS `uprava`,`m`.`time` AS `mtime`
                   FROM `forum_t` AS `t`
                   JOIN `forum_m` AS `m`
                   JOIN `users` AS `u` 
                   WHERE `t`.`rid` = '" . $rid .
                    "' && `t`.`id` = `m`.`tid` && `t`.`l_id` = `u`.`id` GROUP BY `t`.`id` ORDER BY `t`.`spec` DESC, `t`.`time` DESC");

                while ($th = mysqli_fetch_assoc($t)) {
                    $l = mysqli_fetch_assoc(quer("
            SELECT `m`.`time` AS `mtime`,`u`.`id` AS `uid`,`u`.`prava` AS `uprava`,`u`.`login` AS `ulog`
            FROM `forum_m` AS `m`
            JOIN `users` AS `u`
            WHERE `m`.`tid` = '" . $th['tid'] . "' && `m`.`l_id` = `u`.`id`
            ORDER BY `m`.`time` DESC LIMIT 1"));
                    echo '<div class="zag">';
                    if ($th['tspec'] == 1) {
                        echo '<img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/prik.gif" alt = "prik"/>';
                    }
                    if ($th['tclose'] == 1) {
                        echo '<img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/close.gif" alt = "closed"/>';
                    }

                    echo '<a href="viewtopic.php?type=theme&amp;id=' . $th['tid'] . '">' . $th['ttitle'] .
                        '</a>';
                    if ($th['mcount'] > $num) {
                        echo '[<a href="viewtopic.php?type=theme&amp;id=' . $th['tid'] . '&amp;last">' .
                            $th['mcount'] . '</a>]';
                    } else {
                        echo '[' . $th['mcount'] . ']';
                    }
                    echo '</div><div class="c"><small>';
                    if ($aut > 0) {
                        echo '<a href="http://' . $_SERVER['HTTP_HOST'] . '/upan/info.php?id=' . $th['uid'] .
                            '">' . $th['ulog'] . '</a>/<a href="http://' . $_SERVER['HTTP_HOST'] .
                            '/upan/info.php?id=' . $l['uid'] . '">' . $l['ulog'] . '</a>';
                    } else {
                        echo $th['ulog'] . '/' . $l['ulog'];
                    }

                    echo '</small></div>';
                }
                nav($all, $num, "id=" . $rid);
            } else {
                echo '<div class="c">В данном разеделе еще нет тем!</div>
        <div class="c"><a href="index.php">Форум</a></div>';
            }
            if ($aut > 0) {
                echo '<div class="c"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/add.png" alt = "add"/><a href="posting.php?act=topic&amp;id=' . $rid .
                    '">Создать Тему</a></div>';
            }
            echo '<div class="title"><a href="index.php">Форум</a>::' . $r['title'] .
                '</div>';
        } else {
            echo '<div class="c">Такого раздела нет на форуме!</div>
        <div class="c"><a href="index.php">Форум</a></div>';
        }
    } else {
        echo '<div class="c">Ошибка ID Раздела</div>
    <div class="c"><a href="index.php">Форум</a></div>';
    }
    echo '</div></div>';
    foot();
}
?>