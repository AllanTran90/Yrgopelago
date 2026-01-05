-- -------------------------------------------------------------
-- TablePlus 6.7.1(636)
--
-- https://tableplus.com/
--
-- Database: yrgopelago.db
-- Generation Time: 2026-01-05 21:42:02.8380
-- -------------------------------------------------------------


CREATE TABLE sqlite_sequence(name,seq);

CREATE TABLE payment(
id INTEGER PRIMARY KEY AUTOINCREMENT,
booking_id INTEGER,
amount DECIMAL(10,2),
payment_status TEXT,
created_at TIMESTAMP,
FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

CREATE TABLE bookings(
id INTEGER PRIMARY KEY AUTOINCREMENT,
room_id INTEGER,
guest_name TEXT,
arrival DATE,
departure DATE,
FOREIGN KEY(room_id) REFERENCES rooms(id) 
);

CREATE TABLE users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
booking_id INTEGER,
email TEXT,
FOREIGN KEY(booking_id) REFERENCES bookings(id)
);

CREATE TABLE rooms(
id INTEGER PRIMARY KEY,
title TEXT,
description VARCHAR (50000),
size VARCHAR (20),
price INTEGER
);

