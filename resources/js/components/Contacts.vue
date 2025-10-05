<template>
    <div class="chat-view-wrapper">
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <h4>Your Contacts</h4>
                <input type="text" v-model="search" placeholder="Search contacts..." class="sidebar-search mt-2" />
            </div>

            <div class="chat-list">
                <a v-for="user in filteredUsers" :key="user.id" :href="location + '/dashboard/messages/user/' + user.id" class="chat-list-item">
                    <!-- THE CHANGE IS HERE: New Avatar Structure -->
                    <div class="avatar chat-avatar">
                        <span class="avatar-initials">{{ getAvatarInitials(user.first_name + ' ' + (user.last_name || '')) }}</span>
                        <img :src="getAvatarUrl(user.id)" @error="onImageError" class="avatar-image" alt="">
                    </div>
                    <div class="chat-info">
                        <h5>{{ user.first_name }} {{ user.last_name || '' }}</h5>
                        <p v-if="user.phone">+{{ user.phone }}</p>
                    </div>
                </a>
            </div>
        </aside>

        <div class="chat-content" style="align-items: center; justify-content: center; text-align: center; color: var(--text-secondary);">
            <div>
                <h2>Start a new conversation</h2>
                <p>Select a contact from the list on the left.</p>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';

export default {
    props: ["users"],
    data() {
        return {
            search: '',
            location: window.location.origin,
        };
    },
    computed: {
        orderedUsers() {
            return _.orderBy(this.users, 'first_name', 'asc');
        },
        filteredUsers() {
            if (!this.search.trim()) {
                return this.orderedUsers;
            }
            const searchTerm = this.search.toLowerCase();
            return this.orderedUsers.filter(user =>
                (user.first_name && user.first_name.toLowerCase().includes(searchTerm)) ||
                (user.last_name && user.last_name.toLowerCase().includes(searchTerm))
            );
        }
    },
    methods: {
        getAvatarUrl(peerId) {
        // We know it's always a user here
        return `${this.location}/api/profile-photo/user/${peerId}`;
    },
        onImageError(event) {
            event.target.style.display = 'none';
        },
        getAvatarInitials(name) {
            if (!name) return '?';
            const words = name.trim().split(' ').filter(Boolean);
            if (words.length > 1) {
                return (words[0][0] + words[words.length - 1][0]).toUpperCase();
            } else if (words.length === 1 && words[0].length > 1) {
                return words[0].substring(0, 2).toUpperCase();
            } else if (words.length === 1) {
                return words[0][0].toUpperCase();
            }
            return '?';
        }
    }
};
</script>
