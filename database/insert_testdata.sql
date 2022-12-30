/*
Shared Household Expenses
Copyright (C) 2023  Marco Weingart

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

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
