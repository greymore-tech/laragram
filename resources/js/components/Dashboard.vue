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
                                <h4 class="text-center">Create New Channel</h4>
                                <form
                                    method="POST"
                                    :action="
                                                location +
                                                    '/dashboard/channel/create'
                                            "
                                >
                                    <input type="hidden" name="_token" :value="csrf" />
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="title"
                                                    placeholder="Write channel title..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <textarea
                                                    type="text"
                                                    class="form-control"
                                                    name="about"
                                                    placeholder="Write channel description..."
                                                    rows="3"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <button
                                                    type="submit"
                                                    class="btn btn-primary btn-block"
                                                >Create Channel</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col">
                                <h4 class="text-center">Your Contacts</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(contact,
                                            index) in orderContacts"
                                            :key="index"
                                        >
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td scope="row">
                                                <a
                                                    :href="
                                                        'dashboard/messages/user/' +
                                                            contact.id
                                                    "
                                                >
                                                    {{ contact.first_name }}
                                                    {{ contact.last_name }}
                                                </a>
                                            </td>
                                            <td scope="row">+{{ contact.phone }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <h4 class="text-center">Your Groups and Channels</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(channel,
                                            index) in orderChannels"
                                            :key="index"
                                        >
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td scope="row">
                                                <a
                                                    :href="
                                                        'dashboard/messages/channel/' +
                                                            channel.id
                                                    "
                                                >{{ channel.title }}</a>
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
    props: ["contacts", "channels"],
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
        };
    },
    computed: {
        orderContacts() {
            return _.orderBy(this.contacts, "first_name", "asc");
        },
        orderChannels() {
            return _.orderBy(this.channels, "title", "asc");
        },
    },
};
</script>
