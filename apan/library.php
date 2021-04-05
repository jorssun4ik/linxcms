<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
*/

$set['where'] = 'В запретной зоне';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0 && $prava >= 8)
{
    
    $result = mysqli_num_rows(quer("SELECT * FROM `library_cat`"));
    
    if (isset($_GET['id']) && !empty($_GET['id']))
    {
        $id = htmlspecialchars($_GET['id']);
        
        if ($id == 'del')
        {
            if (isset($_GET['f']) && !empty($_GET['f']) && is_numeric($_GET['f']))
            {
                $f = (int)$_GET['f'];
                
                $set['title'] = 'Удаление категории';
                head();
                echo '<div class="all"><div class="menu"><div class="text"><div class="title">Админка::Библиотека</div>';
                
                $rid = mysqli_num_rows(quer("SELECT * FROM `library_cat` WHERE `id` = '". $f ."'"));
                
                if ($rid > 0)
                {
                    quer("DELETE FROM `library_cat` WHERE `id` = '". $f ."'");
                    quer("OPTIMIZE TABLE `library_cat`"); //Оптимизируем
                    echo '<div class="с"><font color="green">Категория успешно удалена</font></div>
                    <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php">назад</a></div>';
                }   
                else
                {
                    echo '<div class="с"><font color="red">Такого ID нет</font></div>
                    <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php">назад</a></div>';
                } 
            }
            else
            {
                Header("Location: library.php");
                exit();
            }
            
        }
        elseif ($id == 'edit')
        {
            if (isset($_GET['f']) && !empty($_GET['f']) && is_numeric($_GET['f']))
            {
                $f = (int)$_GET['f'];
                
                $query = quer("SELECT * FROM `library_cat` WHERE `id` = '". $f ."'");
                
                if (mysqli_num_rows($query) > 0)
                {
                    
                    $set['title'] = 'Редактирование категории';
                    head();
                    echo '<div class="all"><div class="menu"><div class="text"><div class="title">Админка::Библиотека</div>';
                    
                    if (isset($_POST['title']))
                    {
                       if (empty($_POST['title']))
                    {
                       echo '<div class="с"><font color="red">Название категории пусто</font></div>
                       <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=edit&amp;f='. $f .'">назад</a></div>';
                    }
                    elseif (strlen($_POST['title']) < 3 OR strlen($_POST['title']) > 150)
                    {
                       echo '<div class="с"><font color="red">Название категории слишком длинное или короткое(min 3 symb., max 150 symb.)</font></div>
                       <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=edit&amp;f='. $f .'">назад</a></div>';
                    }
                    else
                    {
                       $title = mysqli_escape_string($db,htmlspecialchars($_POST['title']));
                       
                       $tres = mysqli_num_rows(quer("SELECT * FROM `library_cat` WHERE `title` = '". $title ."'"));
                       
                       if ($tres > 0)
                       {
                           echo '<div class="с"><font color="red">Такая категория уже есть</font></div>
                           <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=edit&amp;f='. $f .'">назад</a></div>';
                       }   
                       else
                       {                    
                       if (isset($_POST['desc']) && !empty($_POST['desc']))
                       {
                           $desc = mysqli_escape_string($db,htmlspecialchars($_POST['desc']));
                           quer("UPDATE `library_cat` SET `title` = '". $title ."', `description` = '". $desc ."' WHERE `id` = '". $f ."'");                      
                       }
                       else
                       {
                           quer("UPDATE `library_cat` SET `title` = '". $title ."' WHERE `id` = '". $f ."'");
                       }
                       
                       echo '<div class="с"><font color="green">Категория успешно изменена</font></div>
                       <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php">назад</a></div>'; 
                       }
                    }  
                    }
                    else
                    {    
                    $ecat = mysqli_fetch_assoc($query);
                    $title = $ecat['title'];
                    $desc = $ecat['description'];
                    
                    echo '<form action="library.php?id=edit&amp;f='. $f .'" method="post">
                    Название категории: <br /><input name="title" value="'. $title .'"/><br />                 
                    Описание категории: <br /><input name="desc" value="'. $desc .'"/><br />
                    <input type="submit" value="Изменить"/>
                    </form>
                    <div class="c"><a href="library.php">Назад</a></div>';
                    }
                }
                else
                {
                    Header("Location: library.php");
                }
            }   
            else
            {
                Header("Location: library.php");
            }         
        }
        elseif ($id == 'add')
        {
            $set['title'] = 'Библиотека::Создание категории';
            head();
            echo '<div class="all"><div class="menu"><div class="text"><div class="title">Админка::Библиотека</div>';
            if (isset($_POST['cat']) && isset($_POST['desc']))
            {
                if (empty($_POST['cat']))
                {
                    echo '<div class="с"><font color="red">Название категории пусто</font></div>
                    <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=add">назад</a></div>';
                }
                elseif (strlen($_POST['cat']) < 3 OR strlen($_POST['cat']) > 150)
                {
                    echo '<div class="с"><font color="red">Название категории слишком длинное или короткое(min 3 symb., max 150 symb.)</font></div>
                    <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=add">назад</a></div>';
                }
                else
                {
                    //Фильтруем и добавляем в базу
                    $cat = mysqli_escape_string($db, htmlspecialchars($_POST['cat']));
                    
                    $res = mysqli_num_rows(quer("SELECT * FROM `library_cat`"));
                    
                    $res+= 1;
                    
                    $cres = mysqli_num_rows(quer("SELECT * FROM `library_cat` WHERE `title` = '". $cat ."'"));
                    
                    
                    if ($cres > 0)
                    {
                        echo '<div class="с"><font color="red">Такая категория уже есть</font></div>
                       <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=add">назад</a></div>';
                    }
                    else
                    {
                    if (!empty($_POST['desc']))
                    {
                        $desc = mysqli_escape_string($db, htmlspecialchars($_POST['desc']));
                        quer("INSERT INTO `library_cat`(`position`,`title`,`description`) VALUES('". $res ."','". $cat ."','". $desc ."')");
                    }
                    else
                    {
                        quer("INSERT INTO `library_cat`(`position`,`title`) VALUES('". $res ."','". $cat ."')");
                    }
                    echo '<div class="c"><font color="green">Категория успешно создана</font></div>
                    <div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/apan/library.php">назад</a></div>';
                    }
                }
            }
            else
            {
            echo '<form action="library.php?id=add" method="post">
            Название категории*:<br /><input name="cat"/><br />
            Описание категории:<br /><input name="desc"/><br />
            <font color="red">*<small> - обязательно заполнить</small></font><br />
            <input type="submit" value="Создать"/></form>
            <div class="c"><a href="library.php">назад</a></div>';
            }
        }
        
    }
    else
    {
        
    $set['title'] = 'Библиотека';
    head();    
    echo '<div class="all"><div class="menu"><div class="text"><div class="title">Админка::Библиотека</div>';
        
    if ($result > 0)
    {
        $Query = quer("SELECT * FROM `library_cat` ORDER BY `position` ASC");
        
        while($ctg = mysqli_fetch_assoc($Query))
        {
            $id = $ctg['id']; //ид категории
            $title = $ctg['title']; //Название категории
            $desc = $ctg['description']; //Описание категории
            
            echo '<div class="zag">'. $title .'<a href="library.php?id=edit&amp;f='. $id .'">[ed]</a>
            <a href="library.php?id=del&amp;f='. $id .'">[del]</a></div>';
            if (!empty($desc))
            {
                echo '<div class="c"><small>'. $desc .'</small></div>';
            }
            
        }
    }
    else
    {
        echo '<div class="c">Категорий нет</div>';
    }
    echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=add">Создание категории</a></div>';
    }
    echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/?">В админку</a></div>';
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']);
    exit();
}
echo '</div></div>';
foot();
?>