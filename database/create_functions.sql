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
