/* Chay trong SSMS, database DuLichDB, SAU file DuLichDB.sql */
USE DuLichDB;
GO

-- 1. Bang lien he (cho trang Lien he)
IF OBJECT_ID('contacts') IS NULL
CREATE TABLE contacts (
    contact_id INT IDENTITY(1,1) PRIMARY KEY,
    full_name  NVARCHAR(100) NOT NULL,
    email      VARCHAR(100)  NOT NULL,
    phone      VARCHAR(15),
    subject    NVARCHAR(200) NOT NULL,
    message    NVARCHAR(MAX) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT GETDATE()
);
GO

-- 2. Dat mat khau bcrypt cho tai khoan mau: tat ca deu la 123456
UPDATE users SET password_hash = '$2y$10$l6aWH.SPDGCmGGC6d8iageVKbjy.BeGI2HLxaoLVSya0er8DNJfQW';
GO

-- 3. (Tuy chon) Anh mau cho 3 tour (anh placeholder, co the doi sang link anh that)
UPDATE tour_images SET image_url = 'https://placehold.co/600x400/198754/ffffff?text=Sa+Pa'     WHERE tour_id = 1;
UPDATE tour_images SET image_url = 'https://placehold.co/600x400/0d6efd/ffffff?text=Da+Nang'    WHERE tour_id = 2;
UPDATE tour_images SET image_url = 'https://placehold.co/600x400/fd7e14/ffffff?text=Phu+Quoc'  WHERE tour_id = 3;
GO

SELECT user_id, role_id, full_name, email FROM users;
GO
