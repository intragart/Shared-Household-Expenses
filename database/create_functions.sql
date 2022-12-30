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

CREATE FUNCTION `login`(
	`try_username` VARCHAR(255),
	`try_password` VARCHAR(255)
)
RETURNS INT
LANGUAGE SQL
DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT 'Checks if the given username and password match and returns the user_id. If no match was found or password is incorrect 0 is returned.'
BEGIN

  DECLARE returned_user_id INT;

  SELECT user_id INTO returned_user_id
  FROM user
  WHERE username = try_username AND passwd_hash = PASSWORD(try_password)
  LIMIT 1;

  IF returned_user_id IS NULL THEN
    SET returned_user_id = 0;
  END IF;
  
  RETURN returned_user_id;

END
