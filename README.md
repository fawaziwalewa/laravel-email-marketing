# Laravel Email Marketing Project

This Laravel Email Marketing Project is designed to help you manage your email marketing campaigns. It provides a dashboard where you can view analytics for subscribers, sent emails, failed emails, and open emails. You can also import subscribers from a CSV file, use reusable email templates, and set up a cron job to run every minute for using email scheduling feature.

## Features

- Dashboard Analytics for subscribers, sent emails, failed emails, and open emails
- Import subscribers from a CSV file
- Reusable email templates
- Installation guide for Filament Admin user setup, database setup, and cron job setup

## Installation

To install this project, you need to follow these steps:

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL

### Steps

1. Clone the repository to your local machine
   ```
   git clone https://github.com/fawaziwalewa/laravel-email-marketing.git
   ```
2. Install the dependencies
   ```
   composer install
   ```
3. Create a copy of the `.env.example` file and rename it to `.env`
   ```
   cp .env.example .env
   ```
4. Generate a new application key
   ```
   php artisan key:generate
   ```
5. Set up the SMTP server by editing the .env file with your SMTP credentials
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email_address
   MAIL_PASSWORD=your_email_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email_address
   MAIL_FROM_NAME="${APP_NAME}"
   ```
6. Set up the database by editing the `.env` file with your database credentials
   ```
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```
7. Run the database migrations
   ```
   php artisan migrate
   ```
8. Set up the Filament Admin user by running the following command
   ```
   php artisan filament:user
   ```
   This will prompt you to enter your name, email, and password to create a new admin user.
9.  Set up the cron job by adding the following entry to your crontab
   ```
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```
10. Setup your admin login
    ```
    php artisan make:filament-user
    ```
    The password field is always hidden, do not expect it to show up.

## Usage

Once you have installed the project, you can access the dashboard by navigating to `http://your-domain.com/admin`. From there, you can view the analytics for subscribers, sent emails, failed emails, and open emails.

To import subscribers from a CSV file, navigate to `http://your-domain.com/admin/subscribers` and click the `Import CSV` button. Follow the prompts to upload your CSV file and map the columns to the appropriate fields.

To create a new email campaign, navigate to `http://your-domain.com/admin/templates` and click the `New Template` button. You can create your own template using the template editor. Once you have created your email, you can send it to your subscribers by clicking the `Send` button or you can set the status to scheduled to start sending on save.

## Conclusion

This Laravel Email Marketing Project provides a powerful yet easy-to-use platform for managing your email marketing campaigns. With its dashboard analytics, subscriber import, reusable email templates, and easy installation guide, you can get up and running quickly and easily.
