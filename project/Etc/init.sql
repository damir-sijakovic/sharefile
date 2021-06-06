CREATE TABLE files (
	id INTEGER PRIMARY KEY,
	filename TEXT,	
	key TEXT,
	expiresAt TEXT
);

CREATE TABLE sharefile (
	id INTEGER PRIMARY KEY,
	databaseCreatedAt TEXT
);


