# Laragram: A Telegram Web Client in Laravel

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
[![Hacktoberfest](https://img.shields.io/badge/Hacktoberfest-2025-purple.svg)](https://hacktoberfest.com/)
[![Contributions Welcome](https://img.shields.io/badge/Contributions-Welcome-brightgreen.svg?style=flat)](https://github.com/<your-username>/<your-repo>/issues)

Laragram is a modern, open-source Telegram web client built on the Laravel framework. It's powered by [MadelineProto](https://github.com/danog/MadelineProto), an asynchronous PHP client for the Telegram MTProto protocol. Our goal is to create a fast, beautiful, and feature-rich web client for Telegram.

 <!-- It's a great idea to add a screenshot of the new UI! -->

### Current Status: Modernized!

The project has recently completed a major overhaul (**Phase 1**), migrating from a legacy stack to a modern, secure, and high-performance foundation.

-   **Backend:** Upgraded to **Laravel 11** & **PHP 8.2+**.
-   **Telegram API:** Now using the latest **MadelineProto v8**.
-   **Frontend:** Refactored with **Vue 3** and a blazing-fast **Vite** build process.
-   **UI/UX:** Redesigned with a responsive, themeable interface inspired by the official Telegram desktop app.
-   **Development Environment:** Standardized on **Laravel Sail** for a consistent Docker-based setup.

The application now successfully handles authentication, browsing chats, sending/receiving messages (text and media), and more.

## The Vision: The Path Forward

With a stable and modern foundation, we can now focus on making Laragram bigger, better, and faster. This is where **we need your help**. We are looking for contributors to help us tackle the next phases of development.

### Phase 2: Refactor for Scalability
*   [ ] **Create a `TelegramService` Class:** Abstract all MadelineProto API calls out of the controllers and into a dedicated service class (`app/Services/TelegramService.php`). This will clean up the controllers and make the code more maintainable and testable.
*   [ ] **Refactor Controllers:** Update all controllers (`AuthController`, `ChatController`, `DashboardController`) to use the new `TelegramService` via dependency injection.

### Phase 3: Implement True Real-Time Communication
*   [ ] **Set up a WebSocket Server:** Integrate [Laravel Reverb](https://laravel.com/docs/11.x/reverb) to handle real-time connections.
*   [ ] **Create a Long-Running Listener:** Build an Artisan command (e.g., `php artisan telegram:listen`) that uses MadelineProto's event handler to listen for incoming Telegram updates persistently.
*   [ ] **Broadcast Events from the Listener:** When the listener receives a new message, it should dispatch a Laravel broadcast event (e.g., `NewMessageReceived`).
*   [ ] **Update the Frontend:** Replace all `setInterval` polling in the `ChatView.vue` component with **Laravel Echo** to subscribe to WebSocket channels and update the UI instantly.

### Phase 4: Optimize and Enhance
*   [ ] **Implement a Queue System:** Refactor the "send message" functionality to dispatch a job (e.g., `SendTelegramMessageJob`) instead of executing synchronously. This will make the UI feel instantaneous.
*   [ ] **Add New Features:** Implement features like typing indicators, read receipts, displaying stickers/GIFs, and improved media previews.
*   [ ] **Improve the UI/UX:** We welcome any improvements to the design, responsiveness, and user experience.

## How to Contribute (Hacktoberfest is here!)

We welcome all contributions and love merging pull requests.

1.  **Find an issue:** Check out the [Project Board](https://github.com/<your-username>/<your-repo>/projects) and the [Issues tab](https://github.com/<your-username>/<your-repo>/issues). The roadmap above is our main guide.
2.  **Fork & Branch:** Fork the repository and create a new branch for your feature or bug fix.
3.  **Code!** Make your changes following the setup guide below.
4.  **Submit a Pull Request:** Open a PR against the `main` branch and describe your changes. We'll review it as soon as possible.

## Getting Started (with Laravel Sail & Docker)

The easiest and recommended way to get Laragram running is with Laravel Sail. You must have **Docker Desktop** installed and running on your machine.

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
4.  **Add Your Telegram Credentials:**
    Open the `.env` file and fill in your personal API keys from [my.telegram.org](https://my.telegram.org).
    ```dotenv
    TELEGRAM_API_ID=YOUR_API_ID
    TELEGRAM_API_HASH=YOUR_API_HASH
    ```
5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```
6.  **Start the Sail Containers:**
    This command will build and start the application, database, and Redis containers in the background. The first time you run this, it may take several minutes.
    ```bash
    # (Optional, for macOS/Linux) Create a shell alias for easier commands
    alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

    sail up -d
    ```
7.  **Run Database Migrations:**
    All `artisan` and `npm` commands must now be prefixed with `sail`.
    ```bash
    sail artisan migrate
    ```
8.  **Install Frontend Dependencies:**
    ```bash
    sail npm install
    ```
9.  **Run the Vite Development Server:**
    ```bash
    sail npm run dev
    ```

**You're all set!** Your application is now running at [http://localhost](http://localhost).

## Project Structure Highlights

-   **`app/Services/MadelineProtoService.php`**: A crucial service that manages the MadelineProto API instance. This is where all configuration and initialization logic lives.
-   **`app/Http/Controllers/DashboardController.php`**: Handles the initial page load for chat views.
-   **`app/Http/Controllers/ChatController.php`**: Handles all API requests from the frontend (polling, sending messages, downloading media).
-   **`resources/js/components/ChatView.vue`**: The generic, reusable component that renders the main chat interface for users, groups, and channels.
-   **`resources/css/chat.css`**: Contains the custom CSS for the new Telegram-style UI.

## Queries

If you have any queries, feel free to open an issue or get in touch with [info@greymore.tech](mailto:info@greymore.tech).

## Licence

Covered under the **GNU AFFERO GENERAL PUBLIC LICENSE, Version 3.**

## Thanks and Gratitude

-   **Danog** for the incredible [MadelineProto](https://github.com/danog/MadelineProto) library.
-   All contributors who help make this project better
