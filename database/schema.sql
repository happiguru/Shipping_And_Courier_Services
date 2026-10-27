-- NEXTGEN Logistics SQLite schema. Kept in sync with database/connection.php.
-- Automatic initialization is the normal deployment path.
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS admin (
 adminid INTEGER PRIMARY KEY AUTOINCREMENT,
 username TEXT NOT NULL COLLATE NOCASE UNIQUE,
 passw TEXT NOT NULL,
 created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS users (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 name TEXT NOT NULL DEFAULT '',
 email TEXT NOT NULL COLLATE NOCASE UNIQUE,
 password_hash TEXT NOT NULL,
 created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tracking (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 first_name TEXT NOT NULL, last_name TEXT NOT NULL,
 tracking_number TEXT NOT NULL UNIQUE, shipment_type TEXT NOT NULL,
 content TEXT NOT NULL DEFAULT '', shipment_date TEXT NOT NULL, delivery_date TEXT NOT NULL,
 source_city TEXT NOT NULL, source_state TEXT NOT NULL, source_zip TEXT NOT NULL DEFAULT '',
 current_city TEXT NOT NULL, current_state TEXT NOT NULL, current_zip TEXT NOT NULL DEFAULT '',
 destination_city TEXT NOT NULL, destination_state TEXT NOT NULL, destination_zip TEXT NOT NULL DEFAULT '',
 contact_number TEXT NOT NULL, parcel_status TEXT NOT NULL DEFAULT 'Packaging',
 created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CHECK(length(trim(tracking_number)) > 0),
 CHECK(parcel_status IN ('Packaging','Shipped','Transit','Delivered','Missing Parcel','On Hold'))
);
CREATE TABLE IF NOT EXISTS tracking_events (
 id INTEGER PRIMARY KEY AUTOINCREMENT, shipment_id INTEGER, tracking_number TEXT NOT NULL,
 status TEXT NOT NULL, location TEXT NOT NULL DEFAULT '', description TEXT NOT NULL DEFAULT '',
 event_time TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, created_by INTEGER,
 FOREIGN KEY(shipment_id) REFERENCES tracking(id) ON DELETE CASCADE,
 FOREIGN KEY(created_by) REFERENCES admin(adminid) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS contact_messages (
 id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL,
 subject TEXT NOT NULL DEFAULT '', message TEXT NOT NULL,
 status TEXT NOT NULL DEFAULT 'new' CHECK(status IN ('new','read','resolved','spam')),
 created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS quote_requests (
 id INTEGER PRIMARY KEY AUTOINCREMENT, first_name TEXT NOT NULL, last_name TEXT NOT NULL,
 email TEXT NOT NULL, contact_number TEXT NOT NULL DEFAULT '',
 pickup_address TEXT NOT NULL DEFAULT '', delivery_address TEXT NOT NULL DEFAULT '',
 pickup_date TEXT NOT NULL DEFAULT '', pickup_time TEXT NOT NULL DEFAULT '',
 expected_delivery_date TEXT NOT NULL DEFAULT '', pickup_city TEXT NOT NULL DEFAULT '',
 pickup_state TEXT NOT NULL DEFAULT '', pickup_country TEXT NOT NULL DEFAULT '',
 delivery_city TEXT NOT NULL DEFAULT '', delivery_state TEXT NOT NULL DEFAULT '',
 delivery_country TEXT NOT NULL DEFAULT '', receiver_name TEXT NOT NULL DEFAULT '',
 shipment_type TEXT NOT NULL DEFAULT '', pickup_type TEXT NOT NULL DEFAULT '',
 content TEXT NOT NULL DEFAULT '',
 status TEXT NOT NULL DEFAULT 'new' CHECK(status IN ('new','reviewing','quoted','closed')),
 created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS admin_login_attempts (
 ip_hash TEXT PRIMARY KEY, attempts INTEGER NOT NULL DEFAULT 0 CHECK(attempts >= 0),
 window_started_at INTEGER NOT NULL, locked_until INTEGER NOT NULL DEFAULT 0
);
CREATE INDEX IF NOT EXISTS idx_tracking_status ON tracking(parcel_status);
CREATE INDEX IF NOT EXISTS idx_tracking_delivery_date ON tracking(delivery_date);
CREATE INDEX IF NOT EXISTS idx_tracking_events_number_time ON tracking_events(tracking_number,event_time DESC);
CREATE INDEX IF NOT EXISTS idx_tracking_events_shipment ON tracking_events(shipment_id);
CREATE INDEX IF NOT EXISTS idx_contact_messages_status_date ON contact_messages(status,created_at DESC);
CREATE INDEX IF NOT EXISTS idx_quote_requests_status_date ON quote_requests(status,created_at DESC);
