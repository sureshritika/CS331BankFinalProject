CREATE TABLE IF NOT EXISTS Accounts_Management(
    username varchar(100),
    account_number varchar(12),
    PRIMARY KEY (username, account_number),
    FOREIGN KEY (`username`) REFERENCES Users(`username`),
    FOREIGN KEY (`account_number`) REFERENCES Accounts(`account_number`)
    )