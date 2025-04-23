USE s25_lps;

CREATE TABLE User (
	uid			AUTO_INCREMENT INT PRIMARY KEY,
	pnum			CHAR(12),
	email			VARCHAR(64) UNIQUE NOT NULL,
	name			VARCHAR(70),
	created_at 		TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at 		TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE PaymentInfo (
	payment_username	VARCHAR(32) PRIMARY KEY,
	payment_type		VARCHAR(16),
	uid			INT,
	FOREIGN KEY (uid) REFERENCES User(uid)
);

CREATE TABLE Admin (
	admin_ID		INT PRIMARY KEY,
	FOREIGN KEY (admin_ID) REFERENCES User(uid)
);

CREATE TABLE Ride (
	ride_ID			AUTO_INCREMENT INT PRIMARY KEY,
	destination		VARCHAR(128),
	available_seats		INT,
	dateTime		VARCHAR(18), --DDMMYYYY-00:00:00--
	uid			INT,
	FOREIGN KEY (uid) REFERENCES User(uid)
);

CREATE TABLE Requests (
	req_id			AUTO_INCREMENT INT PRIMARY KEY,
	reviewed_id		INT,
	reviewer_id		INT,
	FOREIGN KEY (ride_ID) REFERENCES Ride(ride_ID),
	FOREIGN KEY (passenger_ID) REFERENCES User(uid)
);

CREATE TABLE Car (
	license_plate		VARCHAR(8),
	make			VARCHAR(16),
	model			VARCHAR(32),
	color			VARCHAR(16),
	seats			INT,
	uid			INT,
	FOREIGN KEY (uid) REFERENCES User(uid),
	
);

CREATE TABLE Rates (
	rate_id			AUTO_INCREMENT INT PRIMARY KEY,
	reviewed_id		INT,
	reviewer_id		INT,
	FOREIGN KEY (reviewed_id) REFERENCES User(uid),
	FOREIGN KEY (reviewer_id) REFERENCES User(uid),
	rating			INT,
	review			VARCHAR(255),
	dateTime		TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	
);
