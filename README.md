# 🏨 Hotel Theme - Booking Platform

A modern hotel booking platform built with **Laravel 12**, **Livewire 3**, **Alpine.js**, and **Tailwind CSS**.  
This project simulates a real-world hotel site with admin management, reservations, and more.

---

## 🚀 Features

✅ Public pages
- Home page with hero section
- Room listing with advanced filtering and lazy load
- Room detail with booking modal
- Contact page with honeypot anti-spam
- Gallery with random images

✅ Reservations
- Date picker with disabled booked ranges
- Honeypot and throttle protection
- Email confirmation to admin and customer
- Form validation with custom rules

✅ Admin area
- Authenticated dashboard
- CRUD for rooms with images gallery (Livewire component)
- CRUD for reservations with calendar-disabled dates
- Filtering, sorting, pagination

✅ Technical
- Laravel 12
- Livewire 3
- Alpine.js
- Tailwind V4
- Flatpickr for date range
- CropperJS for crop rooms images
- GliderJS for gallery
- Queue-based mail sending (Mailables + Queued jobs)
- Custom validation rules
- Eager loading optimization
- Honeypot anti-spam
- Cache invalidation with Observers

---

## 🛠️ Tech stack

- **Backend**: Laravel 12
- **Frontend**: Blade, Alpine.js, Livewire 3
- **Styling**: Tailwind CSS
- **Date picker**: Flatpickr
- **Cropper**: CropperJS
- **Gallery**: GliderJS
- **Select**: TomSelect
- **Table**: livewire-powergrid
- **Database**: MySQL (SQLite for tests)
- **Mails**: Laravel Mailable, queueable
- **Queue**: Database driver, cron-compatible

---

## ⚙️ Setup locally

```bash
git clone https://github.com/Simone4e/HotelTheme.git
cd HotelTheme
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
