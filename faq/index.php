<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит FAQ';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'FAQ';
head();

echo '<div class="all"><div class="menu"><div class="text">';

$acount = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `prava` >= '7'"));
echo '<div class="zag">Основа</div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/about.html">О сайте</a></div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/start.html">С чего начать</a></div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/rules.html">Правила</a></div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/administration.html">Администрация</a>['. $acount .']</div>';
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/statistika.php">Статистика</a></div>';
echo '<div class="zag">Общение</div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/bb_code.html">BB-коды</a></div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/smiles.html">Смайлы</a></div>';
echo '</div></div>';
foot();

?>