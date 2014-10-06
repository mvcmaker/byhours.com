CREATE DATABASE IF NOT EXISTS `booking` CHARACTER SET = utf8 COLLATE = utf8_bin;
USE booking;

CREATE TABLE IF NOT EXISTS `customer` (
	`id` INT UNSIGNED NOT NULL COMMENT 'PK',
	`name` VARCHAR( 200 ) NOT NULL COMMENT 'Customer name',
	`surname` VARCHAR( 200 ) NULL DEFAULT NULL COMMENT 'Customer surname',
	`mail` VARCHAR( 200 ) NOT NULL COMMENT 'Customer mail address for sign up form',
	`blocked` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: not blocked, 1:Blocked by some reason',
	`lang` VARCHAR( 2 ) NULL COMMENT 'Iso2 for customer prefered language',
	`b_profile` TINYINT UNSIGNED NOT NULL COMMENT 'A 8-bit codification to set user profile. 0-bit:user app, 1-bit: Hote owner, 7-bit: Administrator',
	PRIMARY KEY ( `id` ),
	UNIQUE ( `name`, `surname` ),
	INDEX ( `lang` )
) ENGINE = InnoDB COMMENT = 'Main customers data';

CREATE TABLE IF NOT EXISTS `hotel` (
	`id` INT UNSIGNED NOT NULL COMMENT 'PK',
	`ido` INT UNSIGNED NOT NULL COMMENT 'FK to the owner of this hotel',
	`name` VARCHAR( 200 ) NOT NULL COMMENT 'Customer name',
	`longLocation` decimal(18,14) DEFAULT NULL COMMENT 'Long coordinate',
	`latLocation` decimal(18,14) DEFAULT NULL COMMENT 'Latitude coordinate',
	PRIMARY KEY ( `id` ),
	UNIQUE ( `name` ),
	FOREIGN KEY ( `ido` )
		REFERENCES `customer` ( `id` )
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE = InnoDB COMMENT = 'Main hotel data';

CREATE TABLE IF NOT EXISTS `room` (
	`id` INT UNSIGNED NOT NULL COMMENT 'PK',
	`idr` SMALLINT UNSIGNED NOT NULL COMMENT 'IND: Id room identifier for each hotel',
	`idh` INT UNSIGNED NOT NULL COMMENT 'FK to the main hotel',
	`door_num` SMALLINT NOT NULL COMMENT 'Real numeric number',
	`floor` TINYINT NULL DEFAULT NULL COMMENT 'Floor number',
	`blocked` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: not blocked, 1:Blocked by some reason',
	`dt_available` DATETIME NULL DEFAULT NULL COMMENT 'If not null: Datetime where this room will be available (by some reason)',
	PRIMARY KEY ( `id` ),
	INDEX ( `idr`, `idh` ),
	INDEX ( `idr`, `door_num` ),
	FOREIGN KEY ( `idh` )
		REFERENCES `hotel` ( `id` )
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE = InnoDB COMMENT = 'Main room data';

CREATE TABLE IF NOT EXISTS `booking` (
	`idr` INT UNSIGNED NOT NULL COMMENT 'FK to the room id',
	`idc` INT UNSIGNED NOT NULL COMMENT 'FK to the customer id that did this booking',
	`dt_booking` DATETIME NOT NULL COMMENT 'Datetime where this booking was done',
	`dt_entry` DATETIME NOT NULL COMMENT 'Datetime where booking begins',
	`dt_leaving` DATETIME NULL COMMENT 'NULL: Never finishes this booking (for special cases), but datetime where booking finishes',
	`accepted` TINYINT( 1 ) NOT NULL DEFAULT '1' COMMENT '0: booking not accepted, 1: booking accepted',
	`payed`  TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: not payed yet, 1: payed and accepted',
	PRIMARY KEY ( `idr`, `idc` ),
	FOREIGN KEY ( `idr` )
		REFERENCES `room` ( `id` )
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY ( `idc` )
		REFERENCES `customer` ( `id` )
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE = InnoDB COMMENT = 'Main booking data';

/* 1 - Daily sales report.*/
SELECT DATE(b.`dt_booking`) AS `date`, COUNT(b.`idc`) AS `total_sales` FROM `booking` b INNER JOIN `customer` c ON b.`idc`=c.`id` INNER JOIN `room` r ON b.`idr`=r.`id` INNER JOIN `hotel` h ON r.`idh`=h.`id` WHERE b.`payed`=1 AND b.`accepted`=1 AND b.`dt_booking`>NOW() GROUP BY `date` ORDER BY `date` DESC;

/* 2 - Best performer hotels of the day.*/
SELECT h.`id`, h.`name`, COUNT(b.`idc`) AS `total_sales` FROM `hotel` h INNER JOIN `room` r ON h.`id`=r.`idh` INNER JOIN `booking` b ON r.`id`=b.`idr` WHERE b.`accepted`=1 AND b.`payed`=1 AND `dt_booking`>=NOW()-INTERVAL 1 DAY GROUP BY h.`id`, h.`name` ORDER BY `total_sales` DESC LIMIT 10;

/* 3 - Weekly accumulated sales.*/
SELECT h.`name`, DATE_SUB(b.`dt_booking`, INTERVAL 1 WEEK) AS `date_weekly`, COUNT(b.`idc`) AS `total_sales` FROM `booking` b INNER JOIN `room` r ON b.`idr`=r.`id` INNER JOIN `hotel` h ON r.`idh`=h.`id` WHERE b.`payed`=1 AND b.`accepted`=1 AND b.`dt_booking`>NOW() GROUP BY `date_weekly` ORDER BY `date_weekly` DESC;