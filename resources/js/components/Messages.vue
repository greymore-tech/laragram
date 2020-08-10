<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>Messages</h2>
                    </div>
                    <div v-for="(message, index) in orderMessages" :key="index">
                        <div v-if="message.message != null">
                            <div
                                v-bind:class="[
                                    current_user == message.from_id
                                        ? 'float-right'
                                        : 'float-left'
                                ]"
                            >
                                <div class="card-body text-nowrap">
                                    <div class="card pt-3 pr-4 pl-4">
                                        <div class="row">
                                            <div class="col">
                                                <h3>{{ message.message }}</h3>
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
                                                                current_user ==
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
                                                <p>{{ message.date | ago }}</p>
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
    props: ["messages", "users", "current_user"],
    filters: {
        ago(date) {
            return moment(date).format("h:mm A");
        },
    },
    computed: {
        orderMessages() {
            return _.orderBy(this.messages, "date", "asc");
        },
    },
};
</script>
