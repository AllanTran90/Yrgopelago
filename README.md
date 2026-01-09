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
- 

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

## Link to my website

"https://allantran-wu25.se/yrgopelago/"

## FEEDBACK

#1: This one will be less about a single line of code and more of a structural feedback. I don't see a .gitignore file in your repo, and it has resulted in the vendor folder making it onto the public repo, as well as .DS_Store sneaking into your folders. I can't see a .env which is good but I can't see an .env.example either, so I can't be sure if your project has an API_KEY that it uses to communicate with the centralbank. The database file is also in the repo.

#2: Another structural feedback I would like to give is that there is a lot of php files in the root folder. I think it would be easier to navigate if files like booking.php were in their own backend folder. The assets folder I feel contains items that should be in said backend folder, namely the items in assets/availability. Maybe the includes folder could be expanded to serve this purpose.

#3: With the database inserts, I'm noticing a lack of any table that keeps track of the activities.

#4: Perhaps the guest's name could be in the users table instead of the bookings table?

#5: includes/centralbank.php: 38-39- Putting all the centralbank communication in a dedicated .php file was a great move, but I see no receipt function for communicating with the centralbank. I think it would be good to put it here. 

#6: index.php: 5-9 - These are unnecessary since the prices for the activities are already in the database, so making them a variable here is redundant and means if you wanna change the prices you have to change the priced for a room in more than one place, both here and in the db file.

#7: Great job on committing often! You did it way more than me and it's a great habit to have. I didn't commit enough and sometimes forgot to do so, which made versions bulky and would cause issues with finding exact issues.

