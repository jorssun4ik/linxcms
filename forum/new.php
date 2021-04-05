<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит Форум';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if (isset($_REQUEST['act']) && !empty($_REQUEST['act'])){

$act = htmlspecialchars(stripslashes(trim($_REQUEST['act'])));

if ($act == 'npost'){
$set['title'] = 'Форум::Новые посты';
head();

if ($aut > 0)
{
mc();
}

echo '<div class="all"><div class="menu"><div class="text">';
echo '<div class="title"><a href="index.php">Форум</a>::Новые посты</div>';

    $pag = abs($_GET['p']);
    if (empty($pag) && $pag == 0)
    {
	     $start = 0;
    } else
    {
	     $p = $pag - 1;
	     $start = $p*$num;
    }
    $all = mysqli_num_rows(quer("SELECT `id` FROM `forum_t`"));
         $t = quer("SELECT `t`.`spec` AS `tspec`,`t`.`close` AS `tclose`,`t`.`id` AS `tid`, `t`.`title` AS `ttitle`, `t`.`time` AS `ttime`, COUNT(`m`.`id`) AS `mcount`, 
                   `u`.`id` AS `uid`,`u`.`login` AS `ulog`,`u`.`prava` AS `uprava`,`m`.`pid` AS `mpid`,`m`.`time` AS `mtime`
                   FROM `forum_t` AS `t`
                   JOIN `forum_m` AS `m`
                   JOIN `users` AS `u` 
                   WHERE `t`.`id` = `m`.`tid` && `t`.`l_id` = `u`.`id` GROUP BY `t`.`id` ORDER BY `t`.`time` DESC LIMIT ". $start .",". $num ."");
    if ($all > 0){    
    while ($th = mysqli_fetch_assoc($t)){
        $l = mysqli_fetch_assoc(quer("
            SELECT `m`.`time` AS `mtime`,`u`.`id` AS `uid`,`u`.`prava` AS `uprava`,`u`.`login` AS `ulog`
            FROM `forum_m` AS `m`
            JOIN `users` AS `u`
            WHERE `m`.`tid` = '". $th['tid'] ."' && `m`.`l_id` = `u`.`id`
            ORDER BY `m`.`time` DESC LIMIT 1"));
        $p = mysqli_fetch_assoc(quer("SELECT `title` FROM `forum_r` WHERE `id` = '". $th['mpid'] ."'"));    
            echo '<div class="c"><a href="viewtopic.php?id='. $th['mpid'] .'">'. $p['title'] .'</a>::';
            if ($th['tspec'] == 1)
            {
                echo '!';
            }
            if ($th['tclose'] == 1)
            {
                echo '#';
            }
            
            echo '<a href="viewtopic.php?type=theme&amp;id='. $th['tid'] .'">'. $th['ttitle'] .'</a>';
            if ($th['mcount'] > $num) {
                echo '[<a href="viewtopic.php?type=theme&amp;id='. $th['tid'] .'&amp;last">'. $th['mcount'] .'</a>]';
            }
            else{
                echo '['. $th['mcount'] .']';
            }
            echo '(';
            if ($aut > 0) {
               echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $th['uid'] .'">'.$th['ulog'].'</a>/<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $l['uid'] .'">'. $l['ulog'] .'</a>';
            } 
            else {
            echo $th['ulog'].'/'.$l['ulog']; 
            } 
            echo ')'.ftime($l['mtime']).'</div>';
        }
        nav($all,$num,"act=npost");
}
else{
    echo '<div class="c">Ничего нового:)</div>';
}
echo '<div class="title"><a href="index.php">Форум</a>::Новые посты</div>';
}
elseif ($act = 'ntopic'){
    $set['title'] = 'Форум::Новые темы';
head();

if ($aut > 0)
{
mc();
}

echo '<div class="all"><div class="menu"><div class="text">';
echo '<div class="title"><a href="index.php">Форум</a>::Новые темы</div>';

    $pag = (int)$_GET['p'];
    if (empty($pag) && $pag == 0)
    {
	     $start = 0;
    } else
    {
	     $p = $pag - 1;
	     $start = $p*$num;
    }
    $all = mysqli_num_rows(quer("SELECT `id` FROM `forum_t`"));
             $t = quer("SELECT `t`.`spec` AS `tspec`,`t`.`close` AS `tclose`,`t`.`id` AS `tid`, `t`.`title` AS `ttitle`, `t`.`time` AS `ttime`, COUNT(`m`.`id`) AS `mcount`, 
                   `u`.`id` AS `uid`,`u`.`login` AS `ulog`,`u`.`prava` AS `uprava`,`m`.`pid` AS `mpid`,`m`.`time` AS `mtime`
                   FROM `forum_t` AS `t`
                   JOIN `forum_m` AS `m`
                   JOIN `users` AS `u` 
                   WHERE `t`.`id` = `m`.`tid` && `t`.`l_id` = `u`.`id` GROUP BY `t`.`id` ORDER BY `m`.`time` DESC LIMIT ". $start .",". $num ."");
    
    if ($all > 0){    
    while ($th = mysqli_fetch_assoc($t)){
        $l = mysqli_fetch_assoc(quer("
            SELECT `m`.`time` AS `mtime`,`u`.`id` AS `uid`,`u`.`prava` AS `uprava`,`u`.`login` AS `ulog`
            FROM `forum_m` AS `m`
            JOIN `users` AS `u`
            WHERE `m`.`tid` = '". $th['tid'] ."' && `m`.`l_id` = `u`.`id`
            ORDER BY `m`.`time` DESC LIMIT 1"));
        $p = mysqli_fetch_assoc(quer("SELECT `title` FROM `forum_r` WHERE `id` = '". $th['mpid'] ."'"));    
            echo '<div class="c"><a href="viewtopic.php?id='. $th['mpid'] .'">'. $p['title'] .'</a>::';
            if ($th['tspec'] == 1)
            {
                echo '!';
            }
            if ($th['tclose'] == 1)
            {
                echo '#';
            }
            
            echo '<a href="viewtopic.php?type=theme&amp;id='. $th['tid'] .'">'. $th['ttitle'] .'</a>';
            if ($th['mcount'] > $num) {
                echo '[<a href="viewtopic.php?type=theme&amp;id='. $th['tid'] .'&amp;last">'. $th['mcount'] .'</a>]';
            }
            else{
                echo '['. $th['mcount'] .']';
            }
            echo '(';
            if ($aut > 0) {
               echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $th['uid'] .'">'.$th['ulog'].'</a>/<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $l['uid'] .'">'. $l['ulog'] .'</a>';
            } 
            else {
            echo $th['ulog'].'/'.$l['ulog']; 
            } 
            echo ')'.ftime($l['mtime']).'</div>';
        }
        nav($all,$num,"act=ntopic");
}
else{
    echo '<div class="c">Ничего нового:)</div>';
}
echo '<div class="title"><a href="index.php">Форум</a>::Новые темы</div>';

}
echo '</div></div>';
foot();
}
else{
    Header("Location: ../index.php?err"); //Ошибка
    exit();
}
?>