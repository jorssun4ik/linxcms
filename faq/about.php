<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */


$set['where'] = 'смотрит FAQ';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';


$set['title'] = 'FAQ - О сайте';
head();

echo '<div class="all"><div class="menu"><div class="text">';

echo '<div class="zag">Что это?</div>';
echo '<div class="c">'.$_SERVER['HTTP_HOST'].' - это вап уголок для любителей общения. На данном портале все дружные.</div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/index.html">FAQ</a></div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/">Главная</a></div>
</div></div>';

foot();

?>