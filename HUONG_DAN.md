# HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY WEBSITE DU LỊCH (Laravel + SQL Server)

Tài khoản mẫu (sau khi chạy file 02_bo_sung_laravel.sql), mật khẩu đều là **123456**:
- Quản trị: admin@dulich.vn
- Nhân viên: staff@dulich.vn
- Khách hàng: lan@gmail.com, hoang@gmail.com

## A. Chuẩn bị máy (làm một lần)

1. **Kiểm tra PHP của XAMPP:** mở CMD, gõ `C:\xampp\php\php.exe -v`.
   - PHP từ 8.2 trở lên: dùng Laravel mới nhất.
   - PHP 8.1: dùng `laravel/laravel:^10.0` ở bước B.
   - **PHP 8.0 (như máy của bạn, 8.0.30 ZTS x64): dùng `laravel/laravel:^9.0` ở bước B, driver bản 5.11.1 (file có `_80_ts_x64`), và nên cài ODBC Driver 17 thay vì 18.**
   - Thấp hơn: cài lại XAMPP bản mới.
   Nên thêm `C:\xampp\php` vào biến môi trường PATH để gõ `php` trực tiếp.
2. **Cài Composer:** tải tại getcomposer.org, khi cài chọn đúng `php.exe` của XAMPP.
3. **Cài ODBC Driver for SQL Server:** bản 18 cho PHP 8.2 trở lên; với Laravel 9 trên PHP 8.0 nên cài **bản 17** (mặc định không bắt buộc mã hóa nên đỡ lỗi chứng chỉ).
4. **Cài PHP driver cho SQL Server** (rất quan trọng, thiếu bước này sẽ báo lỗi "could not find driver"):
   - Tải **Microsoft Drivers for PHP for SQL Server** (kho github.com/microsoft/msphpsql, mục Releases), giải nén.
   - Với PHP 8.0 phải tải **bản 5.11.1** (bản 5.12 trở đi đã bỏ hỗ trợ PHP 8.0), file cần: `php_pdo_sqlsrv_80_ts_x64.dll` và `php_sqlsrv_80_ts_x64.dll`.
   - Chọn 2 file đúng phiên bản PHP, **TS**, **x64** (ví dụ PHP 8.2: `php_pdo_sqlsrv_82_ts_x64.dll` và `php_sqlsrv_82_ts_x64.dll`), chép vào `C:\xampp\php\ext`.
   - Mở `C:\xampp\php\php.ini`, thêm 2 dòng cuối phần extension:
     ```
     extension=php_pdo_sqlsrv_82_ts_x64.dll
     extension=php_sqlsrv_82_ts_x64.dll
     ```
   - Kiểm tra bằng `php -m`, phải thấy `pdo_sqlsrv` và `sqlsrv`. (Nếu chạy bằng Apache của XAMPP thì restart Apache. Hướng dẫn này dùng `php artisan serve` nên không cần Apache.)
5. **Tạo tài khoản đăng nhập SQL cho Laravel** trong SSMS:
   - Chuột phải tên server > Properties > Security > chọn **SQL Server and Windows Authentication mode** > OK, rồi chuột phải server > **Restart**.
   - Mở New Query và chạy:
     ```sql
     CREATE LOGIN laravel_user WITH PASSWORD = 'Laravel@123', CHECK_POLICY = OFF;
     USE DuLichDB;
     CREATE USER laravel_user FOR LOGIN laravel_user;
     ALTER ROLE db_owner ADD MEMBER laravel_user;
     ```
   - Bật dịch vụ **SQL Server Browser** (Windows Services) để dùng tên `localhost\SQLEXPRESS`.

## B. Tạo project và chép mã nguồn

1. Trong CMD, vào thư mục muốn để project (ví dụ `C:\xampp\htdocs`) và chạy:
   ```
   composer create-project laravel/laravel dulich
   ```
   (PHP 8.1 thì dùng `laravel/laravel:^10.0`, PHP 8.0 thì dùng `laravel/laravel:^9.0`)
2. Giải nén file zip mã nguồn, **chép đè** các thư mục `app`, `resources`, `routes` vào thư mục `dulich` (chọn Replace / Merge).
3. Mở `dulich\.env`, sửa theo file `env_mau.txt`.
4. Trong SSMS, mở database DuLichDB và chạy `database_sql/02_bo_sung_laravel.sql`.
   (Trước đó phải chạy `DuLichDB.sql` để có 14 bảng và dữ liệu mẫu.)
5. Trong CMD, vào thư mục `dulich` và chạy:
   ```
   php artisan config:clear
   php artisan serve
   ```
6. Mở trình duyệt: http://127.0.0.1:8000

> **Không chạy `php artisan migrate`.** Các bảng đã được tạo bằng file SQL, migrate sẽ tạo thêm bảng thừa của Laravel.

## C. Các chức năng đã có

| Nhóm | Chức năng |
|---|---|
| Trang công khai | Trang chủ (hero, tìm kiếm, lọc theo điểm đến, loại tour, giá, phân trang), chi tiết tour (lịch trình, lịch khởi hành, đánh giá), trang Liên hệ (lưu vào bảng contacts) |
| Tài khoản | Đăng ký, đăng nhập, đăng xuất |
| Khách hàng | Đặt tour (chọn số người, nhập hành khách, mã giảm giá, phương thức thanh toán), xem đơn của tôi, hủy đơn |
| Quản trị | Tổng quan và doanh thu, **thêm / sửa / xóa tour**, thêm / xóa lịch khởi hành, xem đơn đặt, đổi trạng thái đơn (tự cập nhật thanh toán và số chỗ), xem và xóa liên hệ |

Mã giảm giá để thử: `WELCOME10` (giảm 10%, đơn từ 2.000.000 đ), `GIAM500K` (giảm 500.000 đ, đơn từ 5.000.000 đ).

## D. Lỗi thường gặp

| Thông báo | Cách xử lý |
|---|---|
| `could not find driver` | Chưa bật extension pdo_sqlsrv, xem lại mục A4 (đúng phiên bản PHP, TS, x64) |
| `Login failed for user` | Sai tài khoản / mật khẩu trong .env, hoặc chưa bật chế độ SQL Server and Windows Authentication (mục A5) |
| `A network-related or instance-specific error` | Bật SQL Server Browser, hoặc bật TCP/IP trong SQL Server Configuration Manager rồi dùng `DB_HOST=127.0.0.1` và `DB_PORT=1433` |
| `SSL Provider: certificate verify failed` | Thêm `DB_TRUST_SERVER_CERTIFICATE=true` và `DB_ENCRYPT=no` vào .env, chạy `php artisan config:clear` |
| Đăng nhập báo sai mật khẩu | Chưa chạy file 02_bo_sung_laravel.sql |
| Sửa .env mà không ăn | Chạy `php artisan config:clear` |

## E. Nộp bài

- **Cách 1 (GitHub):** tạo repo, trong thư mục `dulich` chạy `git init`, `git add .`, `git commit -m "Website du lich"`, `git remote add origin <link repo>`, `git push -u origin main`. File `.env` đã được Laravel bỏ qua trong `.gitignore`. Nên chép kèm file `DuLichDB.sql` và `02_bo_sung_laravel.sql` vào repo để thầy cô tạo lại CSDL.
- **Cách 2 (Word + Google Drive):** chụp màn hình các trang: trang chủ, chi tiết tour, đăng ký/đăng nhập, đặt tour, đơn của tôi, liên hệ, admin quản lý tour (thêm/sửa/xóa), admin đơn đặt; dán vào Word kèm chú thích rồi upload lên Google Drive.
