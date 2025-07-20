# 🏟️ Stadium Booking System

A comprehensive Laravel-based Stadium Booking API system that allows users to book football pitches in stadiums with a complete admin interface and public booking website.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-26%20Tests-green.svg)](#testing)

## 🚀 **Features**

### **Core Functionality**
- ⚽ **Multi-Stadium Support** - Multiple stadiums with multiple pitches each
- 🕐 **Flexible Time Slots** - 60-minute and 90-minute booking slots
- 📅 **Dynamic Operating Hours** - Each pitch has configurable operating hours and days
- 🚫 **Overbooking Prevention** - Real-time availability checking
- 💰 **Dynamic Pricing** - Different rates for 60 and 90-minute slots
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile

### **Admin Dashboard**
- 🏢 **Stadium Management** - Create, edit, delete stadiums
- ⚽ **Pitch Management** - Manage pitches with custom operating hours
- 📊 **Booking Overview** - View and manage all bookings
- 🔐 **Secure Admin Access** - Protected admin area with authentication

### **Public Website**
- 🏠 **Stadium Listings** - Browse available stadiums
- 📅 **Real-time Availability** - See available time slots
- 💳 **Easy Booking** - Simple booking process
- 📞 **User Management** - Track bookings by email

### **RESTful API**
- 🔌 **Complete API** - Full CRUD operations
- 📋 **Slot Management** - List available slots
- 🎯 **Booking System** - Create and manage bookings
- ✅ **Comprehensive Testing** - 26 unit tests covering all scenarios

---

## 📋 **Table of Contents**

1. [Installation](#installation)
2. [Database Setup & Seeding](#database-setup--seeding)
3. [Admin Dashboard](#admin-dashboard)
4. [Public Website](#public-website)
5. [API Documentation](#api-documentation)
6. [Testing](#testing)
7. [Configuration](#configuration)
8. [Usage Examples](#usage-examples)

---

## 🛠️ **Installation**

### **Prerequisites**
- PHP 8.2 or higher
- Composer
- Laravel 11.x
- SQLite/MySQL/PostgreSQL

### **Setup Steps**

1. **Clone the repository**
```bash
git clone <repository-url>
cd booking-stadium
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database in `.env`**
```env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking_stadium
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Start the server**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## 🗄️ **Database Setup & Seeding**

### **Database Structure**

The system uses the following main tables:
- **`stadiums`** - Stadium information and details
- **`pitches`** - Individual pitches within stadiums
- **`bookings`** - User bookings and reservations
- **`users`** - Admin users (for dashboard access)

### **Seeding Data**

The seeders create comprehensive sample data:

```bash
php artisan db:seed
```

**What gets seeded:**

1. **Admin User**
   - Email: `safa@admin.com`
   - Password: `P@ssword12`

2. **Stadiums** (3 stadiums)
   - Al Wasl Sports Club
   - Dubai Sports City
   - Emirates Golf Club

3. **Pitches** (9 pitches total - 3 per stadium)
   - **Pitch 1**: 60-minute slots, operates 06:00-22:00
   - **Pitch 2**: 90-minute slots, operates 08:00-23:00  
   - **Pitch 3**: 60-minute slots, operates 10:00-24:00

4. **Sample Data Features**
   - Different operating hours per pitch
   - Alternating slot types (60 vs 90 minutes)
   - Various pricing structures
   - Different amenities and capacities

## 🏢 **Admin Dashboard**

### **Accessing the Admin Panel**

1. Navigate to: `http://localhost:8000/admin/login`
2. Login with:
   - **Email**: `safa@admin.com`
   - **Password**: `P@ssword12`

### **Admin Features**

#### **🏟️ Stadium Management** (`/admin/stadiums`)
- **Create New Stadiums**
  - Basic information (name, address, capacity)
  - Contact details (phone, email)
  - GPS coordinates (latitude, longitude)
  - Status management (Active/Inactive/Maintenance)

- **Edit Stadium Details**
  - Update all stadium information
  - Change status and availability
  - Manage contact information

- **View Stadium Statistics**
  - See number of pitches per stadium
  - View booking statistics
  - Monitor stadium performance

#### **⚽ Pitch Management** (`/admin/pitches`)
- **Create Custom Pitches**
  - Select parent stadium
  - Set pitch type (Football, Basketball, Tennis, etc.)
  - Configure surface (Grass, Artificial, Clay, Concrete)
  - **Slot Configuration**: Choose 60-minute OR 90-minute slots
  - **Operating Hours**: Set custom start/end times
  - **Operating Days**: Select which days the pitch operates
  - **Pricing**: Different rates for 60 and 90-minute slots
  - Capacity and amenities

- **Advanced Features**
  - Real-time slot preview
  - Operating schedule validation
  - Conflict detection

#### **📅 Bookings Management** (`/admin/bookings`)
- **View All Bookings**
  - Filter by date, stadium, or pitch
  - See booking details and user information
  - Monitor booking status

- **Booking Analytics**
  - Daily/weekly/monthly statistics
  - Revenue tracking
  - Popular time slots analysis

### **Admin Sidebar Navigation**
- **Responsive Design**: Collapses on mobile devices
- **Quick Access**: One-click navigation between sections
- **User-Friendly**: Smooth animations and intuitive layout

---

## 🌐 **Public Website**

### **User Flow**

#### **1. Browse Stadiums** (`/`)
- View all available stadiums
- See stadium details and available pitches
- Browse by location or amenities

#### **2. Select Stadium** (`/stadiums/{id}`)
- View detailed stadium information
- See all available pitches
- Check real-time slot availability

#### **3. Choose Date & Time**
- Interactive date picker
- Real-time slot availability
- Visual time slot selection
- See pricing for each slot

#### **4. Make Booking** (`/booking/form`)
- Simple booking form
- Required information:
  - Full name
  - Email address
  - Phone number
  - Additional notes (optional)

#### **5. Booking Confirmation** (`/booking/success/{id}`)
- Booking details and confirmation
- Booking reference number
- Stadium and pitch information

#### **6. Manage Bookings** (`/my-bookings`)
- Enter email to view bookings
- See upcoming reservations
- Booking history

### **Smart Features**
- **Real-time Availability**: Slots update automatically
- **Slot Type Enforcement**: Only shows available slot durations
- **Operating Hours**: Respects pitch schedules
- **Conflict Prevention**: No double bookings possible

---

## 🔌 **API Documentation**

### **Base URL**
```
http://localhost:8000/api
```

### **Authentication**
Currently, the API is open for public use. Admin endpoints require session authentication.

---

### **🏟️ Stadium Endpoints**

#### **Get All Stadiums**
```http
GET /api/stadiums
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Al Wasl Sports Club",
            "address": "Al Wasl Road, Dubai",
            "capacity": 45000,
            "status": "active",
            "pitches": [...]
        }
    ],
    "message": "Stadiums retrieved successfully"
}
```

#### **Get Stadium Details**
```http
GET /api/stadiums/{id}
```

### **📅 Booking Endpoints**

#### **Create New Booking**
```http
POST /api/bookings
Content-Type: application/json
```

**Request Body:**
```json
{
    "pitch_id": 1,
    "user_name": "John Doe",
    "user_email": "john@example.com",
    "user_phone": "+971501234567",
    "booking_date": "2025-07-21",
    "start_time": "10:00",
    "end_time": "11:00",
    "duration_minutes": 60,
    "notes": "Birthday party booking"
}
```

**Response (Success):**
```json
{
    "success": true,
    "data": {
        "id": 123,
        "pitch_id": 1,
        "user_name": "John Doe",
        "booking_date": "2025-07-21",
        "start_time": "10:00",
        "end_time": "11:00",
        "total_price": "170.00",
        "status": "confirmed"
    },
    "message": "Booking created successfully"
}
```

**Response (Validation Error):**
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "slot": ["This time slot is already booked."]
    }
}
```

#### **Get Bookings for Date**
```http
GET /api/bookings?date=2025-07-21
```

### **🔍 Pitch Endpoints**

#### **Get Available Slots for Specific Pitch**
```http
GET /api/pitches/{id}/available-slots?date=2025-07-21
```

---

### **⚠️ Error Responses**

**404 Not Found:**
```json
{
    "success": false,
    "message": "Stadium not found"
}
```

**422 Validation Error:**
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "pitch_id": ["The selected pitch does not exist."],
        "booking_date": ["Booking date must be today or in the future."]
    }
}
```

---

## 🧪 **Testing**

### **Running Tests**

```bash
# Run all tests
php artisan test

# Run specific test files
php artisan test tests/Feature/Api/StadiumSlotApiTest.php
php artisan test tests/Feature/Api/BookingApiTest.php

# Run tests with coverage
php artisan test --coverage
```

### **Test Coverage**

We have **26 comprehensive tests** covering:

#### **Stadium Slot API Tests** (10 tests)
- ✅ Returns available slots for stadium
- ✅ Returns only 60-minute slots for 60-minute pitch
- ✅ Returns only 90-minute slots for 90-minute pitch
- ✅ Excludes already booked slots
- ✅ Respects operating hours and days
- ✅ Handles invalid requests (404, 422)

#### **Booking API Tests** (16 tests)
- ✅ Creates valid bookings
- ✅ Prevents overbooking same slot
- ✅ Prevents overlapping bookings
- ✅ Validates required fields
- ✅ Validates slot type matching
- ✅ Validates operating hours
- ✅ Validates operating days
- ✅ Calculates correct pricing
- ✅ Prevents booking unavailable pitches


#### **Testing Business Rules**
```bash
# Test slot type validation
php artisan test --filter="it_validates_duration_matches_slot_type"

# Test operating hours validation
php artisan test --filter="it_validates_booking_within_operating_hours"
```


## ⚙️ **Configuration**

### **Pitch Configuration**

Each pitch can be configured with:

#### **Operating Hours**
```php
'operating_start_time' => '08:00:00',
'operating_end_time' => '22:00:00'
```

#### **Operating Days**
```php
'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']
```

#### **Slot Types**
```php
'slot_type' => '60'  // or '90'
```

### **Environment Variables**

Key configuration options in `.env`:

```env
# Application
APP_NAME="Stadium Booking System"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Admin Credentials (for seeding)
ADMIN_EMAIL=safa@admin.com
ADMIN_PASSWORD=P@ssword12
```

---

## 💡 **Usage Examples**

### **Scenario 1: Creating a Stadium with Custom Pitches**

1. **Login to Admin Dashboard**
   ```
   http://localhost:8000/admin/login
   ```

2. **Create Stadium**
   - Navigate to Stadiums → Create
   - Fill in basic information
   - Set status as "Active"

3. **Add Pitches with Different Configurations**
   - **Pitch A**: 60-minute slots, 06:00-22:00, all days
   - **Pitch B**: 90-minute slots, 08:00-23:00, weekdays only
   - **Pitch C**: 60-minute slots, 10:00-18:00, weekends only

### **Scenario 2: Mobile App Integration**

```bash
# Get available slots
curl "http://localhost:8000/api/stadiums/1/available-slots?date=2025-07-21"

# Create booking
curl -X POST "http://localhost:8000/api/bookings" \
  -H "Content-Type: application/json" \
  -d '{
    "pitch_id": 1,
    "user_name": "Mobile User",
    "user_email": "user@mobile.app",
    "user_phone": "+971501234567",
    "booking_date": "2025-07-21",
    "start_time": "14:00",
    "end_time": "15:00",
    "duration_minutes": 60
  }'
```

---

## 🔧 **Troubleshooting**

### **Common Issues**

#### **Database Issues**
```bash
# Reset database
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
```

#### **Permission Issues**
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

#### **Testing Issues**
```bash
# Clear test cache
php artisan config:clear --env=testing
php artisan cache:clear --env=testing
```

### **Debug Mode**

Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

---

## 📝 **Contributing**

1. Fork the repository
2. Create a feature branch
3. Add tests for new features
4. Ensure all tests pass
5. Submit a pull request

---

## 📄 **License**

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 📞 **Support**

For support and questions:
- Create an issue in the repository
- Email: support@stadiumbooking.com

---

**Built with ❤️ using Laravel 11 and modern web technologies.**
