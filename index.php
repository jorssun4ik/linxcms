<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */
$set['where'] = 'на главной';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Главная';

head();

echo '<div class="all"><div class="menu"><div class="text">';
if ($aut > 0)
{
mc();
}
if (isset($site) && $site == 'off' && $prava >= 7)
{
    echo '<div class="title"><font color="red"><b>Сайт закрыт!!!</b></font></div>';
}

if (isset($_GET['err']))
{
echo '<div class="title"><font color="red">Ошибка!!! Такой страницы не существует</font></div>';
}
//новости
$nc = mysqli_num_rows(quer("SELECT `id` FROM `news`"));

//Гостевая
$gc = mysqli_num_rows(quer("SELECT `id` FROM `guest`"));
$gcn = mysqli_num_rows(quer("SELECT `id` FROM `guest` WHERE `time` >= '". last_24(time()) ."'"));
if ($gcn > 0)
{
    $gcn = '<font color="red">+'. $gcn .'</font>';
}
else
{
    $gcn = '';
}


//Форум
$ft = mysqli_num_rows(quer("SELECT `id` FROM `forum_t`"));
$fp = mysqli_num_rows(quer("SELECT `id` FROM `forum_m`"));
$fpn = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `time` >= '". last_24(time()) ."'"));
if ($fpn > 0)
{
    $fpn = '<font color="red"><a href="forum/new.php?act=npost">+'. $fpn .'</a></font>';
}
else
{
    $fpn = '';
}

//Библиотека
$lc = mysqli_num_rows(quer("SELECT `id` FROM `library_cat`"));
$la = mysqli_num_rows(quer("SELECT `id` FROM `library_art`"));
$lan = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `time` >= '". last_24(time()) ."'"));
if ($lan > 0)
{
    $lan = '<font color="red">+'. $lan .'</font>';
}
else
{
    $lan = '';
}

//Время последней новости
if ($nc > 0)
{
    $ntim = mysqli_fetch_assoc(quer("SELECT `time` FROM `news` ORDER BY `id` DESC LIMIT 1"));
    $ntime = ftime($ntim['time'],false);
    
    
}
else
{
    $ntime = '';
}
$ncl = mysqli_num_rows(quer("SELECT `id` FROM `news` WHERE `time` >= '". last_24(time()) ."'"));

if ($ncl > 0){
    $nw = quer("SELECT `title`,`msg`,`time` FROM `news` WHERE `time` >= '". last_24(time()) ."' ORDER BY `id` DESC");
    while ($nww = mysqli_fetch_assoc($nw)){
        echo '<div class="title"><img src="'. URL .'images/icons/rss.png" alt="rss"/>'. $nww['title'] . ftime($nww['time']) .'</div>
        <div class="c">'. nl2br(bb_code($nww['msg'])) .'</div>';
    }
    
    echo '<div class="c"><img src="'. URL .'images/icons/rss.png" alt="news"/><a href="news/">Архив новостей</a>['. $nc .']</div>';
    echo '<div class="title">Информация</div>';
}
else{
echo '<div class="title">Информация</div>';    
echo '<div class="c"><img src="'. URL .'images/icons/rss.png" alt="news"/><a href="news/">Новости</a>'. $ntime .'</div>';
}

$all = mysqli_num_rows(quer("SELECT * FROM `users`"));
$day = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `time` >= '" .
                last_24($time) . "'"));

//<img src="'. URL .'images/icons/business.gif" alt="img"/>             
echo '<div class="c"><img src="'. URL .'images/icons/help.png" alt="info"/><a href="faq/">Информация по сайту</a></div>
<div class="c"><img src="'. URL .'images/icons/friends.gif" alt="users"/><a href="all_users.php">Пользователи</a>['. $all . ($day > 0 ? '/<font color="green">+' . $day . '</font>' : '') .']</div>
<div class="title">Общение</div>
<div class="c"><img src="'. URL .'images/icons/chat.png" alt="chat"/><a href="guest/">Гостевая книга</a>['. $gc .']'. $gcn .'</div>
<div class="c"><img src="'. URL .'images/icons/for.png" alt="forum"/><a href="forum/">Форум</a>['. $ft .'/'. $fp .']'. $fpn .'</div>
<div class="title">Полезное</div>
<div class="c"><img src="'. URL .'images/icons/note.png" alt="lib"/><a href="library/">Библиотека</a>['. $lc .'/'. $la . $lan .']</div>
<div class="c"><img src="'. URL .'images/icons/stat.png" alt="stat"/><a href="statistika.php">Статистика</a></div>';


$res = mysqli_num_rows(quer("SELECT `id` FROM `friends`"));

if ($res){
    echo '<div class="title">Друзья и Партнеры</div>';
    $query = quer("SELECT * FROM `friends`");
    while($fr = mysqli_fetch_assoc($query)){
        echo '<div class="c"><a href="'. $fr['site'] .'">'. $fr['title'] .'</a></div>';        
    }
}   
echo '</div></div>';

foot();

?>