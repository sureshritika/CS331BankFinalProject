CREATE TABLE IF NOT EXISTS Accounts(
    account_number varchar(12) unique PRIMARY KEY,
    balance int DEFAULT 0,
    recent_access_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP)