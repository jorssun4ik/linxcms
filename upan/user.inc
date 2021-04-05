<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

if ($aut > 0)
{
echo '<div class="header">';
if ($_SERVER['SCRIPT_NAME'] != "/index.php")
{
 echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>|';   
}    
echo '<img src="'. URL .'images/icons/menu.gif" alt="menu"/> <a href="http://'.$_SERVER['HTTP_HOST'].'/upan/?">'. $login .'</a> | ';
echo '<img src="'. URL .'images/icons/exit.gif" alt="exit"/> <a href="http://'.$_SERVER['HTTP_HOST'].'/exit.php">Выход</a>
</div>';
} else
{
echo '<div class="header">';
if ($_SERVER['SCRIPT_NAME'] != "/index.php")
{
 echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>|';   
}   
echo '<img src="'. URL .'images/icons/save.gif" alt="save"/> <a href="http://'.$_SERVER['HTTP_HOST'].'/registration/">Регистрация</a> | <img src="'. URL .'images/icons/log.png" alt="log"/> <a href="http://'.$_SERVER['HTTP_HOST'].'/aut.php">Авторизация</a> 
</div>';
}

?>