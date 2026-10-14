/* =====================================================
   CSDL WEBSITE DU LICH VA DAT TOUR - SQL SERVER
   Chay sau khi da tao DuLichDB (CREATE DATABASE DuLichDB)
   ===================================================== */
USE DuLichDB;
GO

/* ---------- 1. TAO BANG ---------- */

CREATE TABLE roles (
    role_id    INT IDENTITY(1,1) PRIMARY KEY,
    role_name  NVARCHAR(50) NOT NULL UNIQUE
);
GO

CREATE TABLE users (
    user_id        INT IDENTITY(1,1) PRIMARY KEY,
    role_id        INT NOT NULL,
    full_name      NVARCHAR(100) NOT NULL,
    email          VARCHAR(100)  NOT NULL UNIQUE,
    password_hash  VARCHAR(255)  NOT NULL,
    phone          VARCHAR(15),
    address        NVARCHAR(255),
    status         TINYINT NOT NULL DEFAULT 1,
    created_at     DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_users_roles FOREIGN KEY (role_id) REFERENCES roles(role_id)
);
GO

CREATE TABLE destinations (
    destination_id INT IDENTITY(1,1) PRIMARY KEY,
    name           NVARCHAR(100) NOT NULL,
    region         NVARCHAR(50),
    description    NVARCHAR(MAX),
    image_url      VARCHAR(255)
);
GO

CREATE TABLE categories (
    category_id INT IDENTITY(1,1) PRIMARY KEY,
    name        NVARCHAR(100) NOT NULL,
    description NVARCHAR(255)
);
GO

CREATE TABLE tours (
    tour_id             INT IDENTITY(1,1) PRIMARY KEY,
    category_id         INT,
    destination_id      INT,
    tour_name           NVARCHAR(200) NOT NULL,
    duration_days       INT NOT NULL CHECK (duration_days > 0),
    duration_nights     INT NOT NULL CHECK (duration_nights >= 0),
    departure_place     NVARCHAR(100),
    adult_price         DECIMAL(12,0) NOT NULL CHECK (adult_price >= 0),
    child_price         DECIMAL(12,0) NOT NULL CHECK (child_price >= 0),
    description         NVARCHAR(MAX),
    inclusions          NVARCHAR(MAX),
    cancellation_policy NVARCHAR(MAX),
    status              TINYINT NOT NULL DEFAULT 1,
    created_at          DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_tours_categories   FOREIGN KEY (category_id)    REFERENCES categories(category_id),
    CONSTRAINT FK_tours_destinations FOREIGN KEY (destination_id) REFERENCES destinations(destination_id)
);
GO

CREATE TABLE tour_itineraries (
    itinerary_id INT IDENTITY(1,1) PRIMARY KEY,
    tour_id      INT NOT NULL,
    day_number   INT NOT NULL,
    title        NVARCHAR(200) NOT NULL,
    content      NVARCHAR(MAX),
    CONSTRAINT FK_itin_tours FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE CASCADE,
    CONSTRAINT UQ_itin_tour_day UNIQUE (tour_id, day_number)
);
GO

CREATE TABLE tour_images (
    image_id   INT IDENTITY(1,1) PRIMARY KEY,
    tour_id    INT NOT NULL,
    image_url  VARCHAR(255) NOT NULL,
    is_primary BIT NOT NULL DEFAULT 0,
    CONSTRAINT FK_img_tours FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE CASCADE
);
GO

CREATE TABLE departures (
    departure_id    INT IDENTITY(1,1) PRIMARY KEY,
    tour_id         INT NOT NULL,
    start_date      DATE NOT NULL,
    end_date        DATE NOT NULL,
    total_seats     INT NOT NULL CHECK (total_seats > 0),
    available_seats INT NOT NULL CHECK (available_seats >= 0),
    status          VARCHAR(20) NOT NULL DEFAULT 'OPEN'
        CHECK (status IN ('OPEN','FULL','CLOSED','CANCELLED')),
    CONSTRAINT FK_dep_tours FOREIGN KEY (tour_id) REFERENCES tours(tour_id),
    CONSTRAINT CK_dep_dates CHECK (end_date >= start_date),
    CONSTRAINT CK_dep_seats CHECK (available_seats <= total_seats)
);
GO

CREATE TABLE promotions (
    promotion_id    INT IDENTITY(1,1) PRIMARY KEY,
    code            VARCHAR(30) NOT NULL UNIQUE,
    discount_type   VARCHAR(10) NOT NULL CHECK (discount_type IN ('PERCENT','AMOUNT')),
    discount_value  DECIMAL(12,2) NOT NULL CHECK (discount_value > 0),
    min_order_value DECIMAL(12,0) NOT NULL DEFAULT 0,
    start_date      DATE NOT NULL,
    end_date        DATE NOT NULL,
    usage_limit     INT,
    used_count      INT NOT NULL DEFAULT 0,
    CONSTRAINT CK_promo_dates CHECK (end_date >= start_date)
);
GO

