<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит Форум';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Форум::Поиск';
head();

if ($aut > 0) {
    mc();
}
echo '<div class="all"><div class="menu"><div class="text">
<div class="zag"><a href="index.php">Форум</a>::Поиск</div>';
switch ($act = isset($_GET['act']) && !empty($_GET['act']) ? $act =
    htmlspecialchars($_GET['act']) : '') {
        //Результаты
    case 'result':
        if (isset($_GET['find'], $_GET['search_in']) && !empty($_GET['find'])) {
            $find = str_replace('*', '%', mysqli_real_escape_string($db, htmlspecialchars($_GET['find'])));
            $sin = htmlspecialchars($_GET['search_in']);

            if ($sin == 'theme') {
                $mQuery = quer("SELECT `id` FROM `forum_t` WHERE `title` LIKE '%{$find}%'");
            } else {
                $mQuery = quer("SELECT `id` FROM `forum_m` WHERE `msg` LIKE '%{$find}%'");
            }

            $res = mysqli_num_rows($mQuery);

            if ($res > 0) {
                $pag = abs($_GET['p']);

                if (empty($pag) or $pag == 0) {
                    $start = 0;
                } else {
                    $p = $pag - 1;
                    $start = $p * $num;
                }
                if ($sin == 'theme') {
                    $t = quer("SELECT `t`.`spec` AS `tspec`,`t`.`close` AS `tclose`,`t`.`id` AS `tid`, `t`.`title` AS `ttitle`, `t`.`time` AS `ttime`, COUNT(`m`.`id`) AS `mcount`, 
                   `u`.`id` AS `uid`,`u`.`login` AS `ulog`,`u`.`prava` AS `uprava`,`m`.`time` AS `mtime`
                   FROM `forum_t` AS `t`
                   JOIN `forum_m` AS `m`
                   JOIN `users` AS `u` 
                   WHERE `t`.`title` LIKE '%{$find}%' && `t`.`id` = `m`.`tid` && `t`.`l_id` = `u`.`id` GROUP BY `t`.`id` ORDER BY `t`.`spec` DESC, `t`.`time` DESC LIMIT {$start},{$num}");

                    while ($th = mysqli_fetch_assoc($t)) {
                        $l = mysqli_fetch_assoc(quer("
            SELECT `m`.`time` AS `mtime`,`u`.`id` AS `uid`,`u`.`prava` AS `uprava`,`u`.`login` AS `ulog`
            FROM `forum_m` AS `m`
            JOIN `users` AS `u`
            WHERE `m`.`tid` = '" . $th['tid'] . "' && `m`.`l_id` = `u`.`id`
            ORDER BY `m`.`time` DESC LIMIT 1"));
                        echo '<div class="zag">';
                        if ($th['tspec'] == 1) {
                            echo '!';
                        }
                        if ($th['tclose'] == 1) {
                            echo '#';
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
                } else {
                    $t = quer("SELECT `t`.`spec` AS `tspec`,`t`.`close` AS `tclose`,`t`.`id` AS `tid`, `t`.`title` AS `ttitle`, `t`.`time` AS `ttime`,`u`.`id` AS `uid`,`u`.`login` AS `ulog`,`u`.`prava` AS `uprava`,`m`.`time` AS `mtime`
                   FROM `forum_t` AS `t`
                   JOIN `forum_m` AS `m`
                   JOIN `users` AS `u` 
                   WHERE `m`.`msg` LIKE '%{$find}%' && `t`.`id` = `m`.`tid` && `t`.`l_id` = `u`.`id` GROUP BY `t`.`id` ORDER BY `t`.`spec` DESC, `t`.`time` DESC");

                    while ($th = mysqli_fetch_assoc($t)) {
                        $l = mysqli_fetch_assoc(quer("
            SELECT `m`.`time` AS `mtime`,`u`.`id` AS `uid`,`u`.`prava` AS `uprava`,`u`.`login` AS `ulog`
            FROM `forum_m` AS `m`
            JOIN `users` AS `u`
            WHERE `m`.`tid` = '" . $th['tid'] . "' && `m`.`l_id` = `u`.`id`
            ORDER BY `m`.`time` DESC LIMIT 1"));
                        echo '<div class="zag">';
                        if ($th['tspec'] == 1) {
                            echo '!';
                        }
                        if ($th['tclose'] == 1) {
                            echo '#';
                        }
                        $tc = mysqli_fetch_assoc(quer("SELECT COUNT(`id`) AS `count` FROM `forum_m` WHERE `tid` = '" .
                            $th['tid'] . "'"));
                        echo '<a href="viewtopic.php?type=theme&amp;id=' . $th['tid'] . '">' . $th['ttitle'] .
                            '</a>';
                        if ($tc['count'] > $num) {
                            echo '[<a href="viewtopic.php?type=theme&amp;id=' . $th['tid'] . '&amp;last">' .
                                $tc['count'] . '</a>]';
                        } else {
                            echo '[' . $tc['count'] . ']';
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
                }
                nav($all, $num, "act=result&amp;find={$find}&amp;search_in={$sin}&amp;search_source={$source}");
                echo '<div class="c">Найдено: ' . $res . '</div>';
            } else {
                echo '<div class="c">Ничего не найдено!</div>
        <div class="c"><a href="search.php">Назад</a></div>';
            }
        } else {
            echo '<div class="c">Ничего не выбрано!</div>
    <div class="c"><a href="search.php">Назад</a></div>';
        }
        break;
        //Начальная страница поиска
    default:
        echo '
<div class="c">
<form action="search.php" method="GET">
<input type="hidden" name="act" value="result">
Что ищем:<br />
<small>В качестве шаблона используйте *</small><br />
<input name="find"/><br />
Поиск в:<br />
<input type="radio" name="search_in" value="theme" checked="checked"/> - Темах<br />
<input type="radio" name="search_in" value="post"/> - Постах<br /><br />
<input type="submit" value="Поиск"/>
</form>
</div>';
}
echo '</div></div>';
foot();
?>