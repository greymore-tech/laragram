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
                            <div class="col card-size">
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
                                                                index) in users"
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    props: ["messages", "users", "group_info", "current_user_id"],
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
