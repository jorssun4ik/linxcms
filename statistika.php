<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в статистике сайта';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Статистика сайта';

head();

if ($aut > 0)
{
mc();
}



if ($aut == 0)
{
	echo '<div class="top_title">';
}
else
{
echo '<div class="title">';
}
echo 'Статистика
</div>';
echo '<div class="menu">';

//Статистика форума
$r_forum = mysqli_num_rows(quer("SELECT `id` FROM `forum_p`"));
$pr_forum = mysqli_num_rows(quer("SELECT `id` FROM `forum_r`"));
$t_forum = mysqli_num_rows(quer("SELECT `id` FROM `forum_t`"));
$p_forum = mysqli_num_rows(quer("SELECT `id` FROM `forum_m`"));

echo '<div class="title"><img src = "'. URL .'images/icons/stat.png" alt = "stat"/> Статистика форума</div>';
echo '<div class="c">Разделов: '. $r_forum .'</div>';
echo '<div class="c">Подразделов: '. $pr_forum .'</div>';
echo '<div class="c">Тем: '. $t_forum .'</div>';
echo '<div class="c">Постов: '. $p_forum .'</div>';

//Статистика пользователей
$all_users = mysqli_num_rows(quer("SELECT `id` FROM `users`"));
$onl_users = mysqli_num_rows(quer("SELECT `id` FROM `online` WHERE `u_id` != '0'"));

echo '<div class="title">Статистика пользователей</div>';
echo '<div class="c">Онлайн пользователей: <a href="online.php">'. $onl_users .'</a></div>';
echo '<div class="c">Всего пользователей: <a href="all_users.php">'. $all_users .'</a></div>';

foot();

?>