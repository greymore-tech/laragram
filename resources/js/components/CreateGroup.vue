<template>
    <div class="chat-view-wrapper">
        <!-- Sidebar for selecting group members from the contact list. -->
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <h4>Add Members</h4>
                <input type="text" v-model="search" placeholder="Search contacts..." class="sidebar-search mt-2" />
            </div>

            <div class="chat-list">
                <!-- Each item is a contact that can be selected. -->
                <div v-for="user in filteredUsers" :key="user.id" class="chat-list-item" @click="toggleUser(user.id)">
                    <div class="avatar chat-avatar">
                        <span class="avatar-initials">{{ getAvatarInitials(user.first_name + ' ' + (user.last_name || '')) }}</span>
                        <img :src="getAvatarUrl(user.id)" @error="onImageError" class="avatar-image" alt="">
                    </div>
                    <div class="chat-info">
                        <h5>{{ user.first_name }} {{ user.last_name || '' }}</h5>
                    </div>
                    <!-- Checkbox to show selection status. -->
                    <input type="checkbox" :checked="selectedUsers.includes(user.id)" class="form-check-input ms-auto">
                </div>
            </div>
        </aside>

        <!-- Main content area to name the group and create it. -->
        <div class="chat-content" style="align-items: center; justify-content: center;">
            <div class="auth-card" style="max-width: 450px;">
                <div class="auth-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h2>New Group</h2>

                <form :action="location + '/dashboard/group/create'" method="POST" class="auth-form">
                    <input type="hidden" name="_token" :value="csrf" />
                    <!-- Hidden inputs for each selected user ID. -->
                    <input v-for="userId in selectedUsers" :key="userId" type="hidden" name="user_id[]" :value="userId">

                    <div class="form-group">
                        <label for="title" class="form-label mb-1">Group Name</label>
                        <input id="title" type="text" v-model="groupTitle" name="title" class="form-control" placeholder="Enter group name..." required>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary" :disabled="!isReadyToCreate">
                            Create Group ({{ selectedUsers.length }} members)
                        </button>
                    </div>
                </form>
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
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            selectedUsers: [],
            groupTitle: '',
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
        },
        /**
         * Checks if the form is ready for submission.
         * Requires at least one member and a group title.
         */
        isReadyToCreate() {
            return this.selectedUsers.length > 0 && this.groupTitle.trim() !== '';
        }
    },
    methods: {
        getAvatarUrl(peerId) {
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
        },
        /**
         * Adds or removes a user from the selected list.
         */
        toggleUser(userId) {
            const index = this.selectedUsers.indexOf(userId);
            if (index > -1) {
                this.selectedUsers.splice(index, 1);
            } else {
                this.selectedUsers.push(userId);
            }
        }
    }
};
</script>
