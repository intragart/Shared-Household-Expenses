USE `shared_household_expenses`;

DROP USER IF EXISTS 'she_viewer'@'localhost';
CREATE USER 'she_viewer'@'localhost' IDENTIFIED BY '1234';
GRANT SELECT ON shared_household_expenses.dashboard TO 'she_viewer'@'localhost';
GRANT SELECT ON shared_household_expenses.dashboard_detail TO 'she_viewer'@'localhost';
GRANT SELECT ON shared_household_expenses.user_contribution TO 'she_viewer'@'localhost';
GRANT SELECT ON shared_household_expenses.retailer TO 'she_viewer'@'localhost';
GRANT SELECT ON shared_household_expenses.article_list TO 'she_viewer'@'localhost';

DROP USER IF EXISTS 'she_login'@'localhost';
CREATE USER 'she_login'@'localhost' IDENTIFIED BY '1234';
GRANT EXECUTE ON FUNCTION shared_household_expenses.login TO 'she_login'@'localhost';