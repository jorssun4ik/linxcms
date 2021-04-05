<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит FAQ';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';


$set['title'] = 'FAQ - BB коды';
head();

echo '<div class="all"><div class="menu"><div class="text">';

echo '<div class="zag">BB коды</div>';
echo '
<div class="c">
[b]<b>Жирный текст</b>[/b]<br />
[i]<i>Текст</i>[/i]<br />
[u]<u>Подчеркнутый текст</u>[/u]<br />
[s]<s>Перечеркнутый текст</s>[/s]<br />
[center]<center>Текст по центру</center>[/center]<br />
[blue]<font color="blue">Синий текст</font>[/blue]<br />
[red]<font color="red">Красный текст</font>[/red]<br />
[url=kogep.ru[ссылка без http://]]<a href="http://'.$_SERVER['HTTP_HOST'].'">Название</a>[/url]
<br />
[code]<div class="phpcode">'. highlight_string("<?php echo 'Это PHP код'; //Также php код можно выводить и без тегов [code][/code], всего лишь написав начало и конец php кода <?php ?> ?>",true) .'</div>[/code]<br />
</div>
[quote]'. bb_code('[quote]цитирование сообщений![/quote]') .'[/quote]
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/index.html">FAQ</a></div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/">Главная</a></div>
</div></div>';
foot();
?>