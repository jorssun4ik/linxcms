<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит онлайн';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Онлайн пользователи';

head();

if ($aut > 0)
{
mc();
}
echo '<div class="title">';
echo 'Онлайн Пользователи
</div>';
echo '<div class="all"><div class="menu"><div class="text">';
$all = mysqli_num_rows(quer("SELECT `id` FROM `online`"));

$query = quer("SELECT * FROM `online` GROUP BY `ip`,`browser` ORDER BY `time` DESC");

if (mysqli_num_rows($query) > 0)
{
 while($o = mysqli_fetch_assoc($query))
 {
 	$name = $o['u_id'];
 	$time = strip_tags($o['time']);
 	$i = mysqli_fetch_assoc(quer("SELECT `id`,`login` FROM `users` WHERE `id` = '". $name ."'"));
    $loggin = strip_tags($i['login']);
    $where = strip_tags($o['where']);
 	if ($aut > 0 && $u_id == $name)
 	{
 	echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">'. $loggin .'</a>['. get_time($time,1) .'] - '. $where .'</div>';	
 	}
    elseif ($aut > 0 && $name != 0)
    {
        echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $name .'">'. $loggin .'</a>['. get_time($time,1) .'] - '. $where .'</div>';	
    }
 	else
 	{
 	if ($name != 0)
    {
       echo '<div class="c">'. $loggin .'['. get_time($time,1) .'] - '. $where .'</div>';
    }  
    else
    {
        
     $bot = '';
    if(stripos($o['browser'], 'yandex') !== false)
    {
        $bot = 'Yandex';
    } 
    if (stripos($o['browser'], 'rambler') !== false)
    {
        $bot = 'Rambler';
    }
    if (stripos($o['browser'], 'mail') !== false)
    {
        $bot = 'Mail';
    }
    if (stripos($o['browser'], 'google') !== false)
    {
        $bot = 'Google';
    }
    if (stripos($o['browser'], 'yahoo') !== false || stripos($o['browser'], 'slurp') !== false)
    {
        $bot = 'Yahoo';
    }
    if (stripos($o['browser'], 'msn') !== false)
    {
        $bot = 'Bing(MSN)';
    }
    if (stripos($o['browser'], 'teoma') !== false)
    {
        $bot = 'Teoma';
    }
    if (stripos($o['browser'], 'scooter') !== false)
    {
        $bot = 'Scooter';
    }
    if (stripos($o['browser'], 'ia_archiver') !== false)
    {
        $bot = 'Archiver';
    }
    if (stripos($o['browser'], 'lycos') !== false )
    {
        $bot = 'Lycos';
    }
    if (stripos($o['browser'], 'webalta') !== false)
    {
        $bot = 'Webalta';
    }
    if (stripos($o['browser'], 'aport') !== false)
    {
        $bot = 'Aport';
    }
    if (stripos($o['browser'], 'linguee') !== false)
    {
        $bot = 'Linguee Bot';
    }
    if (stripos($o['browser'], 'ovale') !== false)
    {
        $bot = 'Ovale Bot';
    }
    if (stripos($o['browser'],'MLBot') !== false)
    {
        $bot = 'MLBot';
    }
    if (stripos($o['browser'],'Begun') !== false)
    {
        $bot = 'Begun Robot';
    }    
     if ($bot != '')
     {
        $loggin = '<font color="red">'. $bot .'</font>';
     }   
     else
     {
        $loggin = 'Гость';
     }
     
      echo '<div class="c">'. $loggin .'['. get_time($time,1) .'] - '. $where .'</div>';
    }
    }
 }
} 
else
{
echo 'Никого нет онлайн!<br />';	
}
echo '</div></div>';
foot();

?>