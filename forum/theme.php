<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    $th = mysqli_num_rows(quer("SELECT `rid` FROM `forum_t` WHERE `id` = '" . $id .
        "'")); //Проверяем или есть такая тема


    $s = mysqli_fetch_assoc(quer("SELECT `t`.`close` AS `tclose`,`t`.`spec` AS `tspec`,`t`.`title` AS `ttitle`,`r`.`id` AS `rid`,`r`.`title` AS `rtitle`,`p`.`id` AS `pid`,`p`.`title` AS `ptitle`
         FROM `forum_r` AS `r`
         JOIN `forum_p` AS `p`
         JOIN `forum_t` AS `t`
         WHERE `t`.`id` = '" . $id .
        "' && `t`.`rid` = `r`.`id` && `r`.`pid` = `p`.`id` LIMIT 1"));

    if ($th > 0 && isset($_GET['txt'])) {
        $r = quer("SELECT `m`.`msg` AS `mmsg`, `m`.`l_id` AS `ml_id`, `m`.`time` AS `mtime`, `u`.`login` AS `ulogin`, `u`.`prava` AS `uprava`
FROM `forum_m` AS `m`
JOIN `users` AS `u`
ON `m`.`tid` = '{$id}' && `u`.`id` = `m`.`l_id` ORDER BY `m`.`time` ASC");
        $d = "-=".$s['ttitle'] . "=-\r\n"; //Инициализация
        while ($m = mysqli_fetch_assoc($r)) {
            $d .= $m['ulogin'] . ftime($m['mtime']) . "\n
            " . del_code($m['mmsg']) . "\r\n";
        }

        $fp = fopen("temp/". $s['ttitle'] .".txt", "a+");
        flock($fp, LOCK_EX);
        fputs($fp, "$d\r\n");
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        $file = "temp/{$s['ttitle']}.txt";

        header("Content-Type: text/plain");
        header("Accept-Ranges: bytes");
        header("Content-Length: " . filesize($file));
        header("Content-Disposition: attachment; filename=" . $file);
        readfile($file);
        exit();
    }

    $set['title'] = 'Форум::' . $s['ptitle'] . '::' . $s['rtitle'] . '::' . $s['ttitle'];
    head();
    if ($th > 0) {
        echo '<div class="all"><div class="menu"><div class="text">
    <div class="title"><a href="index.php">Форум</a>::<a href="viewtopic.php?id=' .
            $s['rid'] . '">' . $s['rtitle'] . '</a>::';
    }
    if ($s['tspec'] == 1) {
        echo '!';
    }
    if ($s['tclose'] == 1) {
        echo '#';
    }
    echo $s['ttitle'] . '</div>';
    if ($th > 0) {
        if (isset($_POST['msg']) && !empty($_POST['msg']) && $aut > 0) {
            $msg = htmlspecialchars(mysqli_escape_string($db, $_POST['msg']));
            $msg = trim($msg);

            $res = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `msg` = '" . $msg .
                "' && `tid` = '" . $id . "'"));

            if (strlen($msg) < 3) {
                echo '<div class="c">Сообщение не добавленно! Слишком мало символов!</div>';
            } elseif ($res > 0) {
                echo '<div class="c">Сообщение не добавленно! Такое сообщение уже есть в данной теме!</div>';
            } elseif ($s['tclose'] == 1 && $prava < 8) {
                echo '<div class="c">Тема закрыта! В ней писать нельзя!</div>';
            } else {
                $time = time();
                quer("INSERT INTO `forum_m`(`pid`,`tid`,`l_id`,`msg`,`time`) VALUES('" . $s['rid'] .
                    "','" . $id . "','" . $u_id . "','" . $msg . "','" . $time . "')");
                quer("UPDATE `forum_t` SET `time` = '" . $time . "' WHERE `id` = '" . $id . "'");
                echo '<div class="c">Сообщение успешно добавленно!</div>';
            }
        }
        $all = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `tid` = '" . $id .
            "'"));
        if ($all > 0) {
            $pag = abs($_GET['p']);
            if (isset($_GET['last'])) {
                $pag = ceil($all / $num);
            }
            if (empty($pag) or $pag == 0) {
                $start = 0;
            } else {
                $p = $pag - 1;
                $start = $p * $num;
            }

            $m = quer("SELECT `u`.`id` AS `uid`,`u`.`prava` AS `uprava`,`u`.`login` AS `ulog`,`m`.`id` AS `mid`,`m`.`msg` AS `post`,`m`.`time` AS `mtime` 
        FROM `forum_m` AS `m`
        JOIN `users` AS `u`
        WHERE `m`.`tid` = '" . $id .
                "' && `m`.`l_id` = `u`.`id` ORDER BY `m`.`time` ASC LIMIT " . $start . "," . $num .
                "");

            $i = $start + 1;

            while ($mm = mysqli_fetch_assoc($m)) {
                echo '<div class="zag"><b>' . $i . '.</b>';
                if ($aut > 0) {
                    echo '<a href="http://' . $_SERVER['HTTP_HOST'] . '/upan/info.php?id=' . $mm['uid'] .
                        '">' . $mm['ulog'] . '</a>';
                } else {
                    echo $mm['ulog'];
                }
                echo status($mm['uprava']) . online($mm['uid']) . ftime($mm['mtime']);
                if ($prava >= 6) {
                    echo '&nbsp;<a href="posting.php?act=medit&amp;id=' . $id . '&amp;mid=' . $mm['mid'] .
                        '"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/pencil.png" alt = "pencil"/></a> <a href="posting.php?act=mdel&amp;id=' . $id . '&amp;mid=' . $mm['mid'] .
                        '"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/cross.png" alt = "del"/></a>';
                }
                echo '</div>';
                if ($mm['uid'] != $u_id && $aut > 0) {
                    echo '<div class="c"><a href="posting.php?act=answer&amp;id=' . $id .
                        '&amp;uid=' . $mm['uid'] .
                        '">ответить</a>|<a href="posting.php?act=quote&amp;id=' . $id . '&amp;mid=' . $mm['mid'] .
                        '">цитировать</a></div>';
                }
                echo '<div class="c">' . antimat(smiles(bb_code(nl2br(links($mm['post']))))) .
                    '</div>';
                $i++;
            }
            nav($all, $num, 'type=theme&amp;id=' . $id);
        } else {
            echo '<div class="c">В теме нет сообщений!</div>';
        }
        if ($aut > 0 && $s['tclose'] == 0 or $aut > 0 && $prava >= 8) {
            echo '<div class="c">Сообщение:<br /><form action="?type=theme&amp;id=' . $id .
                '&amp;last" method="post">
    <textarea name="msg" cols="20" rows="3"></textarea>
    <br /><input type="submit" value="Написать"/>
    </form>
    </div>
    <div class="c"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/save.png" alt = "save"/><a href="viewtopic.php?type=theme&amp;id=' . $id .
                '&amp;txt">Скачать тему в TXT</a></div>';
        }
        if ($aut == 0 && $s['tclose'] == 0) {
            echo '<div class="c">Для добавления сообщения вам надо авторизоваться!</div>';
        }
        if ($aut > 0 && $s['tclose'] == 1) {
            echo '<div class="c"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/close.gif" alt = "closed"/><font color="red">Тема закрыта!</font></div>';
        }
        if ($s['tspec'] == 1) {
            echo '<div class="c"><img src = "http://'. $_SERVER['HTTP_HOST'].'/images/icons/prik.gif" alt = "prik"/><font color="red">Тема закреплена!</font></div>';
        }
        if ($prava >= 6) {
            echo '----<br />
        <div class="c"><a href="posting.php?act=move&amp;id=' . $id .
                '">Переместить тему</a></div>';
            if ($s['tspec'] == 1) {
                echo '<div class="c"><a href="posting.php?act=detach&amp;id=' . $id .
                    '">Открепить тему</a></div>';
            } else {
                echo '<div class="c"><a href="posting.php?act=attach&amp;id=' . $id .
                    '">Закрепить тему</a></div>';
            }
            if ($s['tclose'] == 1) {
                echo '<div class="c"><a href="posting.php?act=open&amp;id=' . $id .
                    '">Открыть тему</a></div>';
            } else {
                echo '<div class="c"><a href="posting.php?act=close&amp;id=' . $id .
                    '">Закрыть тему</a></div>';
            }
            echo '<div class="c"><a href="posting.php?act=tedit&amp;id=' . $id .
                '">Переименовать тему</a></div>
        <div class="c"><a href="posting.php?act=tdel&amp;id=' . $id .
                '">Удалить тему</a></div>';
        }
        echo '<div class="title"><a href="index.php">Форум</a>::<a href="viewtopic.php?id=' .
            $s['rid'] . '">' . $s['rtitle'] . '</a>::' . $s['ttitle'] . '</div>';
    } else {
        echo '<div class="c">Такой темы не существует!</div>';
    }
    echo '</div></div>';
    foot();
} else {
    $set['title'] = 'Форум::Ошибка';
    head();
    echo '<div class="all"><div class="menu"><div class="text">
    <div class="c">Такого ID темы нет!</div>
    </div></div>';
    foot();
}
?>