<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'меняет рейтинг';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{

if (is_numeric($_GET['id']) OR is_numeric($_POST['id']))
{
    if (isset($_GET['id']))
    {
    $id = (int)$_GET['id'];
    }
    else
    {
        $id = (int)$_POST['id'];
    }
if ($id != $u_id)
{
    if (isset($_GET['type']) && $_GET['type'] == 'plus' || isset($_GET['type']) && $_GET['type'] == 'minus' || isset($_POST['type']))
    {
        //Проверяем или чел еще не давал данному человеку свой голос
        $user_query = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `id` = '". $id ."'"));
        $res = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $id ."' && `w_id` = '". $u_id ."'"));
        
        if ($user_query > 0)
        {
            
            $fcount = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `l_id` = '". $u_id ."'"));
        
        
        if ($fcount < 50 && $prava < 7)
        {
            $set['title'] = 'Рейтинг::Ошибка';
            head();
            echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
            Для того чтобы ставить кому то рейтинг вы должны иметь не менее 100 сообщений на форуме!
            <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $id .'">назад</a></div>
            </div></div>';
            foot();
            exit();
        }
        if ($res > 0)
        {
            $set['title'] = 'Рейтинг::Ошибка';
            head();
            echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
            Данному человеку вы уже отдали свой голос!
            <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $id .'">назад</a></div>
            </div></div>';
            
            
        }
        else
        {
        $set['title'] = 'Рейтинг';
        head();
        if (isset($_GET['type']))
        {
        $type = filtr($_GET['type']);
        }
        else
        {
            $type = filtr($_POST['type']);
        }
        if ($type != 'plus' && $type != 'minus'){
            echo '<div class="c">Странный голос, вам не кажется?</div>';
        }
        elseif (isset($_POST['comm']) && !empty($_POST['comm']))
        {
            $comm = htmlspecialchars(mysqli_escape_string($db,$_POST['comm']));
            $time = time();
            quer("INSERT INTO `rating`(`type`,`l_id`,`w_id`,`msg`,`time`) VALUES('". $type ."','". $id ."', '". $u_id ."', '". $comm ."', '". $time ."')");
            echo '<font color="green">Рейтинг у человека изменен</font><br /><br />';
        }
        echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
        <form action="" method="post">
        Комментарий:<br />
        <textarea name="comm"></textarea><br />
        <input type="hidden" name="type" value="'. $type .'"/>
        <input type="hidden" name="id" value="'. $id.'"/>
        <input type="submit" value="Добавить"/>
        </form>        
        <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $id .'">назад</a></div>
        </div></div>';
        }
        }
        else
        {
            $set['title'] = 'Рейтинг::Ошибка';
            head();
            echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
            На сайте не зарегистрирован человек под таким id!
            <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/?">назад</a></div>
            </div></div>';
        }
    }
    else
    {
       $set['title'] = 'Рейтинг::Ошибка';
       head();
       echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
       Недопустимый запрос!
       <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $id .'">назад</a></div>
       </div></div>';   
    }
}
else
{

   $set['title'] = 'Рейтинг::Ошибка';
   head();
   echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
   Себе повышать или понижать рейтинг нельзя!
   <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">назад</a></div>
   </div></div>'; 
    
}
}
else
{
    $set['title'] = 'Рейтинг::Ошибка';
    head();
    echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>
    Такого id нет(надо вводить только числа)!</div></div>';
}


foot();
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']."/?err"); //ошибка
}
?>