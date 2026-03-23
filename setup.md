# SastaPrint App Setup Instructions

## 1. Environment Setup
1. Ensure you have a local server running (like XAMPP, WAMP, or plain PHP Built-in server).
2. Ensure MySQL is running.

## 2. Database Setup
1. Open phpMyAdmin or your preferred database manager.
2. Import the `database.sql` file provided in the root directory.
3. This will create the `print_service` database and populate it with sample products, categories, and an admin user.
    - **Admin Login:** admin@sastaprint.com
    - **Admin Password:** password

## 3. Configuration
1. Open `config/config.php`.
2. Update the `BASE_URL` constant to match your local web server's address for this project.
   - For example: `define('BASE_URL', 'http://localhost/sastaprint/public');` or whatever folder name you placed it in.
3. Check DB credentials in `config/config.php` (DB_HOST, DB_USER, DB_PASS).

## 4. Running the App
Point your browser to the `public/` directory via your local server address. Example: `http://localhost/sasta print/public`

## Technical Details:
- The site uses **Core PHP**, completely object-oriented MVC architecture.
- Frontend includes responsive pure CSS and ES6 Javascript.
- Custom product design is implemented using the `fabric.js` canvas library.
- Routing is handled via `app/Router.php`, processing SEO-friendly URLs.
