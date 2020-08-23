<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>{{ other_user_info.first_name }} {{ other_user_info.last_name }}</h2>
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
                                <form @submit.prevent="sendMessage">
                                    <input type="hidden" name="_token" :value="csrf" />
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="messageToSend"
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

Vue.prototype.interval;

export default {
    props: [
        "messages",
        "users",
        "current_user_id",
        "other_user_id",
        "other_user_info",
    ],
    created() {
        window.addEventListener("load", () => {
            this.fetchUsersMessages();
            this.fetchUsers();
            this.interval = setInterval(() => {
                this.fetchUsersMessages();
            }, 1000);
        });

        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            clearInterval(this.interval);
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.interval = setInterval(() => {
                this.fetchUsersMessages();
            }, 1000);
        });

        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            clearInterval(this.interval);
        }
    },
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
            messageToSend: "",
            newMessages: this.messages,
            newUsers: this.users,
        };
    },
    methods: {
        fetchUsersMessages() {
            fetch(this.location + "/api/user/messages/" + this.other_user_id)
                .then((res) => res.json())
                .then((res) => {
                    this.newMessages = res.data;
                });
        },
        fetchUsers() {
            fetch(this.location + "/api/user/" + this.other_user_id)
                .then((res) => res.json())
                .then((res) => {
                    this.newUsers = res.data;
                });
        },
        sendMessage() {
            var vm = this;
            axios
                .post(
                    this.location +
                        "/api/dashboard/message/user/" +
                        this.other_user_id +
                        "/send",
                    {
                        messageToSend: this.messageToSend,
                    }
                )
                .then(function (response) {
                    if (response.status === 200) {
                        vm.messageToSend = "";
                    }
                })
                .catch((error) => console.log(error));
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
