# Laragram: A Telegram Web Client in Laravel

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
[![Hacktoberfest](https://img.shields.io/badge/Hacktoberfest-2025-purple.svg)](https://hacktoberfest.com/)
[![Contributions Welcome](https://img.shields.io/badge/Contributions-Welcome-brightgreen.svg?style=flat)](https://github.com/<your-username>/<your-repo>/issues)

Laragram is a Telegram web client built on the Laravel framework, powered by [MadelineProto](https://github.com/danog/MadelineProto), an asynchronous PHP client/server API for the Telegram MTProto protocol. Our goal is to harness the power of these two incredible tools to create a robust, open-source web client for Telegram.

### A Note on Our Current State

This project began as a functional proof-of-concept and successfully demonstrated the core capabilities of logging in, viewing chats, and sending messages. It was built using technologies that were standard at the time, including:

-   **Laravel 7**
-   **PHP 7.2**
-   **MadelineProto v5**
-   **Polling-based updates** (using `setInterval` on the frontend)

This foundation works, but it's time for an upgrade! The current codebase doesn't benefit from the massive performance, security, and feature improvements in recent versions of PHP and Laravel. This presents a fantastic opportunity for the community to help us modernize a solid foundation.

## The Vision: The Path Forward

Our primary goal is to upgrade the entire stack to modern standards, making Laragram faster, more secure, and more scalable. We've broken down this effort into several phases, and this is where **we need your help**.

We are looking for contributors to help us tackle the following roadmap. Feel free to pick any task you're comfortable with!

### Phase 1: Stabilize & Modernize (High Priority)
*   [x] **Upgrade PHP Requirement:** Update `composer.json` to require `^8.2`.
*   [x] **Upgrade the Laravel Framework:** Follow the official guides to upgrade the project from Laravel 7 through to the latest version (Laravel 11). This involves updating dependencies, configuration files, and addressing breaking changes.
*   [x] **Upgrade MadelineProto:** Remove the unmaintained `setiawanhu/laravel-madeline-proto` wrapper and integrate `danog/madelineproto` v8 directly. This may require creating a new Service Provider.
*   [x] **Modernize Frontend Tooling:** Migrate the asset compilation from Laravel Mix (`webpack.mix.js`) to Vite (`vite.config.js`).

### Phase 2: Refactor for Scalability
*   [ ] **Create a `TelegramService` Class:** Abstract all MadelineProto API calls out of the controllers and into a dedicated service class (`app/Services/TelegramService.php`). This will clean up the controllers and make the code more maintainable and testable.
*   [ ] **Refactor Controllers:** Update all controllers (`AuthController`, `ChatController`, `DashboardController`) to use the new `TelegramService` via dependency injection.

### Phase 3: Implement True Real-Time Communication
*   [ ] **Set up a WebSocket Server:** Integrate [Laravel Reverb](https://laravel.com/docs/11.x/reverb) (or Soketi) to handle real-time connections.
*   [ ] **Create a Long-Running Listener:** Build an Artisan command (e.g., `php artisan telegram:listen`) that uses MadelineProto's event handler to listen for incoming Telegram updates persistently.
*   [ ] **Broadcast Events from the Listener:** When the listener receives a new message or update, it should dispatch a Laravel broadcast event (e.g., `NewMessageReceived`).
*   [ ] **Update the Frontend:** Replace all `setInterval` polling in the Vue components with **Laravel Echo**. The components should subscribe to WebSocket channels and update the UI instantly when a broadcast event is received.

### Phase 4: Optimize and Enhance
*   [ ] **Implement a Queue System:** Refactor the "send message" functionality to dispatch a job (e.g., `SendTelegramMessageJob`) instead of executing synchronously. This will make the UI feel instantaneous.
*   [ ] **Improve the UI/UX:** The current UI is basic. We welcome any improvements to the design and user experience.
*   [ ] **Add New Features:** Implement features like viewing user profiles, file uploads/downloads, typing indicators, and read receipts.

## How to Contribute (Hacktoberfest 2024 is here!)

Wanna help out? If you think you can make this bigger, better, or faster, please do! We welcome all contributions and love merging pull requests.

1.  **Find an issue:** Check out the [Project Board](https://github.com/<your-username>/<your-repo>/projects) and the [Issues tab](https://github.com/<your-username>/<your-repo>/issues) to see what needs to be done. The roadmap above is our main guide.
2.  **Fork & Branch:** Fork the repository and create a new branch for your feature or bug fix. (`git checkout -b feature/upgrade-laravel-11`)
3.  **Code!** Make your changes.
4.  **Submit a Pull Request:** Open a PR against the `main` branch and describe the changes you've made. We'll review it as soon as possible.

**Good first issues for beginners:**
-   Helping with a specific step in the Laravel upgrade process (e.g., updating a specific config file).
-   Creating the initial `TelegramService.php` file and moving one or two methods into it.
-   Setting up the initial `vite.config.js` file.

## Getting Started (for Contributors)

To get the project running locally, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/<your-username>/laragram.git
    cd laragram
    ```
2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```
3.  **Setup your environment file:**
    ```bash
    cp .env.example .env
    ```
    - Inside your `.env` file, you **must** add your Telegram API credentials:
    ```
    TELEGRAM_API_ID=your_api_id
    TELEGRAM_API_HASH=your_api_hash
    ```
4.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```
5.  **Install frontend dependencies:**
    ```bash
    npm install
    ```
6.  **Run the development server and asset builder:**
    ```bash
    # In one terminal
    php artisan serve

    # In another terminal
    npm run dev
    ```
7.  Visit `http://127.0.0.1:8000` in your browser!

## Queries

If you have any queries, feel free to open an issue or get in touch with [info@greymore.tech](mailto:info@greymore.tech).

## Licence

Covered under the **GNU AFFERO GENERAL PUBLIC LICENSE, Version 3.**

## Thanks and Gratitude

-   **Danog** for [MadelineProto](https://github.com/danog/MadelineProto)
-   **setiawanhu** for the original [Laravel-Madeline-Proto](https://github.com/setiawanhu/laravel-madeline-proto) wrapper that inspired this project.
