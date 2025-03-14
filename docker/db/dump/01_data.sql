USE php_test;
SET collation_connection = utf8mb4_unicode_ci;
SET NAMES utf8;

INSERT INTO `companies` (`id`,`name`) VALUES
(1,'A@A');

INSERT INTO `agencies` (`id`,`name`) VALUES
(1,'ООО "Рога и копыта"'),
(2,'ООО "Наследие"');

INSERT INTO `countries` (`id`,`name`) VALUES
(1,'Россия'),
(2,'Беларусь'),
(3,'Казахстан');

INSERT INTO `cities` (`id`,`name`,`country_id`) VALUES
(1,'Москва',1),
(2,'Санкт Петербург',1);

INSERT INTO `hotels` (`id`,`name`, `city_id`, `stars`) VALUES
(1,'Балчуг Кемпински Москва', 1, 5),
(2,'Измайлово Альфа', 1, 4),
(3,'Золотое кольцо', 1, 5),
(4,'Плаза Гарден Москва Центр Международной Торговли', 1, 5),
(5,'Измайлово Гамма', 1, 3),
(6,'Нептун', 2, 4),
(7,'Ладога-отель', 2, 3),
(8,'Питер Академия', 2, 3),
(9,'Марко Поло', 2, 4),
(10,'Герцен-Хаус', 2, 1);

INSERT INTO `hotel_agreements` (`id`,`hotel_id`,`discount_percent`,`comission_percent`,`is_default`,`vat_percent`,`vat1_percent`,`vat1_value`,`company_id`,`date_from`,`date_to`,`is_cash_payment`) VALUES
(1,1,10,0,1,20,1,0,1,'2023-01-01','2024-01-01',0),
(2,2,12,0,1,20,1,0,1,'2023-01-01','2024-01-01',0),
(3,3,0,15,1,20,1,0,1,'2023-01-01','2024-01-01',1),
(4,4,12,0,1,20,1,0,1,'2023-01-01','2024-01-01',1),
(5,5,0,10,1,20,1,0,1,'2023-01-01','2024-01-01',1),
(6,6,5,0,1,20,1,0,1,'2023-01-01','2024-01-01',0),
(7,7,0,12,1,20,1,0,1,'2023-01-01','2024-01-01',1),
(8,8,10,0,1,20,1,0,1,'2023-01-01','2024-01-01',1),
(9,9,0,12,1,20,1,0,1,'2023-01-01','2024-01-01',1),
(10,10,14,0,1,20,1,0,1,'2023-01-01','2024-01-01',0);

INSERT INTO `agency_hotel_options` (`id`,`hotel_id`,`agency_id`,`percent`,`is_black`,`is_recomend`,`is_white`) VALUES
(1,1,1,10,0,0,0),
(2,2,1,5,0,0,0),
(3,3,1,8,1,0,0),
(4,4,1,12,0,0,0),
(5,5,1,8,1,0,0),
(6,6,1,15,0,0,0),
(7,7,1,8,0,0,0),
(8,8,1,11,1,0,0),
(9,9,1,12,0,0,0),
(10,10,1,6,0,0,0),
(11,1,2,8,0,0,0),
(12,2,2,12,1,0,0),
(13,3,2,6,0,0,0),
(14,4,2,10,0,0,0),
(15,5,2,9,0,0,0),
(16,6,2,11,1,0,0),
(17,7,2,4,0,0,0),
(18,8,2,12,0,0,0),
(19,9,2,10,1,0,0),
(20,10,2,14,1,0,0);

INSERT INTO `agency_rules` (`id`, `name`, `manager_text`, `agency_id`, `hotel_id`, `active`) VALUES
(1,	'Test 1',	'Text for manager test 123 ',	1,	1,	1),
(2,	'Test 2',	'Text for manager test 1234 ',	1,	1,	1),
(3,	'Test 3',	'Some text for manager 12345 ',	2,	1,	1),
(4,	'Test 4',	'Some text for manager 123 ',	2,	1,	1),
(5,	'Test 5',	'CHECK: ',	1,	6,	1),
(6,	'Test 6',	'ATTENTION: ',	1,	5,	1),
(7,	'Test 7',	'Some text for manager',	2,	5,	1),
(8,	'Test 8',	'cringy: ',	1,	2,	1),
(9,	'Test 9',	'coca cola: ',	2,	2,	1);

INSERT INTO `agency_rules_condition` (`id`, `rule_id`, `rule_type`, `rule_operator`, `rule_value`, `active`) VALUES
(1,	1,	0,	0,	'1',	1),
(2,	2,	0,	0,	'1',	1),
(3,	3,	0,	0,	'1',	1),
(4,	4,	0,	0,	'1',	1),
(5,	1,	6,	0,	'0',	1),
(6,	2,	6,	0,	'0',	1),
(7,	5,	6,	0,	'1',	1),
(8,	5,	0,	0,	'1',	1),
(9,	6,	6,	0,	'0',	1),
(10,	6,	5,	0,	'1',	1),
(11,	5,	3,	0,	'10',	1),
(12,	5,	8,	0,	'0',	1),
(13,	7,	0,	0,	'1',	1),
(14,	8,	0,	0,	'1',	1),
(15,	7,	6,	0,	'0',	1),
(16,	8,	6,	0,	'0',	1),
(17,	7,	4,	0,	'1',	1);
