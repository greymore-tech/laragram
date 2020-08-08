<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Your Contacts</h2>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(contact, index) in orderContacts" :key="index">
                                    <th scope="row">{{ index+1 }}</th>
                                    <td scope="row">{{ contact.first_name }} {{ contact.last_name }}</td>
                                    <td scope="row">+{{ contact.phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["contacts"],
    computed: {
        orderContacts() {
            return _.orderBy(this.contacts, "first_name", "asc");
        },
    },
};
</script>
