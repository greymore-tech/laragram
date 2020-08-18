<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>{{ group_info.title }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col card-size" id="card-scroll-bottom">
                                <div class="card">
                                    <div
                                        v-for="(message,
                                        index) in orderMessages"
                                        :key="index"
                                    >
                                        <div v-if="message.message != null">
                                            <div
                                                class="card-body text-nowrap w-50"
                                                v-bind:class="[
                                                    current_user_id ==
                                                    message.from_id
                                                        ? 'float-right'
                                                        : 'float-left'
                                                ]"
                                            >
                                                <div class="card pt-3 pr-4 pl-4">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5 class="text-wrap">
                                                                {{
                                                                message.message
                                                                }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row text-center">
                                                        <div class="col">
                                                            <div
                                                                v-for="(user,
                                                                index) in newUsers"
                                                                :key="index"
                                                            >
                                                                <div
                                                                    v-if="
                                                                        user.id ==
                                                                            message.from_id
                                                                    "
                                                                >
                                                                    <div
                                                                        v-if="
                                                                            current_user_id ==
                                                                                user.id
                                                                        "
                                                                    >
                                                                        <p>You</p>
                                                                    </div>
                                                                    <div v-else>
                                                                        <p>
                                                                            {{
                                                                            user.first_name
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <p>
                                                                {{
                                                                message.date
                                                                | ago
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-4">
                                <form
                                    method="POST"
                                    :action="
                                        location +
                                            '/dashboard/message/group/' +
                                            group_info.id +
                                            '/send'
                                    "
                                >
                                    <input type="hidden" name="_token" :value="csrf" />
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="message"
                                                    placeholder="Write your message..."
                                                />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-block"
                                            >Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
$(document).ready(function () {
    var card_scroll_bottom = document.getElementById("card-scroll-bottom");
    card_scroll_bottom.scrollTop = card_scroll_bottom.scrollHeight;
});

import moment from "moment";

export default {
    props: ["messages", "users", "group_info", "current_user_id"],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
            newMessages: this.messages,
            newUsers: this.users,
        };
    },
    created() {
        var interval;

        this.fetchGroupsMessages();
        this.fetchGroupsUsers();
        interval = setInterval(() => {
            this.fetchGroupsMessages();
        }, 1000);

        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            clearInterval(interval);
        }
    },
    methods: {
        fetchGroupsMessages() {
            fetch(this.location + "/api/group/messages/" + this.group_info.id)
                .then((res) => res.json())
                .then((res) => {
                    this.newMessages = res.data;
                });
        },
        fetchGroupsUsers() {
            fetch(this.location + "/api/group/" + this.group_info.id)
                .then((res) => res.json())
                .then((res) => {
                    this.newUsers = res.data;
                });
        },
    },
    filters: {
        ago(date) {
            return moment.unix(date).fromNow();
        },
    },
    computed: {
        orderMessages() {
            return _.orderBy(this.newMessages, "date", "asc");
        },
    },
};
</script>
