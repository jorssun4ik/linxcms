<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'Читает статью';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if (isset($_GET['id']) && !empty($_GET['id']))
{

$id = abs($_GET['id']);
$art = quer("SELECT `title`,`text` FROM `library_art` WHERE id = '". $id ."' && `mod` = '1'");

if (mysqli_num_rows($art) > 0)
{
    if (isset($_GET['act']) && !empty($_GET['act']))
    {
        $act = htmlspecialchars($_GET['act']);
        
        if ($act == 'edit' && $prava >= 7){
        $set['title'] = 'Библиотека::Редактирование статьи!';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Редактирование статьи</div>';
        if (isset($_POST['title'],$_POST['msg']) && !empty($_POST['title']) && !empty($_POST['msg'])){
            $title = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['title'])));
            $msg = htmlspecialchars(mysqli_escape_string($db,$_POST['msg']));
            
            $result = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `title` = '". $title ."' && `text` = '". $msg ."'"));
            
            if (mb_strlen($title) < 3 OR mb_strlen($title) > 150){
                echo '<div class="c"><font color="red">Название статьи слишком маленькое или за длинное(мин 3, макс 150 символов))</font></div>';
            }
            elseif (mb_strlen($msg) < 50){
                echo '<div class="c"><font color="red">Текст статьи не может быть меньше 50 символов</font></div>';
            }
            elseif ($result > 0){
                echo '<div class="c"><font color="red">Такая статья уже есть</font></div>';
            }
            else {
                quer("UPDATE `library_art` SET `title` = '". $title ."', `text` = '". $msg ."' WHERE `id` = '". $id ."'");
                echo '<div class="c">Статья успешно изменена!</div>';
            }
            
        }
        else{        
        $a = mysqli_fetch_assoc($art);
        echo '<div class="c"><form action="?act=edit&amp;id='. $id .'" method="post">
        Название статьи: <br />
        <input name="title" value="'. $a['title'] .'"/><br />
        Текст статьи:<br />
        <textarea name="msg" cols="15" rows="4">'. $a['text'] .'</textarea>        
        <br />
        <input type="submit" value="Изменить"/>
        </form></div>';
        }
        echo '<div class="c"><a href="article.php?id='. $id .'">Смотреть статью</a></div>';
        }
        elseif ($act == 'del' && $prava >= 7){
            $set['title'] = 'Библиотека::Удаление статьи!';
            head();
            echo '<div class="all"><div class="menu"><div class="text">
            <div class="title">Удаление статьи статьи</div>';
            $result = mysqli_num_rows(quer("SELECT `cat_id` FROM `library_art` WHERE `id` = '". $id ."'"));
            
            if ($result > 0){
                quer("DELETE FROM `library_art` WHERE `id` = '". $id ."'");
                echo '<div class="c"><font color="green">Статья успешно удалена</font></div>';
            }else{
                echo '<div class="c"><font color="red">Такой статьи нет!</font></div>';
            }
            echo '<div class="c"><a href="index.php">Библиотека</a></div>';
        }
        echo '</div></div>';
        foot();
        exit();
    }
    //Проверяем или чел уже читал данное, если читал, то ничего не делаем, а если читал, то записываем в базу
    
    $res = mysqli_num_rows(quer("SELECT `id` FROM `library_count` WHERE `art_id` = '". $id ."' && `ip` = '". $ip ."' && `browser` = '". $browser ."'"));
    
    if ($res == 0)
    {
        quer("INSERT INTO `library_count`(`art_id`,`ip`,`browser`) VALUES('". $id ."','". $ip ."','". $browser ."')");
        quer("OPTIMIZE TABLE `library_count`"); //Оптимизируем таблицу
    }
      
    $artic = mysqli_fetch_assoc(quer("SELECT * FROM `library_art` WHERE `id` = '". $id ."' && `mod` = '1'"));
    
    $aid = $artic['id'];
    $cat_id = $artic['cat_id'];
    $title = $artic['title'];
    $text = trim(nl2br($artic['text']));
    
    $c = mysqli_fetch_assoc(quer("SELECT `title` FROM `library_cat` WHERE `id` = '". $cat_id ."'"));
    $cat_title = $c['title'];
    
    $time = $artic['time'];
    $autid =$artic['author'];
    
    $u = mysqli_fetch_assoc(quer("SELECT * FROM `users` WHERE `id` = '". $autid ."'"));
    $uprav = $u['prava'];
    $ulogin = $u['login'];
    
    if (isset($_GET['txt'])){
        $text = htmlspecialchars_decode(del_code($text));
        $st .= $title . ftime($time)."\n
        Добавленно: ". $ulogin."\n
        ".$text;
        
        $fp = fopen("temp/". $id .".txt", "a+");
        flock($fp, LOCK_EX);
        fputs($fp, "{$st}\r\n");
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        $file = "temp/{$id}.txt";

        header("Content-Type: text/plain");
        header("Accept-Ranges: bytes");
        header("Content-Length: " . filesize($file));
        header("Content-Disposition: attachment; filename=" . $file);
        readfile($file);

        unlink($file);
        exit();
    }
    
    $set['title'] = 'Библиотека::'. $cat_title .'::'.$title;
    head();
    echo '<div class="all"><div class="menu"><div class="text"><div class="title"><a href="index.php">Библиотека</a>::<a href="view.php?id='. $cat_id .'">'. $cat_title .'</a></div>';
    
    echo '<div class="zag">'. $title .'['. get_time($time,5) .']';
    if ($prava >= 7){
        echo '&nbsp;<a href="?act=edit&amp;id='. $id .'">[ed]</a> | <a href="?act=del&amp;id='. $id .'">[del]</a>';
    }
    echo '</div>
    <div class="c"><small>Добавленно:<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $autid.'">'. $ulogin .'</a>'.status($uprav).'</small></div>';
   
    
    $ctext = mb_strlen($text);
    

    
    $pag = abs($_GET['p']);
    if (empty($pag) OR $pag == 0)
    {
	    $start = 0;
    } 
    else
    {
	    $p = $pag - 1;
	    $start = $p*$sym;
    }
    
     
    $text = mb_substr($text,$start,$sym);
    
    $text = bb_code(smiles(nl2br(links($text))));
    
    //$text = del_code($text);
    echo '<div class="c">'. $text .'</div>';
    
    nav($ctext,$sym,'id='.$id);
    
    $count = mysqli_num_rows(quer("SELECT `id` FROM `library_count` WHERE `art_id` = '". $id ."'"));
    
    $comm = mysqli_num_rows(quer("SELECT `id` FROM `library_comm` WHERE `art_id` = '". $id ."'"));
    $com_day = mysqli_num_rows(quer("SELECT `id` FROM `library_comm` WHERE `art_id` = '". $id ."' && `time` >= '". last_24(time()) ."'"));
    
    echo '<div class="c"><font color="#999999"><small>Читало:<b>'.$count.'</b></small></font></div>
    <div class="c"><a href="comm.php?id='. $id .'">Комментарии</a>['. $comm .']';
    if ($com_day > 0)
    {
        echo '<font color="green">+'. $com_day .'</font>';
    }
    echo '</div><div class="c"><a href="article.php?id='. $id .'&amp;txt">Скачать в TXT</a></div>
    <div class="title"><a href="index.php">Библиотека</a>::<a href="view.php?id='. $cat_id .'">'. $cat_title .'</a></div>
    </div></div>';
    
    foot();
}
else
{
    Header("Location: index.php?err");
}
}
else
{
    Header("Location: index.php?err");
}
?>