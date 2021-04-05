<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит рейтинг';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{
         if (isset($_GET['id']))
         {
         $id = (int)$_GET['id'];
         $user_query = mysqli_num_rows(quer("SELECT `login` FROM `users` WHERE `id` = '". $id ."'"));
         }
         else
         {
            $user_query = mysqli_num_rows(quer("SELECT `login` FROM `users` WHERE `id` = '". $u_id ."'")); //чтоб не ипаться
         }
         if ($user_query > 0)
         {
            //Можно приступать к выводу комментариев репутации
            $set['title'] = 'Рейтинг';
            head();
            if (isset($id))
            {
                $all = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $id ."'"));
            }
            else
            {
                $all = mysqli_num_rows(quer("SELECT `id` FROM `rating` WHERE `l_id` = '". $u_id ."'"));
            }
            echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div>';
            
            if ($all > 0)
            {
                $pag = (int)$_GET['p'];
                if (empty($pag) OR $pag == 0)
                {
	                $start = 0;
                } else
                {
	                $p = $pag - 1;
	                $start = $p*$num;
                }
                
                if (isset($id))
                {
                    $Query = quer("SELECT * FROM `rating` WHERE `l_id` = '". $id ."' ORDER BY `id` DESC LIMIT ".$start.",".$num."");
                }
                else
                {
                    $Query = quer("SELECT * FROM `rating` WHERE `l_id` = '". $u_id ."' ORDER BY `id` DESC LIMIT ".$start.",".$num."");
                }
                while($r = mysqli_fetch_assoc($Query))
                {
                    $w_id = $r['w_id']; //тот кто поставил
                    $type = $r['type']; //тип рейтинга минус или плюс
                    $comm = nl2br($r['msg']);
                    $time = $r['time'];
                    
                    $n = mysqli_fetch_assoc(quer("SELECT `login` FROM `users` WHERE `id` = '". $w_id ."'"));
                    $nick = $n['login']; //логин пользователя давшего челу какой то балл рейтинга(плюс либо минус)
                    
                    if ($type == 'p')
                    {
                        $type = 'положительный';
                    }
                    else
                    {
                        $type = 'отрицательный';
                    }
                    
                    echo '<b>Кто:</b> <a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $w_id .'">'.$nick.'</a>['. get_time($time,5) .']<br />
                    <b>Тип рейтинга:</b> '. $type .'<br />
                    <b>Комментарий:</b> '. $comm .'<br /><br />
                    ';                                        
                }
                nav($all,$num);
                if (isset($id))
                {
                    echo '<br /><div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $id .'">назад</a></div>';
                }
                else
                {
                    echo '<br /><div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">назад</a></div>';
                }
            }
            else
            {
                echo 'У данного пользователя еще никто не повышал и не понижал рейтинг!';
            }
            
         }
         else
         {
            $set['title'] = 'Рейтинг::Ошибка';
            head();
            echo '<div class="title">Рейтинг</div><div class="menu">
            На сайте не зарегистрирован человек под таким id!
            <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/?">назад</a></div>';
         }
     }
     else
     {
         $set['title'] = 'Рейтинг::Ошибка';
         head();
         echo '<div class="all"><div class="menu"><div class="text"><div class="title">Рейтинг</div><div class="menu">Такого id нет(надо вводить только числа)!';
     }
     echo '</div></div>';
     foot();
?>