-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: db
-- Время создания: Июн 02 2025 г., 17:31
-- Версия сервера: 9.2.0
-- Версия PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fitness_club`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `schedule_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int NOT NULL,
  `trainer_id` int NOT NULL,
  `schedule_datetime` datetime NOT NULL,
  `schedule_type` enum('group','individual') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `trainer_id`, `schedule_datetime`, `schedule_type`) VALUES
(1, 1, '2025-05-23 15:00:00', 'group'),
(2, 2, '2025-05-26 10:00:00', 'group'),
(3, 3, '2025-05-26 11:00:00', 'group'),
(4, 4, '2025-05-27 15:00:00', 'group'),
(5, 5, '2025-05-26 09:00:00', 'group'),
(6, 1, '2025-05-23 09:20:00', 'individual'),
(7, 1, '2025-05-23 09:20:00', 'individual'),
(8, 1, '2025-05-24 11:27:00', 'individual');

-- --------------------------------------------------------

--
-- Структура таблицы `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int NOT NULL,
  `trainer_photo` varchar(255) NOT NULL,
  `trainer_last_name` varchar(128) NOT NULL,
  `trainer_first_name` varchar(128) NOT NULL,
  `trainer_surname` varchar(128) DEFAULT NULL,
  `trainer_phone` varchar(24) NOT NULL,
  `trainer_specialization` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `trainer_photo`, `trainer_last_name`, `trainer_first_name`, `trainer_surname`, `trainer_phone`, `trainer_specialization`) VALUES
(1, 'assets/trainers/kuznetsov_alexey_viktorovich.jpg', 'Кузнецов', 'Алексей', 'Викторович', '+7(938)238-23-24', 'Функциональный тренинг'),
(2, 'assets/trainers/novikova_maria_andreevna.jpg', 'Новикова', 'Мария', 'Андреевна', '+7(942)127-54-78', 'Кардиотренировки'),
(3, 'assets/trainers/pavlov_jake_jackovich.jpg', 'Павлов', 'Джейк', 'Джекович', '+7(900)899-12-65', 'Силовой тренинг'),
(4, 'assets/trainers/romanov_pavel_petrovich.jpg', 'Романов', 'Павел', 'Петрович', '+7(912)833-76-89', 'Функциональный тренинг'),
(5, 'assets/trainers/smirnova_darya_alexandrovna.jpg', 'Смирнова', 'Дарья', 'Александровна', '+7(950)803-53-62', 'Йога');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_last_name` varchar(128) NOT NULL,
  `user_first_name` varchar(128) NOT NULL,
  `user_surname` varchar(128) DEFAULT NULL,
  `user_phone` varchar(24) NOT NULL,
  `user_password` varchar(128) NOT NULL,
  `user_role` enum('admin','client') NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_last_name`, `user_first_name`, `user_surname`, `user_phone`, `user_password`, `user_role`) VALUES
(1, 'Добрынин', 'Сергей', 'Александрович', '+7(123)456-78-90', '$2y$12$C6XYycmEiw6Ja5wKQeRS1uxHax.omdwORyih5fKHmHHcvThgrpMPm', 'admin'),
(2, 'Добрынин', 'Сергей', 'Александрович', '+7(900)900-90-90', '$2y$12$uq2yzgK0LJnMpTbWO.QzD.UrWq1hUP20elcRX/E/u54QMhusTK1h6', 'client');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Индексы таблицы `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Индексы таблицы `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
