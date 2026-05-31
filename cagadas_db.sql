CREATE DATABASE IF NOT EXISTS cagadas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cagadas_db;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(50) NULL,
    address VARCHAR(500) NULL,
    profile_picture VARCHAR(255) NULL,
    profile_picture_base64 LONGTEXT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE songs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    album VARCHAR(255) NULL,
    genre VARCHAR(100) NULL,
    year YEAR NULL,
    file_path VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample Users (password = "password123" for all)
INSERT INTO users (name, email, password, gender, address, created_at, updated_at) VALUES
('Admin User',     'admin@music.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Male',   '123 Main St, Manila',    NOW(), NOW()),
('Juan dela Cruz', 'juan@music.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Male',   '456 Rizal Ave, Cebu',    NOW(), NOW()),
('Maria Santos',   'maria@music.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Female', '789 Mabini St, Davao',   NOW(), NOW()),
('Pedro Reyes',    'pedro@music.com',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Male',   '321 Bonifacio Blvd, QC', NOW(), NOW()),
('Ana Lim',        'ana@music.com',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Female', '654 Luna St, Makati',    NOW(), NOW());

-- Sample Songs
INSERT INTO songs (user_id, title, artist, album, genre, year, created_at, updated_at) VALUES
(1, 'Blinding Lights',         'The Weeknd',      'After Hours',          'Pop',      2020, NOW(), NOW()),
(1, 'Shape of You',            'Ed Sheeran',      'Divide',               'Pop',      2017, NOW(), NOW()),
(1, 'Bohemian Rhapsody',       'Queen',           'A Night at the Opera', 'Rock',     1975, NOW(), NOW()),
(2, 'Hotel California',        'Eagles',          'Hotel California',     'Rock',     1977, NOW(), NOW()),
(2, 'Smells Like Teen Spirit', 'Nirvana',         'Nevermind',            'Rock',     1991, NOW(), NOW()),
(2, 'Levitating',              'Dua Lipa',        'Future Nostalgia',     'Pop',      2020, NOW(), NOW()),
(3, 'Dynamite',                'BTS',             'BE',                   'K-Pop',    2020, NOW(), NOW()),
(3, 'Butter',                  'BTS',             'Butter',               'K-Pop',    2021, NOW(), NOW()),
(3, 'As It Was',               'Harry Styles',    'Harry\'s House',       'Pop',      2022, NOW(), NOW()),
(4, 'Despacito',               'Luis Fonsi',      'Vida',                 'Latin',    2017, NOW(), NOW()),
(4, 'Stay',                    'The Kid LAROI',   'F*CK LOVE 3',          'Hip-Hop',  2021, NOW(), NOW()),
(5, 'Peaches',                 'Justin Bieber',   'Justice',              'R&B',      2021, NOW(), NOW()),
(5, 'Good 4 U',                'Olivia Rodrigo',  'SOUR',                 'Pop-Rock', 2021, NOW(), NOW()),
(5, 'Montero',                 'Lil Nas X',       'Montero',              'Hip-Hop',  2021, NOW(), NOW());
