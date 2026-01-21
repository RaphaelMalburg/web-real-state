# EstatePro Agency - Laravel Version

This is the enhanced Laravel 11 version of the Real Estate Agency website, built for use with Laravel Herd (PHP 8.4).

## Features

- **Laravel 11**: Using the latest features and structure.
- **SQLite Database**: Auto-migrated and seeded.
- **Enhanced Data**: Properties now have addresses, bedroom/bathroom counts, and square footage.
- **Eloquent Models**: Clean database interactions with `Property`, `Booking`, and `Inquiry` models.
- **Blade Templating**: Clean layout and view management.
- **Admin Dashboard**: Full CRUD for properties, plus overview of bookings and contact messages.
- **Local Assets**: All property images are served from the local `public` folder.

## How to Run

Since you are using **Laravel Herd**:

1.  Make sure this folder (`real_estate_laravel`) is in your Herd directory (or linked to it).
2.  Open your browser and visit `http://real-estate-laravel.test` (or whatever name Herd assigned).
3.  **Alternatively**, you can run it via the terminal:
    ```bash
    php artisan serve
    ```
    Then visit `http://127.0.0.1:8000`.

## Admin Access

You can access the admin dashboard at `/admin`.
The database is already seeded with:
- **Email**: `admin@estatepro.com`
- **Password**: `password`

(Note: Authentication is currently open for simplicity in this assignment, but a User seeder is included if you want to add `auth` middleware later).

## Project Structure

- `app/Models/`: `Property`, `Booking`, `Inquiry`
- `app/Http/Controllers/`: Controllers for logic.
- `database/migrations/`: Database schema.
- `database/seeders/`: Initial data population.
- `resources/views/`: Blade templates (HTML/UI).
- `public/assets/`: CSS, JS, and Images.
# web-real-state
