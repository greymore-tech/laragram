<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>{{ channel_info.title }}</h2>
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
                                        <div
                                            v-if="
                                                message.message != null &&
                                                    message.message != ''
                                            "
                                        >
                                            <div class="card-body text-nowrap w-50">
                                                <div class="card pt-3 pr-4 pl-4">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5
                                                                class="text-wrap"
                                                                v-html="
                                                                    message.message
                                                                "
                                                                v-linkified
                                                            ></h5>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row text-center">
                                                        <div class="col">
                                                            <p>
                                                                {{
                                                                channel_info.title
                                                                }}
                                                            </p>
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
import linkify from "vue-linkify";

Vue.directive("linkified", linkify);
Vue.prototype.interval;

export default {
    props: ["messages", "channel_info", "current_user_id"],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
            messageToSend: "",
            newMessages: this.messages,
        };
    },
    created() {
        this.fetchChannelsMessages();
        this.interval = setInterval(() => {
            this.fetchChannelsMessages();
        }, 1000);

        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            clearInterval(this.interval);
        }
    },
    mounted() {
        window.addEventListener("load", () => {
            this.interval = setInterval(() => {
                this.fetchChannelsMessages();
            }, 1000);
        });

        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            clearInterval(this.interval);
        }
    },
    methods: {
        fetchChannelsMessages() {
            fetch(
                this.location + "/api/channel/messages/" + this.channel_info.id
            )
                .then((res) => res.json())
                .then((res) => {
                    this.newMessages = res.data;
                });
        },
        sendMessage() {
            var vm = this;
            axios
                .post(
                    this.location +
                        "/api/dashboard/message/channel/" +
                        this.channel_info.id +
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
