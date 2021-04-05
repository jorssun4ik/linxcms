<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит Форум';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0 && isset($_GET['act'],$_GET['id']) && !empty($_GET['act']) && !empty($_GET['id']) && is_numeric($_GET['id']))
{
    $act = htmlspecialchars($_GET['act']);
    $id = (int)$_GET['id'];
    if ($act == 'tedit' && $prava >= 6){
        $set['title'] = 'Переименование темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Изменение темы</div>';
        $query = quer("SELECT `title`,`l_id` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $t = mysqli_fetch_assoc($query);
            $l = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $t['l_id'] ."'"));
            if (isset($_POST['title']) && !empty($_POST['title'])){
                $title = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['title'])));
                $result = mysqli_num_rows(quer("SELECT `l_id` FROM `forum_t` WHERE `title` = '". $title ."' && `id` = '". $id ."'"));
                if (mb_strlen($title) > 40){
                    echo '<div class="c">Название темы не может превышать 40 символов!</div>';
                }
                elseif (mb_strlen($title) < 4){
                    echo '<div class="c">Название темы не может быть меньше 4 символов!</div>';
                }
                elseif ($result > 0){
                    echo '<div class="c">Название темы итак такое!</div>';
                }
                else{
                    quer("UPDATE `forum_t` SET `title` = '". $title ."' WHERE `id` = '". $id ."'");
                    echo '<div class="c">Название темы успешно измененно!</div>';
                }
            }   
            else{        
            echo '<div class="c">
            Создал тему: <b><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $t['l_id'] .'">'. $l['login'] .'</a></b>
            <br /><form action="posting.php?act=tedit&amp;id='. $id .'" method="post">
            Название темы:<br /><input name="title" value="'. $t['title'] .'"/>
            <br /><input type="submit" value="Изменить"/>
            </form></div>';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }   
        else{
            echo '<div class="с">Такой темы нет!</div>';
        }     
    }
    if ($act == 'tdel' && $prava >= 6){
        $set['title'] = 'Удаление темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Удаление темы</div>';
        $query = quer("SELECT `l_id` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            quer("DELETE FROM `forum_m` WHERE `tid` = '". $id ."'");
            quer("DELETE FROM `forum_t` WHERE `id` = '". $id ."'");
            echo '<div class="c">Тема успешно удалена!</div>';
        }   
        else{
            echo '<div class="с">Такой темы нет!</div>';
        } 
        echo '<div class="c"><a href="index.php">Форум</a></div>';    
    }
    elseif ($act == 'medit' && $prava >= 6 && isset($_GET['mid']) && !empty($_GET['mid']) && is_numeric($_GET['mid'])){
        $set['title'] = 'Изменение поста';
        head();
        echo '<div class="all"><div class="menu"><div class="text">';
        $query = quer("SELECT `spec` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $mid = (int)$_GET['mid'];
            $id = (int)$_GET['id']; //На всякий случай
            $res = quer("SELECT `l_id`,`msg`,`time` FROM `forum_m` WHERE `id` = '". $mid ."'");
            
            if (mysqli_num_rows($res) > 0){
                $r = mysqli_fetch_assoc($res);
                $l = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $r['l_id'] ."'"));
                if (isset($_POST['msg']) && !empty($_POST['msg'])){
                    $msg = trim(htmlspecialchars(stripslashes($_POST['msg'])));
                    
                    $result = mysqli_num_rows(quer("SELECT `l_id` FROM `forum_m` WHERE `msg` = '". $msg ."' && `tid` = '". $id ."'"));
                    
                    if (mb_strlen($msg) < 4){
                        echo '<div class="c">Сообщение не должно быть менее 4 символов!</div>';
                    }
                    elseif($result > 0){
                        echo '<div class="c">Такое сообщение уже есть в данной теме!</div>';
                    }
                    else{
                        quer("UPDATE `forum_m` SET `msg` = '". $msg ."' WHERE `id` = '". $mid ."'");
                        echo '<div class="c">Сообщение успешно изменено!</div>';
                    }
                }else{
                echo '<div class="c"><form action="?act=medit&amp;id='. $id .'&amp;mid='. $mid .'" method="post">
                <b><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $r['l_id'] .'">'. $l['login'] .'</a>
                </b>['. get_time($r['time'],3) .']<br />
                Сообщение:<br />
                <textarea name="msg" cols="20" rows="3">'. $r['msg'] .'</textarea><br />
                <input type="submit" value="Изменить"/>
                </form></div>';
                }
            }
            else{
                echo 'Такого сообщения нет, либо оно находится не в данной теме!';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }   
        else{
            echo '<div class="с">Такой темы нет!</div>';
        }     
    }
    elseif ($act == 'mdel' && $prava >= 6 && isset($_GET['mid']) && !empty($_GET['mid']) && is_numeric($_GET['mid'])){
        $set['title'] = 'Удаление поста';
        head();
        echo '<div class="all"><div class="menu"><div class="text">';
        $query = quer("SELECT `spec` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $mid = (int)$_GET['mid'];
            
            $res = quer("SELECT `l_id`,`msg`,`time` FROM `forum_m` WHERE `id` = '". $mid ."'");
            
            if (mysqli_num_rows($res) > 0){
                $count = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `tid` = '". $id ."'"));
                if ($count > 1){
                    quer("DELETE FROM `forum_m` WHERE `id` = '". $mid ."'");
                    echo '<div class="c">Сообщение успешно удаленно!</div>';
                }
                else{
                    echo '<div class="c">Удалять посты нельзя в теме, у которой всего один пост! Используйте удаление темы</div>';
                }
            }
            else{
                echo 'Такого сообщения нет, либо оно находится не в данной теме!';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }   
        else{
            echo '<div class="с">Такой темы нет!</div>';
        }     
    }
    elseif ($aut > 0 && $act == 'answer' && isset($_GET['uid']) && !empty($_GET['uid']) && is_numeric($_GET['uid'])){
        $set['title'] = 'Ответ';
        head();
        echo '<div class="all"><div class="menu"><div class="text">';
        $query = quer("SELECT `spec` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $uid = (int)$_GET['uid'];
            $res = quer("SELECT `login` FROM `users` WHERE `id` = '". $uid ."' LIMIT 1");            
            if (mysqli_num_rows($res) > 0){
                $us = mysqli_fetch_assoc($res);
                echo '<div class="c"><form action="viewtopic.php?type=theme&amp;id='.$id.'&amp;last" method="post">
                <textarea name="msg" cols="20" rows="3">'. $us['login'] .',</textarea>
                <br /><input type="submit" value="Ответить"/>
                </form></div>';
            }
            else{
                echo '<div class="c">Такого пользователя нет!</div>';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }   
        else{
            echo '<div class="с">Такой темы нет!</div>';
        } 
    }
    elseif ($aut > 0 && $act == 'quote' && isset($_GET['mid']) && !empty($_GET['mid']) && is_numeric($_GET['mid'])){
        $set['title'] = 'Цитировать';
        head();
        echo '<div class="all"><div class="menu"><div class="text">';
        $query = quer("SELECT `spec` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $mid = (int)$_GET['mid']; //ID сообщения
            $res = quer("SELECT `msg`,`l_id`,`time` FROM `forum_m` WHERE `id` = '". $mid ."' && `tid` = '". $id ."'");
                        
            if (mysqli_num_rows($res) > 0){
                $r = mysqli_fetch_assoc($res);
                $l = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $r['l_id'] ."'"));
                
                $msg = "[quote][b]".$l['login']."[/b][". get_time($r['time'],3) ."]\n{$r['msg']}[/quote]\n";
                
                echo '<div class="c"><form action="viewtopic.php?type=theme&amp;id='.$id.'&amp;last" method="post">
                <textarea name="msg" cols="20" rows="3">'. $msg .'</textarea>
                <br /><input type="submit" value="Ответить"/>
                </form></div>';
            }
            else{
                echo '<div class="c">Такого сообщения нет, либо данное сообщение не в этой теме!</div>';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }
        else{
            echo '<div class="с">Такой темы нет!</div>';
        }
    }
    elseif ($act == 'attach' && $prava >= 6){
        $set['title'] = 'Закрепление темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Закрепление темы</div>';
        $query = quer("SELECT `spec` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $t = mysqli_fetch_assoc($query);
            if ($t['spec'] == 1){
                echo '<div class="c">Тема итак закреплена!</div>';
            }
            else{
                quer("UPDATE `forum_t` SET `spec` = '1' WHERE `id` = '". $id ."'");
                echo '<div class="c">Тема успешно закреплена</div>';
            }           
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }
        else{
            echo '<div class="c">Такой темы нет!</div>';
        }
    }
    elseif ($act == 'detach' && $prava >= 6){
        $set['title'] = 'Открепление темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Открепление темы</div>';
        $query = quer("SELECT `spec` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $t = mysqli_fetch_assoc($query);
            if ($t['spec'] == 0){
                echo '<div class="c">Тема итак откреплена!</div>';
            }
            else{
                quer("UPDATE `forum_t` SET `spec` = '0' WHERE `id` = '". $id ."'");
                echo '<div class="c">Тема успешно откреплена!</div>';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }
        else{
            echo '<div class="c">Такой темы нет!</div>';
        }
    }
    elseif ($act == 'close' && $prava >= 6){
        $set['title'] = 'Закрытие темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Закрытие темы</div>';
        $query = quer("SELECT `close` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $t = mysqli_fetch_assoc($query);
            if ($t['close'] == 1){
                echo '<div class="c">Тема итак закрыта!</div>';
            }
            else{
                quer("UPDATE `forum_t` SET `close` = '1' WHERE `id` = '". $id ."'");
                echo '<div class="c">Тема успешно закрыта!</div>';
            }
            
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }
        else{
            echo '<div class="c">Такой темы нет!</div>';
        }
    }
    elseif ($act == 'open' && $prava >= 6){
        $set['title'] = 'Открытие темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Открытие темы</div>';
        $query = quer("SELECT `close` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        if (mysqli_num_rows($query) > 0){
            $t = mysqli_fetch_assoc($query);
            if ($t['close'] == 0){
                echo '<div class="c">Тема итак открыта!</div>';
            }
            else{
                quer("UPDATE `forum_t` SET `close` = '0' WHERE `id` = '". $id ."'");
                echo '<div class="c">Тема успешно открыта!</div>';
            }
            
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>';
        }
        else{
            echo '<div class="c">Такой темы нет!</div>';
        }
    }
    elseif ($act == 'move' && $prava >= 6){
        $set['title'] = 'Перемещение темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">';
        $query = quer("SELECT `rid` FROM `forum_t` WHERE `id` = '". $id ."'");
        
        $count = mysqli_num_rows(quer("SELECT `id` FROM `forum_r`"));
        if (mysqli_num_rows($query) > 0){
            if (isset($_POST['rid']) && !empty($_POST['rid'])){
                $rid = (int)$_POST['rid'];
                
                $res = mysqli_num_rows(quer("SELECT `id` FROM `forum_t` WHERE `id` = '". $id ."' && `rid` = '". $rid ."'"));
                
                if ($res > 0){
                    echo '<div class="c">Тема итак в данном разделе!</div>';
                }
                else
                {
                    quer("UPDATE `forum_t` SET `rid` = '". $rid ."' WHERE `id` = '". $id ."'");
                    quer("UPDATE `forum_m` SET `pid` = '". $rid ."' WHERE `tid` = '". $id ."'");
                    echo '<div class="c">Тема успешно перемешенна!</div>';
                }
            }
            elseif ($count <= 1){
                echo '<div class="c">Мало разделов в форуме для перемещения тем!</div>';
            }            
            else        
            {
            echo '<form action="posting.php?act=move&amp;id='. $id .'" method="post">';
            $r = mysqli_fetch_assoc($query);
            //Запросик)
            $rd = mysqli_fetch_assoc(quer("SELECT `pid` FROM `forum_r` WHERE `id` = '". $r['rid'] ."'"));
            
            $res = quer("SELECT `r`.`id` AS `rid`,`r`.`title` AS `rtitle`
            FROM `forum_r` AS `r`
            JOIN `forum_p` AS `p`
            ON `p`.`id` = '". $rd['pid'] ."' && `p`.`id` = `r`.`pid` 
            GROUP BY `r`.`id`");
            echo 'Раздел:<br />
            <select name="rid">';
            while ($rr = mysqli_fetch_assoc($res)) {
                if ($rr['rid'] != $r['rid']){
                echo '<option value="'. $rr['rid'] .'">'. $rr['rtitle'] .'</option>';
                }
            }
            echo '</select><br /><br />
            <input type="submit" value="Переместить"/></form>';
            }
            echo '<div class="c"><a href="viewtopic.php?type=theme&amp;id='. $id .'&amp;last">В тему</a></div>
            <div class="c"><a href="index.php">В Форум</a></div>';
        }
        else {
            echo '<div class="c">Такой темы нет!</div>';
        }
    }
    elseif ($act == 'topic' && $aut > 0)
    {
        $set['title'] = 'Создание темы';
        head();
        echo '<div class="all"><div class="menu"><div class="text">';
        $res = mysqli_num_rows(quer("SELECT `pid` FROM `forum_r` WHERE `id` = '". $id ."'"));
        if ($res > 0)
        {
        if (isset($_POST['title'],$_POST['msg']) && !empty($_POST['title']) && !empty($_POST['msg']))        
        {
            $title = htmlspecialchars(mysqli_escape_string($db,$_POST['title']));
            $title = trim($title);
            $msg = htmlspecialchars(mysqli_escape_string($db,$_POST['msg']));
            $msg = trim($msg);
            
            $res = mysqli_num_rows(quer("SELECT `id` FROM `forum_t` WHERE `title` = '". $title ."' && `rid` = '". $id ."'"));
                        
            if (mb_strlen($title) < 5 OR mb_strlen($title) > 50)
            {
                echo '<div class="c">Слишком много или мало символов в теме(min 5, max 50)</div>';
            }
            elseif (mb_strlen($msg) < 4)
            {
                echo '<div class="c">Слишком мало символов в сообщении(min 4)</div>';
            }
            elseif ($res > 0)
            {
                echo '<div class="c">Такая тема уже есть в данном разделе!</div>';
            }
            else
            {
                $time = time();
                quer("INSERT INTO `forum_t`(`rid`,`title`,`l_id`,`time`) VALUES('". $id ."','". $title ."','". $u_id ."','". $time ."')");
                $tid = mysqli_insert_id($db);
                quer("INSERT INTO `forum_m`(`pid`,`tid`,`msg`,`l_id`,`time`) VALUES('". $id ."','". $tid ."','". $msg ."','". $u_id ."','". $time ."')");
                echo '<div class="c">Тема успешно создана!</div>
                <div class="c"><a href="viewtopic.php?type=theme&amp;id='. $tid .'">В тему</a></div>';
            }
        }
        else
        {
        echo '<div class="c"><form action="?act=topic&amp;id='. $id .'" method="post">
        Название:<br />
        <input name="title"/><br />
        Сообщение:<br />
        <textarea name="msg" cols="20" rows="3"></textarea><br />
        <input type="submit" value="Создать тему"/>
        </form></div>
        <div class="c">
        <a href="http://'. $_SERVER['HTTP_HOST'] .'/faq/bb_code.html">BB-коды</a><br />
        <a href="http://'. $_SERVER['HTTP_HOST'] .'/faq/smiles.html">Смайлы</a>
        </div>';
        }
        echo '<div class="c"><a href="viewtopic.php?id='. $id .'">Раздел</a></div>';
        }
        else
        {
            echo '<div class="c">Такого раздела нет!</div>
            <div class="c"><a href="index.php">Форум</a></div>';
        }
    }
    echo '</div></div>';
    foot();
}
else
{
    Header("Location: index.php"); //Ошибочка :)
}
?>