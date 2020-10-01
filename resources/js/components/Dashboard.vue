<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <a
                                    :href="'dashboard/channel'"
                                    class="btn btn-primary btn-block"
                                    >Create Channel</a
                                >
                            </div>
                            <div class="col">
                                <a
                                    :href="'dashboard/group'"
                                    class="btn btn-primary btn-block"
                                    >Create Group</a
                                >
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <a
                                    :href="'dashboard/contacts'"
                                    class="btn btn-primary btn-block"
                                    >Start New Chat</a
                                >
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col">
                                <h4 class="text-center">Recent Chats</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(message, index) in messages"
                                            :key="index"
                                        >
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td
                                                scope="row"
                                                v-if="
                                                    message.to_id['_'] ==
                                                    'peerUser'
                                                "
                                            >
                                                <div
                                                    v-for="(user,
                                                    index) in users"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            message.from_id ==
                                                            current_user_id
                                                        "
                                                    >
                                                        <div
                                                            v-if="
                                                                message.to_id
                                                                    .user_id ==
                                                                user.id
                                                            "
                                                        >
                                                            <div
                                                                v-if="
                                                                    user.first_name !=
                                                                    null
                                                                "
                                                            >
                                                                <a
                                                                    :href="
                                                                        'dashboard/messages/user/' +
                                                                        user.id
                                                                    "
                                                                    >{{
                                                                        user.first_name
                                                                    }}
                                                                    {{
                                                                        user.last_name
                                                                    }}</a
                                                                >
                                                            </div>
                                                            <div v-else>
                                                                <p>
                                                                    Deleted
                                                                    Account
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            message.to_id
                                                                .user_id ==
                                                            current_user_id
                                                        "
                                                    >
                                                        <div
                                                            v-if="
                                                                message.from_id ==
                                                                user.id
                                                            "
                                                        >
                                                            <div
                                                                v-if="
                                                                    user.first_name !=
                                                                    null
                                                                "
                                                            >
                                                                <a
                                                                    :href="
                                                                        'dashboard/messages/user/' +
                                                                        user.id
                                                                    "
                                                                    >{{
                                                                        user.first_name
                                                                    }}
                                                                    {{
                                                                        user.last_name
                                                                    }}</a
                                                                >
                                                            </div>
                                                            <div v-else>
                                                                <p>
                                                                    Deleted
                                                                    Account
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                scope="row"
                                                v-else-if="
                                                    message.to_id['_'] ==
                                                    'peerChat'
                                                "
                                            >
                                                <div
                                                    v-for="(chat,
                                                    index) in chats"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            message.to_id
                                                                .chat_id ==
                                                            chat.id
                                                        "
                                                    >
                                                        <a
                                                            :href="
                                                                'dashboard/messages/group/' +
                                                                chat.id
                                                            "
                                                            >{{ chat.title }}</a
                                                        >
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                scope="row"
                                                v-else-if="
                                                    message.to_id['_'] ==
                                                    'peerChannel'
                                                "
                                            >
                                                <div
                                                    v-for="(chat,
                                                    index) in chats"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            message.to_id
                                                                .channel_id ==
                                                            chat.id
                                                        "
                                                    >
                                                        <a
                                                            :href="
                                                                'dashboard/messages/channel/' +
                                                                chat.id
                                                            "
                                                            >{{ chat.title }}</a
                                                        >
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["messages", "chats", "users", "current_user_id"],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
        };
    },
};
</script>
