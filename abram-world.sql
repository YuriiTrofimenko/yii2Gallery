-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 17 2016 г., 16:12
-- Версия сервера: 5.5.50
-- Версия PHP: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `abram-world`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Photo`
--

CREATE TABLE IF NOT EXISTS `Photo` (
  `id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `album_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `PhotoAlbum`
--

CREATE TABLE IF NOT EXISTS `PhotoAlbum` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover_id` int(11) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `PhotoAlbum`
--

INSERT INTO `PhotoAlbum` (`id`, `name`, `user_id`, `cover_id`, `description`, `createdAt`, `updatedAt`) VALUES
(1, 'Common', 1, NULL, NULL, '2016-08-01 00:00:00', '2016-08-01 00:00:00'),
(2, 'Test album 2', 1, NULL, '', '2016-09-16 16:07:33', '2016-09-16 16:07:33'),
(3, 'Test album 3', 1, NULL, 'test d 3', '2016-09-16 16:24:24', '2016-09-16 16:24:24');

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `maritalStatus` enum('married','not married') DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`id`, `password`, `email`, `firstname`, `lastname`, `birth`, `gender`, `maritalStatus`, `createdAt`, `updatedAt`) VALUES
(1, '$2y$13$D1GuqBCYJQv5heBdCL3Vy.H9TcTJSsPDf6OKx77m7Var2gDPzCFKK', 'email1@email.com', 'firstname1', 'lastname1', NULL, NULL, NULL, '2016-07-28 23:16:08', '2016-07-28 23:16:08'),
(2, '$2y$13$a1hO9SP5XBbozJggCY6hleU8DLa1t6UoMCAmvAz.AEJlrvjL.fWPa', 'tyaa@ukr.net', 'Yurii', 'Trofimenko', NULL, NULL, NULL, '2016-07-30 18:54:09', '2016-07-30 18:54:09');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Photo`
--
ALTER TABLE `Photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Индексы таблицы `PhotoAlbum`
--
ALTER TABLE `PhotoAlbum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `NI_COVER_ID` (`cover_id`),
  ADD KEY `NI_USER_ID` (`user_id`);

--
-- Индексы таблицы `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `User_email_uindex` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Photo`
--
ALTER TABLE `Photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT для таблицы `PhotoAlbum`
--
ALTER TABLE `PhotoAlbum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Photo`
--
ALTER TABLE `Photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `PhotoAlbum` (`id`),
  ADD CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

--
-- Ограничения внешнего ключа таблицы `PhotoAlbum`
--
ALTER TABLE `PhotoAlbum`
  ADD CONSTRAINT `photoalbum_ibfk_2` FOREIGN KEY (`cover_id`) REFERENCES `Photo` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `photoalbum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
