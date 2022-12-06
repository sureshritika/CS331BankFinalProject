-- Active: 1670210508622@@db.ethereallab.app@3306@rs2392
CREATE TABLE Transactions (
transaction_code INT NOT NULL AUTO_INCREMENT,
type CHAR(2) NOT NULL,
transaction_timestamp TIMESTAMP,
amount INT,
account_no CHAR(12),
surcharge DECIMAL(2,0) NOT NULL,
PRIMARY KEY (transaction_code),
FOREIGN KEY (account_no) REFERENCES Accounts(account_number) ON DELETE SET NULL);