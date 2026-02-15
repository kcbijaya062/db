
CREATE TABLE UserAccountData (
    username VARCHAR2(50) PRIMARY KEY,
    password VARCHAR2(50) NOT NULL,
    firstname VARCHAR2(50),
    lastname VARCHAR2(50)
);


CREATE TABLE UserSessionDatas (
    sessionid VARCHAR2(50) PRIMARY KEY,
    session_date DATE,
    username VARCHAR2(50) NOT NULL,
    FOREIGN KEY (username) REFERENCES UserAccountData(username)
);

CREATE TABLE StudentUserData (
    username VARCHAR2(50) PRIMARY KEY,
    date_of_admission DATE,
    FOREIGN KEY (username) REFERENCES UserAccountData(username)
);

CREATE TABLE StudentAdminUserData (
    username VARCHAR2(50) PRIMARY KEY,
    start_date DATE,
    FOREIGN KEY (username) REFERENCES UserAccountData(username)
);

INSERT INTO UserAccountData (username, password, firstname, lastname)
VALUES ('john_doe', 'securepassword', 'John', 'Doe');
ALTER TABLE UserAccountData ADD is_admin CHAR(1) DEFAULT 'N';

INSERT INTO UserAccountData (username, password, firstname, lastname)
VALUES ('bkc8', 'passwordsecure', 'biju', 'kcc');
INSERT INTO UserAccountData (username, password, firstname, lastname)
VALUES ('admin', 'admin', 'ads', 'minn');

UPDATE UserAccountData SET is_admin = 'Y' WHERE username = 'admin';
INSERT INTO UserAccountData (username, password, firstname, lastname)
VALUES ('admin_username', 'password', 'admins', 'user');
UPDATE UserAccountData SET is_admin = 'Y' WHERE username = 'admin_username';



INSERT INTO UserSessionDatas (sessionid, session_date, username)
VALUES ('session123', SYSDATE, 'john_doe');


INSERT INTO StudentUserData(username, date_of_admission)
VALUES ('john_doe', TO_DATE('2024-09-01', 'YYYY-MM-DD'));


INSERT INTO StudentAdminUserData (username, start_date)
VALUES ('john_doe', TO_DATE('2024-09-01', 'YYYY-MM-DD'));