CREATE TABLE bookings (
    booking_id      INT IDENTITY(1,1) PRIMARY KEY,
    booking_code    VARCHAR(20) NOT NULL UNIQUE,
    user_id         INT NOT NULL,
    departure_id    INT NOT NULL,
    promotion_id    INT NULL,
    adult_count     INT NOT NULL CHECK (adult_count >= 1),
    child_count     INT NOT NULL DEFAULT 0 CHECK (child_count >= 0),
    subtotal        DECIMAL(12,0) NOT NULL,
    discount_amount DECIMAL(12,0) NOT NULL DEFAULT 0,
    total_amount    DECIMAL(12,0) NOT NULL,
    contact_name    NVARCHAR(100) NOT NULL,
    contact_phone   VARCHAR(15) NOT NULL,
    note            NVARCHAR(500),
    status          VARCHAR(20) NOT NULL DEFAULT 'PENDING'
        CHECK (status IN ('PENDING','PAID','CONFIRMED','COMPLETED','CANCELLED')),
    created_at      DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_book_users      FOREIGN KEY (user_id)      REFERENCES users(user_id),
    CONSTRAINT FK_book_departures FOREIGN KEY (departure_id) REFERENCES departures(departure_id),
    CONSTRAINT FK_book_promotions FOREIGN KEY (promotion_id) REFERENCES promotions(promotion_id)
);
GO

CREATE TABLE booking_passengers (
    passenger_id   INT IDENTITY(1,1) PRIMARY KEY,
    booking_id     INT NOT NULL,
    full_name      NVARCHAR(100) NOT NULL,
    gender         NVARCHAR(10),
    birth_date     DATE,
    passenger_type VARCHAR(10) NOT NULL CHECK (passenger_type IN ('ADULT','CHILD')),
    CONSTRAINT FK_pass_bookings FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
);
GO

CREATE TABLE payments (
    payment_id       INT IDENTITY(1,1) PRIMARY KEY,
    booking_id       INT NOT NULL,
    method           VARCHAR(30) NOT NULL CHECK (method IN ('BANK','MOMO','VNPAY','CASH')),
    amount           DECIMAL(12,0) NOT NULL CHECK (amount > 0),
    transaction_code VARCHAR(100),
    status           VARCHAR(20) NOT NULL DEFAULT 'PENDING'
        CHECK (status IN ('PENDING','SUCCESS','FAILED','REFUNDED')),
    paid_at          DATETIME,
    CONSTRAINT FK_pay_bookings FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);
GO

CREATE TABLE reviews (
    review_id  INT IDENTITY(1,1) PRIMARY KEY,
    user_id    INT NOT NULL,
    tour_id    INT NOT NULL,
    booking_id INT NOT NULL,
    rating     TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment    NVARCHAR(MAX),
    status     TINYINT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_rev_users    FOREIGN KEY (user_id)    REFERENCES users(user_id),
    CONSTRAINT FK_rev_tours    FOREIGN KEY (tour_id)    REFERENCES tours(tour_id),
    CONSTRAINT FK_rev_bookings FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    CONSTRAINT UQ_rev_booking UNIQUE (booking_id)
);
GO

CREATE TABLE favorites (
    user_id    INT NOT NULL,
    tour_id    INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT PK_favorites PRIMARY KEY (user_id, tour_id),
    CONSTRAINT FK_fav_users FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT FK_fav_tours FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE CASCADE
);
GO

/* ---------- 2. DU LIEU MAU ---------- */

INSERT INTO roles(role_name) VALUES (N'ADMIN'), (N'STAFF'), (N'CUSTOMER');

INSERT INTO users(role_id, full_name, email, password_hash, phone, address) VALUES
(1, N'Quản trị viên', 'admin@dulich.vn', 'hash_admin', '0900000001', N'Hà Nội'),
(2, N'Nguyễn Văn Nhân', 'staff@dulich.vn', 'hash_staff', '0900000002', N'Hà Nội'),
(3, N'Trần Thị Lan', 'lan@gmail.com', 'hash_lan', '0912345678', N'Hà Nội'),
(3, N'Lê Minh Hoàng', 'hoang@gmail.com', 'hash_hoang', '0987654321', N'Hải Phòng');

INSERT INTO destinations(name, region, description) VALUES
(N'Sa Pa', N'Miền Bắc', N'Thị trấn trong mây, ruộng bậc thang'),
(N'Đà Nẵng', N'Miền Trung', N'Thành phố biển, Bà Nà Hills'),
(N'Phú Quốc', N'Miền Nam', N'Đảo ngọc với biển xanh cát trắng');

INSERT INTO categories(name, description) VALUES
(N'Tour núi', N'Khám phá núi rừng'),
(N'Tour biển', N'Nghỉ dưỡng biển đảo'),
(N'Tour văn hóa', N'Tham quan di tích, làng nghề');

INSERT INTO tours(category_id, destination_id, tour_name, duration_days, duration_nights,
                  departure_place, adult_price, child_price, description, inclusions, cancellation_policy) VALUES
