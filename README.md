# Yrgopelago hotel booking system.

This project is a booking system for a hotel. The system allows users to check room availability, select dates, chose room types and extra features. To complet the bookning we use an external payment service (Centralbank of Yrgopelag).

---

## Technologies Used

The project is build useing:

- **PHP**   backend logic and server-side validation
- **SQL**   database for booking and availability
- **HTML**  struckture and forms
- **CSS**   layout and design
- **JAVASCRIPT**    calendar interaction, availability checks and price calculation

---

## What the system does

- Users can check room availability using a calendar
- Rooms can be booked within a fixed date range (January)
- Three room types are available: Budget, Standard, and Luxury
- Extra features can be added for an additional cost
- The system calculates the total price automatically
- Payment is verified using the Central Bank of Yrgopelag
- A confirmation page shows booking details after successful payment

---

## How availability works

Room availability is checked by looking for the existing bookings in the database where:
arrival<=selected date AND departure > selected date

This ensures that room are available when no overlapping booking exists.
The result is returned  through an API and handled by Javascript.

---

## Payment handling

Before a booking is saved , the transfercode is validated against the Centralbank of Yrgopelag.

Invalid payment stops immidetaly.

---

## Why fetch, not Guzzle?

Fetch was used beacuse it was famililar and it was easir to work with within the projects timeframe. Guzzle would have required additional learning time.

---

## Future improvments

If I had more time, the following improvments could be made:

- Admin file to manage bookings and rooms
- Replace Fetch with Guzzle

## Database

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


