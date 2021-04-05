<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'изменяет аватар';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{

if (isset($_GET['act']))
{
	$act = htmlspecialchars(trim($_GET['act']));
    
	if ($act == 'supload')
	{
		$ff = explode(".",$_FILES['avva']['name']);
         
        if (count($ff) != 2)
        {
        	$set['title'] = 'Обновление аватара :: Upload';
        head();
    	
    	echo '<div class="all"><div class="main"><div class="text"><div class="title">Upload</div>';
        	echo '<div class="c">Не может быть множество расширений, загружаемый файл должен быть типа такого: [имя файла.расширение]</div>';
        	echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/aadd.php">&#171; назад</a></div></div></div>';
        foot();
        exit();
        } 
        elseif(pathinfo($_FILES['avva']['name'],PATHINFO_EXTENSION) != "gif")
        {
        	$set['title'] = 'Обновление аватара :: Upload';
        head();
    	
    	echo '<div class="all"><div class="main"><div class="text"><div class="title">Upload</div>';
        	echo '<div class="c">Загружать файлы можно только с gif расширением!</div>';
        	echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/aadd.php">&#171; назад</a></div></div></div>';
        foot();
        exit();
        }
        else
        {              
                       if ((move_uploaded_file($_FILES["avva"]["tmp_name"], "avatars/".$u_id.".gif")) == true)
                    {
                        @chmod("avatars/".$u_id.".gif", 0777);
                        
                        	define('SOURCE', 'avatars/'.$u_id.'.gif');  // исходный файл
  define('TARGET', "avatars/".$login.".gif");     // имя файла для "превьюшки"
  define('NEWX', 80);               // ширина "превьюшки"
  define('NEWY', 80);                // высота "превьюшки"

  // Определяем размер изображения с помощью функции getimagesize:
  $size = getimagesize(SOURCE);
  // Функция getimagesize, требуя в качестве своего параметра имя файла,
  // возвращает массив, содержащий (помимо прочего, о чем можно прочитать
  // в документации), ширину - $size[0] - и высоту - $size[1] -
  // указанного изображения. Кстати, для ее использования не требуется наличие
  // библиотеки GD, так как она работает непосредственно с заголовками
  // графических файлов. В случае, если формат файла не распознан, getimagesize
  // возвращает false:
  if ($size === false) die ('Bad image file!');

  // Читаем в память JPEG-файл с помощью функции imagecreatefromjpeg:
  $source = imagecreatefromgif(SOURCE)
    or die('Cannot load original GIF');

  // Создаем новое изображение
  $target = imagecreatetruecolor(NEWX, NEWY);
  
  // Копируем существующее изображение в новое с изменением размера:
  imagecopyresampled(
    $target,  // Идентификатор нового изображения
    $source,  // Идентификатор исходного изображения
    0,0,      // Координаты (x,y) верхнего левого угла
              // в новом изображении
    0,0,      // Координаты (x,y) верхнего левого угла копируемого
              // блока существующего изображения
    NEWX,     // Новая ширина копируемого блока
    NEWY,     // Новая высота копируемого блока
    $size[0], // Ширина исходного копируемого блока
    $size[1]  // Высота исходного копируемого блока
    );	
    }
    imagegif($target, TARGET, 100);

  // Как всегда, не забываем:
  imagedestroy($target);
  imagedestroy($source);
  unlink("avatars/".$u_id.".gif");
                    }
              $set['title'] = 'Добавление аватара :: Upload';
              head();
    	
    	echo '<div class="all"><div class="main"><div class="text"><div class="title">Upload</div>';      
	         echo '<div class="c">Файл успешно загружен</div>';	
			 echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">&#171; анкета '. $login .'</a></div></div></div>';	
			 
			 
        
        foot();
        exit();
    	
    }
	elseif ($act == 'upload')
    {
    	$set['title'] = 'Обновление аватара :: Upload';
        head();
    	
    	echo '<div class="all"><div class="main"><div class="text"><div class="title">Upload</div>';
    	echo "<div class='c'>Загружаем аватар(ваш аватар будет сжат до 100x100 px. Загружать можно только .gif файлы)<br/><form action='http://".$_SERVER['HTTP_HOST']."/upan/arefr.php?act=supload' method='post' enctype='multipart/form-data'>";
            if (!eregi("Opera/8.01", $browser))
            {
                echo "<input type='file' name='avva'/><br/>";
            } else
            {
                echo "	<input name='ffile' value =''/>&nbsp;<br/>
<a href='op:fileselect'>Выбрать файл</a><br/>";
            }
            echo "<input type='submit' title='Нажмите для отправки' name='submit' value='Отправить'/><br/></form></div>";
            echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">&#171; анкета '. $login .'</a></div></div></div>';
    }

foot();
exit();
}

$set['title'] = 'Обновление аватара';
head();

echo '<div class="all"><div class="main"><div class="text">
<div class="title">
Добавление аватара
</div>
<div class="menu">';
echo '<div class="c">
<a href="http://'.$_SERVER['HTTP_HOST'].'/upan/arefr.php?act=upload">Upload</a><br />
</div>';

echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">&#171; анкета '. $login .'</a></div></div></div></div>';

foot();
} 
else
{
	Header("Location: ../index.php");
}
?>