(1, 1, N'Sa Pa - Fansipan 3N2Đ', 3, 2, N'Hà Nội', 3500000, 2500000,
    N'Chinh phục Fansipan, thăm bản Cát Cát', N'Xe, khách sạn, ăn sáng, vé cáp treo', N'Hủy trước 7 ngày hoàn 100%'),
(2, 2, N'Đà Nẵng - Hội An 4N3Đ', 4, 3, N'Hà Nội', 5200000, 3600000,
    N'Bà Nà Hills, phố cổ Hội An', N'Vé máy bay, khách sạn 4 sao, vé tham quan', N'Hủy trước 10 ngày hoàn 80%'),
(2, 3, N'Phú Quốc nghỉ dưỡng 3N2Đ', 3, 2, N'TP. Hồ Chí Minh', 4800000, 3200000,
    N'Nghỉ dưỡng, lặn ngắm san hô', N'Vé máy bay, resort, ăn sáng', N'Hủy trước 7 ngày hoàn 70%');

INSERT INTO tour_itineraries(tour_id, day_number, title, content) VALUES
(1, 1, N'Hà Nội - Sa Pa', N'Khởi hành, nhận phòng, thăm bản Cát Cát'),
(1, 2, N'Chinh phục Fansipan', N'Đi cáp treo lên đỉnh Fansipan'),
(1, 3, N'Sa Pa - Hà Nội', N'Mua sắm đặc sản, trở về Hà Nội');

INSERT INTO tour_images(tour_id, image_url, is_primary) VALUES
(1, '/images/sapa1.jpg', 1), (2, '/images/danang1.jpg', 1), (3, '/images/phuquoc1.jpg', 1);

INSERT INTO departures(tour_id, start_date, end_date, total_seats, available_seats) VALUES
(1, '2026-11-05', '2026-11-07', 30, 30),
(1, '2026-11-20', '2026-11-22', 30, 30),
(2, '2026-12-01', '2026-12-04', 25, 25),
(3, '2026-12-10', '2026-12-12', 20, 20);

INSERT INTO promotions(code, discount_type, discount_value, min_order_value, start_date, end_date, usage_limit) VALUES
('WELCOME10', 'PERCENT', 10, 2000000, '2026-10-01', '2026-12-31', 100),
('GIAM500K', 'AMOUNT', 500000, 5000000, '2026-10-01', '2026-12-31', 50);

-- Mot don dat mau: 2 nguoi lon + 1 tre em, tour Sa Pa, ap dung WELCOME10
INSERT INTO bookings(booking_code, user_id, departure_id, promotion_id, adult_count, child_count,
                     subtotal, discount_amount, total_amount, contact_name, contact_phone)
VALUES ('BK20261012001', 3, 1, 1, 2, 1, 9500000, 950000, 8550000, N'Trần Thị Lan', '0912345678');

INSERT INTO booking_passengers(booking_id, full_name, gender, birth_date, passenger_type) VALUES
(1, N'Trần Thị Lan', N'Nữ', '1995-03-10', 'ADULT'),
(1, N'Phạm Quốc Bảo', N'Nam', '1993-07-22', 'ADULT'),
(1, N'Phạm Gia Hân', N'Nữ', '2018-05-15', 'CHILD');

INSERT INTO payments(booking_id, method, amount, transaction_code, status, paid_at)
VALUES (1, 'VNPAY', 8550000, 'VNP123456789', 'SUCCESS', GETDATE());

UPDATE bookings SET status = 'PAID' WHERE booking_id = 1;
UPDATE departures SET available_seats = available_seats - 3 WHERE departure_id = 1;
UPDATE promotions SET used_count = used_count + 1 WHERE promotion_id = 1;
GO

/* ---------- 3. CAU TRUY VAN KIEM TRA ---------- */

-- Danh sach tour kem diem den, danh muc
SELECT t.tour_name, d.name AS diem_den, c.name AS danh_muc, t.adult_price
FROM tours t
JOIN destinations d ON d.destination_id = t.destination_id
JOIN categories c   ON c.category_id = t.category_id;

-- Lich khoi hanh con cho
SELECT t.tour_name, dp.start_date, dp.available_seats
FROM departures dp JOIN tours t ON t.tour_id = dp.tour_id
WHERE dp.status = 'OPEN' AND dp.available_seats > 0 AND dp.start_date >= CAST(GETDATE() AS DATE)
ORDER BY dp.start_date;

-- Doanh thu theo tour (chi tinh thanh toan thanh cong)
SELECT t.tour_name, COUNT(DISTINCT b.booking_id) AS so_don, SUM(p.amount) AS doanh_thu
FROM payments p
JOIN bookings b   ON b.booking_id = p.booking_id
JOIN departures d ON d.departure_id = b.departure_id
JOIN tours t      ON t.tour_id = d.tour_id
WHERE p.status = 'SUCCESS'
GROUP BY t.tour_name;
GO
