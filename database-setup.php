<?php

$db = new PDO('sqlite:./data/database.sqlite');

// create users table
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    username TEXT NOT NULL UNIQUE
)");

// create groups table
$db->exec("CREATE TABLE IF NOT EXISTS groups (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    name TEXT NOT NULL UNIQUE
)");

// create messages table
$db->exec("CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_name TEXT NOT NULL,
    user_name TEXT NOT NULL,
    content TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS user_groups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    group_id INTEGER NOT NULL,
    UNIQUE(user_id, group_id),  -- ensure a user can only join a group once
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(group_id) REFERENCES groups(id)
)");

echo "Database and tables created successfully.";
