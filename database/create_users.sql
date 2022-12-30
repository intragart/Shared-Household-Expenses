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

USE `shared_household_expenses`;

DROP USER IF EXISTS 'she_login'@'localhost';
CREATE USER 'she_login'@'localhost' IDENTIFIED BY '1234';
GRANT EXECUTE ON FUNCTION shared_household_expenses.login TO 'she_login'@'localhost';

DROP USER IF EXISTS 'she_admin'@'localhost';
CREATE USER 'she_admin'@'localhost' IDENTIFIED BY '1234';
GRANT DELETE ON shared_household_expenses.contribution TO 'she_admin'@'localhost';
GRANT DELETE ON shared_household_expenses.purchase TO 'she_admin'@'localhost';
GRANT INSERT ON shared_household_expenses.contribution TO 'she_admin'@'localhost';
GRANT INSERT ON shared_household_expenses.purchase TO 'she_admin'@'localhost';
GRANT INSERT ON shared_household_expenses.retailer TO 'she_admin'@'localhost';
GRANT INSERT ON shared_household_expenses.user TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.article_list TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.contribution TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.dashboard TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.dashboard_detail TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.purchase TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.retailer TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.user TO 'she_admin'@'localhost';
GRANT SELECT ON shared_household_expenses.user_contribution TO 'she_admin'@'localhost';
GRANT UPDATE ON shared_household_expenses.contribution TO 'she_admin'@'localhost';
GRANT UPDATE ON shared_household_expenses.purchase TO 'she_admin'@'localhost';
GRANT UPDATE ON shared_household_expenses.user TO 'she_admin'@'localhost';
