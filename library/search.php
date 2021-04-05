<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */


$set['where'] = 'смотрит Библиотеку';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Библиотека::Поиск';
head();

if ($aut > 0) {
    mc();
}
echo '<div class="all"><div class="menu"><div class="text">
<div class="zag"><a href="index.php">Библиотека</a>::Поиск</div>';
switch ($act = isset($_GET['act']) && !empty($_GET['act']) ? $act =
    $_GET['act'] : '') {
        //Результаты
    case 'result':
        if (isset($_GET['find'], $_GET['search_in']) && !empty($_GET['find'])) {
            $find = str_replace('*', '%', mysqli_real_escape_string($db, htmlspecialchars($_GET['find'])));
            $sin = htmlspecialchars($_GET['search_in']);

            if ($sin == 'title') {
                $mQuery = quer("SELECT `id` FROM `library_art` WHERE `title` LIKE '%{$find}%'");
            } else {
                $mQuery = quer("SELECT `id` FROM `library_art` WHERE `text` LIKE '%{$find}%'");
            }
            $res = mysqli_num_rows($mQuery);

            if ($res > 0) {
                $pag = abs($_GET['p']);

                if (empty($pag) or $pag == 0) {
                    $start = 0;
                } else {
                    $p = $pag - 1;
                    $start = $p * $num;
                }
                if ($sin == 'title') {
                    $q = quer("SELECT * FROM `library_art` WHERE `title` LIKE '%" . $find .
                        "%' && `mod` = '1' ORDER BY `id` DESC LIMIT " . $start . "," . $num . "");

                    while ($qq = mysqli_fetch_assoc($q)) {
                        $aid = $qq['id'];
                        $title = $qq['title'];
                        $time = $qq['time'];
                        $author = $qq['author'];


                        $u = mysqli_fetch_assoc(quer("SELECT `login`,`prava` FROM `users` WHERE `id` = '" .
                            $author . "'"));
                        $ulogin = $u['login'];
                        $uprav = $u['prava'];
                        echo '<div class="zag"><a href="article.php?id=' . $aid . '">' . $title .
                            '</a>[<a href="http://' . $_SERVER['HTTP_HOST'] . '/upan/info.php?id=' . $author .
                            '">' . $ulogin . '</a>' . status($uprav) . '][' . get_time($time, 5) .
                            ']</div>';
                    }
                } else {
                    $q = quer("SELECT * FROM `library_art` WHERE `text` LIKE '%" . $find .
                        "%' && `mod` = '1' ORDER BY `id` DESC LIMIT " . $start . "," . $num . "");

                    while ($qq = mysqli_fetch_assoc($q)) {
                        $aid = $qq['id'];
                        $title = $qq['title'];
                        $time = $qq['time'];
                        $author = $qq['author'];


                        $u = mysqli_fetch_assoc(quer("SELECT `login`,`prava` FROM `users` WHERE `id` = '" .
                            $author . "'"));
                        $ulogin = $u['login'];
                        $uprav = $u['prava'];
                        echo '<div class="zag"><a href="article.php?id=' . $aid . '">' . $title .
                            '</a>[<a href="http://' . $_SERVER['HTTP_HOST'] . '/upan/info.php?id=' . $author .
                            '">' . $ulogin . '</a>' . status($uprav) . '][' . get_time($time, 5) .
                            ']</div>';
                    }
                }
                nav($all, $num, "act=result&amp;find={$find}&amp;search_in={$sin}&amp;search_source={$source}");
                echo '<div class="c">Найдено: ' . $res . '</div>';
            } else {
                echo '<div class="c">Ничего не найдено!</div>
                <div class="c"><a href="search.php">Назад</a></div>';
            }
        } else {
            echo '<div class="c">Ничего не выбрано!</div>
            <div class="c"><a href="search.php">Назад</a></div>';
        }
        break;
        //Начальная страница поиска
    default:
        echo '
<div class="c">
<form action="search.php" method="GET">
<input type="hidden" name="act" value="result">
Что ищем:<br />
<small>В качестве шаблона используйте *</small><br />
<input name="find"/><br />
Поиск в:<br />
<input type="radio" name="search_in" value="title" checked="checked"/> - Названиях статей<br />
<input type="radio" name="search_in" value="article"/> - Статьях<br /><br />
<input type="submit" value="Поиск"/>
</form>
</div>';
}
echo '</div></div>';
foot();
?>