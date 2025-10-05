<template>
    <div class="chat-view-wrapper">
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <input type="text" v-model="search" placeholder="Search chats..." class="sidebar-search" />
            </div>

            <div class="sidebar-menu">
                <a :href="location + '/dashboard/channel'" class="btn btn-primary">Create Channel</a>
                <a :href="location + '/dashboard/group'" class="btn btn-primary">Create Group</a>
                <a :href="location + '/dashboard/contacts'" class="btn btn-primary">New Chat</a>
            </div>

            <div class="chat-list">
                <a v-for="dialog in filteredDialogs" :key="dialog.id" :href="dialog.link" class="chat-list-item">
                    <!-- THE CHANGE IS HERE: New Avatar Structure -->
                    <div class="avatar chat-avatar">
                        <span class="avatar-initials">{{ getAvatarInitials(dialog.name) }}</span>
                        <img :src="getAvatarUrl(dialog.peerId)" @error="onImageError" class="avatar-image" alt="">
                    </div>
                    <div class="chat-info">
                        <h5>{{ dialog.name }}</h5>
                    </div>
                </a>
            </div>
        </aside>

        <div class="chat-content" style="align-items: center; justify-content: center; text-align: center; color: var(--text-secondary);">
            <div>
                <h2>Welcome to Laragram</h2>
                <p>Select a chat from the list to start messaging.</p>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';

export default {
    props: ["messages", "chats", "users", "current_user_id"],
    data() {
        return {
            search: '',
            location: window.location.origin,
        };
    },
    computed: {
        userMap() {
            const map = new Map();
            this.users.forEach(user => map.set(user.id, user));
            return map;
        },
        chatMap() {
            const map = new Map();
            this.chats.forEach(chat => map.set(chat.id, chat));
            return map;
        },
        dialogs() {
            const dialogs = [];
            for (const message of this.messages) {
                if (!message.peer_id) continue;
                const peerId = typeof message.peer_id === 'object' ? (message.peer_id.user_id || message.peer_id.chat_id || message.peer_id.channel_id) : message.peer_id;
                if (this.userMap.has(peerId)) {
                    const user = this.userMap.get(peerId);
                    if (user.id === 777000) continue;
                    dialogs.push({
                        id: 'user-' + user.id,
                        name: `${user.first_name || ''} ${user.last_name || ''}`.trim() || 'Deleted Account',
                        link: `${this.location}/dashboard/messages/user/${user.id}`,
                        peerId: user.id,
                        peerType: 'user'
                    });
                } else if (this.chatMap.has(peerId)) {
                    const chat = this.chatMap.get(peerId);
                    const isChannel = chat['_'] === 'channel' || chat.megagroup || chat.broadcast;
                    dialogs.push({
                        id: (isChannel ? 'channel-' : 'group-') + chat.id,
                        name: chat.title,
                        link: `${this.location}/dashboard/messages/${isChannel ? 'channel' : 'group'}/${chat.id}`,
                        peerId: chat.id,
                        peerType: isChannel ? 'channel' : 'group'
                    });
                }
            }
            const uniqueDialogs = Array.from(new Map(dialogs.map(d => [d.id, d])).values());
            return uniqueDialogs;
        },
        filteredDialogs() {
            if (!this.search.trim()) {
                return this.dialogs;
            }
            const searchTerm = this.search.toLowerCase();
            return this.dialogs.filter(dialog =>
                dialog.name.toLowerCase().includes(searchTerm)
            );
        }
    },
    methods: {
        getAvatarUrl(peerId, peerType) {
        return `${this.location}/api/profile-photo/${peerType}/${peerId}`;
    },
        onImageError(event) {
            // Instead of showing a placeholder, we just hide the broken image.
            // The initials underneath will then be visible.
            event.target.style.display = 'none';
        },
        getAvatarInitials(name) {
            if (!name) return '?';
            const words = name.trim().split(' ').filter(Boolean); // filter(Boolean) removes empty strings
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
