<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'Смотрит библиотеку';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = (int)$_GET['id'];
    $cat = quer("SELECT `title` FROM `library_cat` WHERE id = '" . $id . "'");

    if (mysqli_num_rows($cat) > 0) {

        $t = mysqli_fetch_assoc($cat);
        $tcat = $t['title']; //Название категории

        $set['title'] = 'Библиотека::' . $tcat;
        head();
        echo '<div class="all"><div class="menu"><div class="text"><div class="title">Библиотека</div>';
        $res = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `cat_id` = '" .
            $id . "' && `mod` = '1'"));

        if ($res > 0) {
            $pag = abs($_GET['p']);

            if (empty($pag) or $pag == 0) {
                $start = 0;
            } else {
                $p = $pag - 1;
                $start = $p * $num;
            }
            $q = quer("SELECT * FROM `library_art` WHERE `cat_id` = '" . $id .
                "' && `mod` = '1' ORDER BY `id` DESC LIMIT " . $start . "," . $num . "");

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
            nav($res, $num, "id=" . $id);
        } else {
            echo '<div class="c">В данной категории нет статей</div>';
        }
        if ($aut > 0) {
            echo '<div class="c"><a href="add_article.php">Добавить статью</a></div>';
        }
        echo '<div class="c"><a href="index.php">Назад</a></div>
</div></div>';
        foot();
    } else {
        Header("Location: index.php?err");
    }
} else {
    Header("index.php?err");
}
?>