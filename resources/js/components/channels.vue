<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>Your Chat</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col card-size">
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
                                <form
                                    method="POST"
                                    :action="
                                        location +
                                            '/dashboard/message/channel/' +
                                            channel_info.id +
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
import moment from "moment";
import linkify from "vue-linkify";

Vue.directive("linkified", linkify);

export default {
    props: ["messages", "channel_info", "current_user_id"],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
        };
    },
    filters: {
        ago(date) {
            return moment.unix(date).fromNow();
        },
    },
    computed: {
        orderMessages() {
            return _.orderBy(this.messages, "date", "asc");
        },
    },
};
</script>
