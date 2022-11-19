USE shared_household_expenses;

INSERT INTO `shared_household_expenses`.`retailer` (`retailer`) VALUES ('Händler 1');
INSERT INTO `shared_household_expenses`.`retailer` (`retailer`) VALUES ('Händler 2');
INSERT INTO `shared_household_expenses`.`retailer` (`retailer`) VALUES ('Händler 3');
INSERT INTO `shared_household_expenses`.`retailer` (`retailer`) VALUES ('Händler 4');

INSERT INTO `shared_household_expenses`.`purchase` (`article`,`date`,`retailer_id`) VALUES ('Einkauf','2022-11-16',1);
INSERT INTO `shared_household_expenses`.`purchase` (`article`,`date`,`retailer_id`) VALUES ('Werkzeug','2022-11-19',2);
INSERT INTO `shared_household_expenses`.`purchase` (`article`,`date`,`retailer_id`) VALUES ('Einkauf','2022-09-05',1);
INSERT INTO `shared_household_expenses`.`purchase` (`article`,`date`,`retailer_id`) VALUES ('Möbel','2021-07-13',3);
INSERT INTO `shared_household_expenses`.`purchase` (`article`,`date`,`retailer_id`) VALUES ('Schrauben','2021-07-15',2);

INSERT INTO `shared_household_expenses`.`user` (`username`,`pretty_name`,`passwd_hash`) VALUES ('persona','Person A',PASSWORD('1234'));
INSERT INTO `shared_household_expenses`.`user` (`username`,`pretty_name`,`passwd_hash`) VALUES ('personb','Person B',PASSWORD('1234'));

INSERT INTO `shared_household_expenses`.`contribution` (`purchase_id`,`contribution_id`,`user_id`,`amount`,`currency`)
VALUES (1,1,1,15,'EUR');
INSERT INTO `shared_household_expenses`.`contribution` (`purchase_id`,`contribution_id`,`user_id`,`amount`,`currency`,`comment`)
VALUES (2,1,2,60,'EUR','Schraubenzieher');
INSERT INTO `shared_household_expenses`.`contribution` (`purchase_id`,`contribution_id`,`user_id`,`amount`,`currency`)
VALUES (3,1,1,20,'EUR');
INSERT INTO `shared_household_expenses`.`contribution` (`purchase_id`,`contribution_id`,`user_id`,`amount`,`currency`)
VALUES (4,1,1,50,'EUR');
INSERT INTO `shared_household_expenses`.`contribution` (`purchase_id`,`contribution_id`,`user_id`,`amount`,`currency`,`comment`)
VALUES (4,2,2,70,'EUR','Tisch');
INSERT INTO `shared_household_expenses`.`contribution` (`purchase_id`,`contribution_id`,`user_id`,`amount`,`currency`)
VALUES (5,1,1,15,'EUR');
