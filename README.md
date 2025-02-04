
# GSMPay Backend API

This project is a **Laravel 11** based backend for GSMPay, implementing features such as JWT-based authentication, profile image upload, post management with view tracking, and user rankings based on post views. All functionalities are tested with unit and integration tests.

---

## Features

- **User Registration & Login** (JWT Token based)
- **Profile Image Upload**
- **Post Creation & View Counting**
- **User Ranking by Post Views**
- **Pagination Support**
- **Swagger API Documentation**

---

## Setup Instructions

1. **Clone the Repository**

```bash
git clone https://github.com/ar4min/gsmpay.git
cd gsmpay
```

2. **Install Dependencies**

```bash
composer install
```

3. **Configure Environment**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Run Migrations & Seeders**

```bash
php artisan migrate --seed
```

5. **Generate JWT Secret**

```bash
php artisan jwt:secret
```

6. **Run the Server**

```bash
php artisan serve
```

---

## API Endpoints

### **Authentication**

- **Login:** `POST /api/login`
- **Profile:** `GET /api/profile` (requires JWT)

### **Profile Management**

- **Upload Profile Image:** `POST /api/profile/image`

### **Posts**

- **Create Post:** `POST /api/posts`
- **View Post:** `GET /api/posts/{id}` (increments view count)
- **Get User's Posts:** `GET /api/posts`

### **User Ranking**

- **Users by Views:** `GET /api/users-by-views?page=1&per_page=10`

---

## Running Tests

```bash
php artisan test
```

- **Unit Tests:** Test individual functionalities.
- **Integration Tests:** Verify interactions between different components.

---

## API Documentation (Swagger)

Generate API documentation:

```bash
php artisan l5-swagger:generate
```

View documentation:

```
http://localhost:8000/api/documentation
```

---

