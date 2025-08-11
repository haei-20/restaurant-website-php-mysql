# Nhà Hàng Vincent - Website PHP Thuần

## Giới thiệu

PHP thuần mà không sử dụng JavaScript

## Tính năng chính

- **Xử lý form liên hệ**: Được xử lý bằng Ajax
- **Tooltip**: Sử dụng CSS thay vì JavaScript với các hàm helper PHP
- **Menu động**: Sử dụng PHP để quản lý và hiển thị menu
- **Hệ thống đặt món**: Tích hợp trong trang với xử lý PHP
- **Quản lý trạng thái**: Sử dụng session PHP để lưu trạng thái thay vì JavaScript

## Hướng dẫn cài đặt

1. Tải mã nguồn về máy chủ web
2. Tạo cơ sở dữ liệu và nhập file SQL
3. Cấu hình kết nối trong file connect.php
4. Truy cập trang web qua trình duyệt

## Yêu cầu hệ thống

- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Apache hoặc Nginx

## Cấu trúc thư mục

- **Includes/**: Chứa các file PHP hỗ trợ và templates
  - **functions/**: Các hàm tiện ích
  - **templates/**: Các file template (header, footer, navbar)
  - **php_replacements.php**: Các hàm thay thế JavaScript
  - **tooltip_helper.php**: Hỗ trợ tooltip thuần PHP
- **Design/**: Chứa CSS và tài nguyên giao diện
- **admin/**: Khu vực quản trị

## Hướng dẫn sử dụng

Trang web hoạt động hoàn toàn với PHP, không cần bật JavaScript trong trình duyệt. Các chức năng tương tác được thực hiện thông qua form submit và xử lý PHP. 
