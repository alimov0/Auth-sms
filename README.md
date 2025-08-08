 <p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# 📱 Auth-SMS - Laravel Based Authentication via SMS

## 🔧 Technologies & Architecture

- ✅ Laravel 12+
- ✅ Repository Pattern
- ✅ Service Layer
- ✅ DTO (Data Transfer Object)
- ✅ SMS Code Notification (Nexmo / Twilio / Custom)
- ✅ API Response standardization via Resources
- ✅ Caching & Rate Limiting
- ✅ Auto Cleanup Job for Unverified Users

---

## 📌 AUTH MODULE: SMS orqali ro‘yxatdan o‘tish va tizimga kirish

### ✅ 1. Ro‘yxatdan o‘tish (Register)

- Foydalanuvchi quyidagi maydonlar bilan ro‘yxatdan o‘tadi:
  - `name` (ism)
  - `last_name` (familiya)
  - `phone` (telefon raqami)
  - `avatar` (ixtiyoriy rasm)
- Ro’yxatdan o‘tgach, foydalanuvchiga 5 xonali SMS kod yuboriladi.
- Ushbu kod:
  - Faqat 1 daqiqa amal qiladi.
  - 1 daqiqadan so‘ng yaroqsiz bo‘ladi.
- Foydalanuvchi kodni muvaffaqiyatli tasdiqlaganidan so‘nggina yakuniy ro‘yxatdan o‘tadi (`is_verified = true`).

---

### 🔐 2. Kirish (Login)

- Foydalanuvchi faqat `telefon raqami` orqali login qilishi mumkin.
- Telefon raqami yuborilgach, unga 5 xonali SMS kod yuboriladi.
- Kod 1 daqiqa amal qiladi.
- Kod to‘g‘ri bo‘lsa — login muvaffaqiyatli yakunlanadi.
- Telefon raqami tasdiqlanmagan foydalanuvchi login qila olmaydi.

---

### 🔁 3. SMS Kodni Qayta Yuborish

- Har bir telefon raqamiga 1 daqiqada faqat 1 ta kod yuboriladi.
- Oldingi yuborilgan kod ham 1 daqiqa amal qiladi.
- Yangi kodni yuborish uchun vaqti tugashi kerak.

---

### ☎️ 4. Telefon Raqamini Almashtirish

- Tizimga kirgan foydalanuvchi o‘zining telefon raqamini yangilashi mumkin.
- Yangi raqam:
  - Bazada mavjud bo‘lmasligi kerak.
  - Unga ham 5 xonali SMS kodi yuboriladi (1 daqiqa amal qiladi).
  - Kod tasdiqlansa — telefon raqami yangilanadi.

---

### 🧹 5. Avtomatik Tozalash (Clean-up)

- 3 kun ichida telefon raqamini tasdiqlamagan foydalanuvchilar avtomatik o‘chiriladi.
  - Bu ishlar background job yoki cron orqali bajariladi.

---

## 🧠 Layered Architecture
📦 App
├── 📁 DTO # Data transfer objects
├── 📁 Interfaces
│ ├── 📁 Repositories # Repository interfaces
│ └── 📁 Services # Service interfaces
├── 📁 Repositories # Repository implementatsiyalari
├── 📁 Services # Service implementatsiyalari
├── 📁 Notifications # SMS yuborish xabarnomalari
├── 📁 Http
│ └── 📁 Controllers # API Controllerlar


---

## ⚙️ Requirements

- PHP >= 8.1
- Laravel >= 12
- Redis / Memcached (cache uchun)
- Nexmo / Twilio (SMS jo‘natish uchun)

---

## 🚀 Installation

```bash
git clone https://github.com/yourusername/auth-sms.git
cd auth-sms
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
