DROP DATABASE IF EXISTS `TAGCARD`;
CREATE DATABASE `TAGCARD` CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `TAGCARD`;

DROP TABLE IF EXISTS `CARD`;
CREATE TABLE `CARD` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`content` text NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `TAG`;
CREATE TABLE `TAG` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tag_card`;
CREATE TABLE `tag_card` (
	`card_id` int(10) NOT NULL,
	`tag_id` int(10) NOT NULL,
	PRIMARY KEY (card_id, tag_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `card` (`id`, `content`) VALUES (NULL, 'CARD1'), (NULL, 'CARD2'), (NULL, 'CARD3'), (NULL, 'CARD4'), (NULL, 'CARD5');
INSERT INTO `tag` (`id`, `name`) VALUES (NULL, 'TAG1'), (NULL, 'TAG2'), (NULL, 'TAG3'), (NULL, 'TAG4'), (NULL, 'TAG5');
INSERT INTO `tag_card` (`card_id`, `tag_id`) VALUES ('1', '1'), ('1', '2'), ('1', '3'), ('1', '4'), ('1', '5'), ('2', '1'), ('2', '3'), ('2', '5'), ('3', '1'), ('3', '2'), ('3', '4'), ('4', '2'), ('4', '4'), ('5', '1'), ('5', '5');

DROP TABLE IF EXISTS `tag_tag`;
CREATE TABLE `tag_tag` (
	`tag_id` int(10) NOT NULL,
	`with_tag_id` int(10) NOT NULL,
	`count` int(10) NOT NULL,
	PRIMARY KEY (tag_id, with_tag_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* SELECT * FROM `tag_tag` WHERE `tag_id` = 1 ORDER BY `count` DESC */