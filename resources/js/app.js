import './bootstrap';
import { createApp } from 'vue';

// --- THEME TOGGLER LOGIC ---
document.addEventListener('DOMContentLoaded', () => {
    const themeToggler = document.getElementById('theme-toggle');
    const body = document.body;

    // Function to apply the theme
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
    };

    // Check for saved theme in localStorage
    const savedTheme = localStorage.getItem('theme');
    // Check for OS preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Apply saved theme, or OS preference, or default to light
    applyTheme(savedTheme || (prefersDark ? 'dark' : 'light'));

    // Add click listener to the button
    themeToggler.addEventListener('click', () => {
        const isDarkMode = body.classList.toggle('dark-mode');
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
    });
});

// Import Components
import ChatView from './components/ChatView.vue';
import Dashboard from './components/Dashboard.vue';
import Contacts from './components/Contacts.vue';
import CreateChannel from './components/CreateChannel.vue';
import CreateGroup from './components/CreateGroup.vue';

const app = createApp({});

// Register Components
app.component('chat-view', ChatView);
app.component('dashboard', Dashboard);
app.component('contacts', Contacts);
app.component('create-channel', CreateChannel);
app.component('create-group', CreateGroup);

app.mount('#app');
