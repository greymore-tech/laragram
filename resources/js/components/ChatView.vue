<template>
    <div class="chat-view-wrapper">
        <div class="chat-content">
            <!-- The header for the current chat, showing photo and title. -->
            <header class="chat-header">
                <!-- A back button for mobile view. -->
                <button class="back-button d-md-none" @click="goBack" style="display: block;">&larr;</button>

                <!-- The profile picture/initials avatar for the current chat. -->
                <div class="avatar chat-avatar-header">
                    <span class="avatar-initials">{{ getAvatarInitials(chatTitle) }}</span>
                    <img :src="getAvatarUrl(chatInfo.id)" @error="onImageError" class="avatar-image" alt="">
                </div>

                <h3>{{ chatTitle }}</h3>
            </header>

            <!-- The scrollable list of messages. -->
            <main class="message-list" id="message-list" ref="messageListEl">
                <div v-for="message in orderedMessages" :key="message.id" class="message-row" :class="{ 'outgoing': message.out }">
                    <div class="message-bubble">
                        <div class="message-sender" v-if="chatType === 'group' && !message.out">
                            {{ getSenderName(message) }}
                        </div>
                        <div class="message-content">
                            <!-- Renders media if present. -->
                            <div v-if="message.media" class="message-media">
                                <em class="media-placeholder">Media Attached (Photo, Video, etc.)</em>
                                <a :href="downloadUrl(message)" target="_blank" class="download-link">Download</a>
                            </div>
                            <!-- Renders the text message if present. -->
                            <div v-if="message.message" class="message-text" v-html="linkify(message.message)"></div>
                        </div>
                        <div class="message-timestamp">{{ formatTimestamp(message.date) }}</div>
                    </div>
                </div>
            </main>

            <!-- The footer containing the message input form. -->
            <footer class="chat-footer">
                <form @submit.prevent="sendMessage" class="message-form">
                    <input
                        type="text"
                        class="message-input"
                        v-model="messageToSend"
                        placeholder="Write a message..."
                        autocomplete="off"
                    />
                    <button type="submit" class="send-button">&rarr;</button>
                </form>
            </footer>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import _ from 'lodash';
import { onMounted, ref, computed, watch, nextTick, onBeforeUnmount } from 'vue';
import axios from 'axios';

export default {
    /**
     * Component properties passed from the Blade template.
     * @property {String} chatType - The type of chat ('user', 'group', 'channel').
     * @property {Object} chatInfo - Info about the current chat (user, group, or channel object).
     * @property {Array} initialMessages - The first batch of messages loaded by the server.
     * @property {Array} initialUsers - The list of users in the chat (especially for groups).
     * @property {Object} currentUser - Info about the logged-in user.
     */
    props: {
        chatType: { type: String, required: true },
        chatInfo: { type: Object, required: true },
        initialMessages: { type: Array, default: () => [] },
        initialUsers: { type: Array, default: () => [] },
        currentUser: { type: Object, required: true },
    },
    setup(props) {
        const messages = ref([...props.initialMessages]);
        const users = ref([...props.initialUsers]);
        const messageToSend = ref('');
        const location = window.location.origin;
        const messageListEl = ref(null); // Template ref for the message list element

        // --- Computed Properties ---
        const chatTitle = computed(() => (props.chatType === 'user') ? `${props.chatInfo.first_name || ''} ${props.chatInfo.last_name || ''}`.trim() : props.chatInfo.title);

        const apiEndpoint = computed(() => {
            if (props.chatType === 'user') return `${location}/api/user/chat-data/${props.chatInfo.id}`;
            if (props.chatType === 'group') return `${location}/api/group/messages/${props.chatInfo.id}`;
            if (props.chatType === 'channel') return `${location}/api/channel/messages/${props.chatInfo.id}`;
            return '';
        });

        const sendEndpoint = computed(() => `${location}/api/dashboard/message/${props.chatType}/${props.chatInfo.id}/send`);

        const orderedMessages = computed(() => _.orderBy(messages.value, 'date', 'asc'));

        const userMap = computed(() => new Map(users.value.map(user => [user.id, user])));

        // --- Methods ---
        const fetchChatData = () => {
            if (!apiEndpoint.value) return;
            axios.get(apiEndpoint.value).then(response => {
                const incomingMessages = response.data.messages || response.data || [];
                const existingMessageIds = new Set(messages.value.map(m => m.id));
                const newMessages = incomingMessages.filter(m => !existingMessageIds.has(m.id));
                if (newMessages.length > 0) messages.value.push(...newMessages);
                if (response.data.users) users.value = response.data.users;
            });
        };

        const sendMessage = () => {
            if (!messageToSend.value.trim()) return;
            axios.post(sendEndpoint.value, { messageToSend: messageToSend.value })
                .then(() => {
                    messageToSend.value = '';
                    setTimeout(() => fetchChatData(), 300);
                })
                .catch(error => console.error("Send message failed:", error));
        };

        const getSenderName = (message) => {
            const senderId = message.from_id;
            if (userMap.value.has(senderId)) return userMap.value.get(senderId).first_name || 'Unknown User';
            return '...';
        };

        const formatTimestamp = (unixDate) => moment.unix(unixDate).format('h:mm A');

        const linkify = (text) => text.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, url => `<a href="${url}" target="_blank">${url}</a>`);

        const downloadUrl = (message) => `${location}/api/media/download?messageId=${message.id}&peerId=${props.chatInfo.id}&peerType=${props.chatType}`;

        const scrollToBottom = (force = false) => {
            nextTick(() => {
                const el = messageListEl.value;
                if (el) {
                    const isScrolledToBottom = el.scrollHeight - el.clientHeight <= el.scrollTop + 100;
                    if (force || isScrolledToBottom) {
                        el.scrollTop = el.scrollHeight;
                    }
                }
            });
        };

        const getAvatarUrl = (peerId) => {
            return `${location}/api/profile-photo/${props.chatType}/${peerId}`;
        };

        const onImageError = (event) => {
            event.target.style.display = 'none';
        };

        const getAvatarInitials = (name) => {
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
        };

        const goBack = () => window.history.back();

        // --- Lifecycle and Watchers ---
        let intervalId = null;
        onMounted(() => {
            scrollToBottom(true);
            intervalId = setInterval(fetchChatData, 5000);
        });

        onBeforeUnmount(() => {
            clearInterval(intervalId);
        });

        watch(messages, () => {
            scrollToBottom();
        }, { deep: true });

        return {
            messageListEl,
            messages,
            users,
            messageToSend,
            chatTitle,
            orderedMessages,
            sendMessage,
            getSenderName,
            formatTimestamp,
            linkify,
            downloadUrl,
            getAvatarUrl,
            onImageError,
            getAvatarInitials,
            goBack,
        };
    }
};
</script>
