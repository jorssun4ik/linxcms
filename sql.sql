-- phpMyAdmin SQL Dump
-- version 3.1.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 25 2010 г., 19:43
-- Версия сервера: 5.0.67
-- Версия PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kod`
--

-- --------------------------------------------------------

--
-- Структура таблицы `anketa`
--

CREATE TABLE IF NOT EXISTS `anketa` (
  `u_id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `birth_d` int(2) NOT NULL,
  `birth_m` int(2) NOT NULL,
  `birth_y` int(4) NOT NULL,
  `sex` varchar(1) NOT NULL,
  `about` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `icq` int(11) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `jabber` varchar(255) NOT NULL,
  `model` varchar(30) NOT NULL,
  `operator` varchar(30) NOT NULL,
  `country` varchar(50) NOT NULL,
  `city` varchar(100) NOT NULL,
  `interes` text NOT NULL,
  `rzan` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  PRIMARY KEY  (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `anketa`
--
-- --------------------------------------------------------

--
-- Структура таблицы `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `id` int(255) NOT NULL auto_increment,
  `u_id` int(100) NOT NULL,
  `who_id` int(100) NOT NULL,
  `cause` text NOT NULL,
  `stime` int(11) NOT NULL,
  `dtime` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `ban`
--


-- --------------------------------------------------------

--
-- Структура таблицы `ban_log`
--

CREATE TABLE IF NOT EXISTS `ban_log` (
  `id` int(255) NOT NULL auto_increment,
  `u_id` int(100) NOT NULL,
  `who_id` int(100) NOT NULL,
  `cause` text NOT NULL,
  `stime` int(11) NOT NULL,
  `dtime` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `ban_log`
--

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Структура таблицы `counter`
--

CREATE TABLE IF NOT EXISTS `counter` (
  `u_id` int(100) NOT NULL,
  `forum` int(20) NOT NULL,
  `chat` int(20) NOT NULL,
  `gb` int(20) NOT NULL,
  PRIMARY KEY  (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `counter`
--

INSERT INTO `counter` (`u_id`, `forum`, `chat`, `gb`) VALUES
(1, 17, 0, 1),
(0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `forum`
--

CREATE TABLE IF NOT EXISTS `forum` (
  `id` int(255) NOT NULL auto_increment,
  `fid` int(111) NOT NULL,
  `o_id` int(255) NOT NULL,
  `type` varchar(2) NOT NULL,
  `title` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `desc` text NOT NULL,
  `log_id` int(100) NOT NULL,
  `file` varchar(255) NOT NULL,
  `raz` int(150) NOT NULL,
  `spec` int(1) NOT NULL,
  `close` int(1) NOT NULL,
  `rcount` int(11) NOT NULL,
  `rtime` int(11) NOT NULL,
  `rname` varchar(50) NOT NULL,
  `time` int(20) NOT NULL,
  `last_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `forum`
--


-- --------------------------------------------------------

--
-- Структура таблицы `guest`
--

CREATE TABLE IF NOT EXISTS `guest` (
  `id` int(100) NOT NULL auto_increment,
  `login` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `browser` varchar(100) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Структура таблицы `library_art`
--

CREATE TABLE IF NOT EXISTS `library_art` (
  `id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `mod` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `library_art`
--

INSERT INTO `library_art` (`id`, `cat_id`, `title`, `text`, `time`, `author`, `mod`) VALUES
(1, 1, 'Первая статейка', 'Тестирование статейк, в PHP очень полезная штука, любимая =)фыфввыф аваавывао р\r\n ва ы\r\n арлдырваывда\r\n', 1268773282, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `library_cat`
--

CREATE TABLE IF NOT EXISTS `library_cat` (
  `id` int(11) NOT NULL auto_increment,
  `position` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `library_cat`
--

INSERT INTO `library_cat` (`id`, `position`, `title`, `description`) VALUES
(1, 1, 'PHP и MySQL', ''),
(2, 2, 'Операционные системы', ''),
(3, 3, 'SEO', ''),
(4, 4, 'Браузеры', ''),
(5, 5, 'Дизайн', '');

-- --------------------------------------------------------

--
-- Структура таблицы `library_comm`
--

CREATE TABLE IF NOT EXISTS `library_comm` (
  `id` int(11) NOT NULL auto_increment,
  `art_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `browser` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `library_comm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `library_count`
--

CREATE TABLE IF NOT EXISTS `library_count` (
  `id` int(11) NOT NULL auto_increment,
  `art_id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `browser` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `library_count`
--
-- --------------------------------------------------------

--
-- Структура таблицы `msg`
--

CREATE TABLE IF NOT EXISTS `msg` (
  `id` int(111) NOT NULL auto_increment,
  `u_id` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `tema` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `in` int(1) NOT NULL,
  `out` int(1) NOT NULL,
  `time` int(11) NOT NULL,
  `read` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `msg`
--


-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(100) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` int(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `news`
--

-- --------------------------------------------------------

--
-- Структура таблицы `online`
--

CREATE TABLE IF NOT EXISTS `online` (
  `id` int(255) NOT NULL auto_increment,
  `u_id` int(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `time` int(100) NOT NULL,
  `where` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `online`
--


-- --------------------------------------------------------

--
-- Структура таблицы `onl_log`
--

CREATE TABLE IF NOT EXISTS `onl_log` (
  `id` int(255) NOT NULL auto_increment,
  `ip` varchar(20) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `time` int(100) NOT NULL,
  `iron` varchar(255) NOT NULL,
  `where` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `onl_log`
--


-- --------------------------------------------------------

--
-- Структура таблицы `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(1) NOT NULL,
  `l_id` int(11) NOT NULL,
  `w_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `rating`
--


-- --------------------------------------------------------

--
-- Структура таблицы `reclame`
--

CREATE TABLE IF NOT EXISTS `reclame` (
  `id` int(1) NOT NULL auto_increment,
  `title` varchar(150) NOT NULL,
  `site` varchar(150) NOT NULL,
  `rtime` int(11) NOT NULL,
  `dtime` int(11) NOT NULL,
  `main` int(1) NOT NULL,
  `position` varchar(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `reclame`
--

INSERT INTO `reclame` (`id`, `title`, `site`, `rtime`, `dtime`, `main`, `position`) VALUES
(1, '', '', 0, 0, 0, 'h'),
(2, '', '', 0, 0, 0, 'h'),
(3, '', '', 0, 0, 0, 'h'),
(4, '', '', 0, 0, 0, 'f'),
(5, '', '', 0, 0, 0, 'f'),
(6, '', '', 0, 0, 0, 'f');

-- --------------------------------------------------------

--
-- Структура таблицы `reputation`
--

CREATE TABLE IF NOT EXISTS `reputation` (
  `id` int(11) NOT NULL auto_increment,
  `l_id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `w_id` int(11) NOT NULL,
  `ocenka` int(1) NOT NULL,
  `msg` varchar(500) NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(100) NOT NULL auto_increment,
  `login` varchar(50) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `prava` int(1) NOT NULL,
  `time` int(11) NOT NULL default '1254397088',
  `last_time` int(11) NOT NULL default '1254397088',
  `code` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `users`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users_log`
--

CREATE TABLE IF NOT EXISTS `users_log` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(150) NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_set`
--

CREATE TABLE IF NOT EXISTS `user_set` (
  `u_id` int(100) NOT NULL,
  `count` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;