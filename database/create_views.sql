USE `shared_household_expenses`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `dashboard` AS
SELECT purchase.purchase_id, purchase.article, retailer.retailer, purchase.date, SUM(contribution.amount) AS amount,
group_concat(DISTINCT CASE
WHEN user.pretty_name IS NULL THEN user.username
ELSE user.pretty_name
END SEPARATOR ' & ') AS contributor
FROM purchase
LEFT JOIN contribution ON purchase.purchase_id = contribution.purchase_id
LEFT JOIN retailer ON purchase.retailer_id = retailer.retailer_id
LEFT JOIN user ON contribution.user_id = user.user_id
GROUP BY purchase.purchase_id
ORDER BY purchase.date DESC, purchase.article ASC;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `user_contribution` AS
SELECT user.user_id, user.username, user.pretty_name, 
CASE
WHEN user.pretty_name IS NULL THEN user.username
ELSE user.pretty_name
END display_name,
user.start_value,
CASE
WHEN SUM(contribution.amount) IS NULL THEN 0
ELSE SUM(contribution.amount)
END sum_contributions,
CASE
WHEN SUM(contribution.amount) IS NULL THEN user.start_value
ELSE user.start_value + SUM(contribution.amount)
END sum_user,
user.account_status
FROM user
LEFT JOIN contribution ON user.user_id = contribution.user_id
GROUP BY contribution.user_id
ORDER BY user.username;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `dashboard_detail` AS
SELECT contribution.purchase_id, contribution.contribution_id, user.username, contribution.amount, contribution.currency, contribution.comment
FROM contribution
LEFT JOIN user ON contribution.user_id = user.user_id
ORDER BY purchase_id ASC, username ASC, contribution_id ASC;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `article_list` AS
SELECT article, COUNT(*) AS quantity
FROM purchase
GROUP BY article
ORDER BY article ASC;
