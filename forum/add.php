<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */


$set['where'] = 'смотрит Форум';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Добавление подфорума/раздела';

if ($prava >= 7)
{
head();
if ($aut > 0)
{
mc();
}
echo '<div class="all"><div class="menu"><div class="text">';

if (isset($_POST['title'],$_POST['type']) && !empty($_POST['title']) && !empty($_POST['type'])) {
    $title = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['title'])));
    $type = htmlspecialchars(mysqli_escape_string($db,$_POST['type']));
    
    if ($type == 'p') {
        
        $result = mysqli_num_rows(quer("SELECT `id` FROM `forum_p` WHERE `title` = '". $title ."'"));
        
        if (mb_strlen($title) > 40){
            echo '<div class="c">Длина подфорума слишком велика(max 40 символов)!</div>';
        }
        elseif (mb_strlen($title) < 4){
            echo '<div class="c">Длина подфорума слишком маленькая(min 4 символа)!</div>';
        }
        elseif ($result > 0){
            echo '<div class="c">Такой подфорум уже существует!</div>';
        }
        else{
            //Записываем в базу
            $res = mysqli_num_rows(quer("SELECT `id` FROM `forum_p`"));
            $pd = $res + 1;
            quer("INSERT INTO `forum_p`(`title`,`position`) VALUES('". $title ."','". $pd ."')");
            echo 'Подфорум успешно добавлен!';
        }
    }
    elseif ($type == 'r'){
        $pid = (int)$_POST['pid'];
        
        $presult = mysqli_num_rows(quer("SELECT `position` FROM `forum_p` WHERE `id` = '". $pid ."'"));
        $result = mysqli_num_rows(quer("SELECT `id` FROM `forum_r` WHERE `title` = '". $title ."' && `pid` = '". $pid ."'"));
        
        if ($presult == 0){
            echo '<div class="c">Такого подфорума нет!</div>';
        }
        elseif (mb_strlen($title) > 40){
            echo '<div class="c">Слишком большое название раздела(max 40 символов)!</div>';
        }
        elseif (mb_strlen($title) < 4){
            echo '<div class="c">Слишком маленькое название раздела(min 4 символа)</div>';
        }
        elseif($result > 0){
            echo '<div class="c">Такой раздел уже существует в данном подфоруме!</div>';
        }
        else
        {
            $rd = $result + 1;
            quer("INSERT INTO `forum_r`(`pid`,`title`,`position`) VALUES('". $pid ."','". $title ."', '". $rd ."')");
            echo '<div class="c">Раздел успешно создан</div>';
        }
    }
    else
    {
        echo '<div class="c">Ошибка!!!</div>';
    }
}
echo '<form action="add.php" method="post">
Название: <br />
<input name="title"/><br />
Тип:<br />
<select name="type">
<option value="p">Подфорум</option>';
$query = quer("SELECT `id`,`title` FROM `forum_p`");
if (mysqli_num_rows($query) > 0) {
echo '<option value="r">Раздел</option>';    
}
echo '</select>
<br />';

if (mysqli_num_rows($query) > 0) {
echo 'Подфорум:<br /><select name="pid">';
while($pf = mysqli_fetch_assoc($query)) {
    echo '<option value="'. $pf['id'] .'">'. $pf['title'] .'</option>';
}
echo '</select><br />';    
}
echo '
<input type="submit" value="Добавить"/>
</form><br />
<div class="c"><a href="index.php">Форум</a></div>';

}
else
{
    Header("Location: ?error"); //Ошибка 
    exit();
}
echo '</div></div>';
foot();
?>