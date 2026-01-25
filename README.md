# VisionSphere – Explore your world of ideas and stories.

A complete, professional blog platform built with Laravel 12, featuring a beautiful admin panel and responsive frontend.

## Features

### Admin Panel
- **Dashboard**: Overview with statistics and recent activity
- **Post Management**: Create, edit, publish, and manage blog posts with TinyMCE editor
- **Category Management**: Organize posts with categories
- **Tag Management**: Add tags to posts for better organization
- **Comment Management**: Moderate and approve comments
- **User Management**: Manage users and their roles
- **Page Management**: Create static pages (About, Privacy Policy, etc.)
- **Slider Management**: Manage homepage sliders
- **Gallery Management**: Upload and manage photo galleries
- **Settings**: Comprehensive site settings (general, brand, contact, social)
- **Profile Management**: User profile with avatar and social links

### Frontend
- **Responsive Design**: Mobile-first design with Tailwind CSS
- **Homepage**: Featured posts, sliders, and latest posts
- **Blog Posts**: Full post view with comments, tags, and related posts
- **Categories & Tags**: Browse posts by category or tag
- **Search**: Full-text search functionality
- **Gallery**: Photo gallery with lightbox
- **Contact Form**: Contact form with email sending
- **Static Pages**: About, Privacy Policy, Terms of Service

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd alisha
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   - Create a MySQL database
   - Update `.env` with database credentials
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Start the Server**
   ```bash
   php artisan serve
   ```

## Usage

### Admin Access
- Visit `/admin/login`
- Default admin credentials: `test@example.com` / `password`

### Frontend Access
- Homepage: `/`
- Blog: `/blog`
- Categories: `/category/{slug}`
- Tags: `/tag/{slug}`
- Search: `/blog/search?q=query`
- Gallery: `/gallery`
- Contact: `/contact`
- About: `/about`

## Technologies Used

- **Laravel 12**: PHP framework
- **Tailwind CSS 4.x**: Utility-first CSS framework
- **Vite 7.x**: Build tool and dev server
- **TinyMCE 6**: Rich text editor
- **MySQL**: Database
- **Font Awesome**: Icons

## Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   └── BlogController.php  # Frontend controller
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── public/
│   ├── build/             # Compiled assets
│   └── storage/           # Uploaded files
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript
│   └── views/             # Blade templates
│       ├── admin/         # Admin views
│       ├── blog/          # Frontend views
│       └── layouts/       # Layout templates
└── routes/
    └── web.php            # Route definitions
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is licensed under the MIT License.

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
