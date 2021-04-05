<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит FAQ';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'FAQ - С чего начать';
head();

echo '<div class="all"><div class="menu"><div class="text"><div class="zag">Начало</div>';
echo '<div class="c">Чтобы пользоваться всеми функциями портала вам надо зарегистрироваться на нем!</div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/index.html">FAQ</a></div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/">Главная</a></div></div></div>';

foot();

?>