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
                                <h4 class="text-center">Search Contacts</h4>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="searchContact"
                                    placeholder="Enter contact name"
                                />
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
                                        <tr v-for="(user, index) in filterUsers" :key="index">
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td scope="row">
                                                <a
                                                    :href="
                                                        '/dashboard/messages/user/' +
                                                            user.id
                                                    "
                                                >
                                                    {{ user.first_name }}
                                                    {{ user.last_name }}
                                                </a>
                                            </td>
                                            <td scope="row">+{{ user.phone }}</td>
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
    props: ["users"],
    data() {
        return {
            searchContact: "",
        };
    },
    computed: {
        orderUsers() {
            return _.orderBy(this.users, "first_name", "asc");
        },
        filterUsers() {
            return this.orderUsers.filter((user) => {
                return user.first_name
                    .toLowerCase()
                    .trim()
                    .match(this.searchContact.toLowerCase().trim());
            });
        },
    },
};
</script>
