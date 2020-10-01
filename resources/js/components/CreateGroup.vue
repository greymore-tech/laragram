<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-4 mb-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>Dashboard</h2>
                    </div>
                    <form
                        method="POST"
                        :action="location + '/dashboard/group/create'"
                    >
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-center">
                                        Create New Group
                                    </h4>
                                    <input
                                        type="hidden"
                                        name="_token"
                                        :value="csrf"
                                    />
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="title"
                                                    placeholder="Write group title..."
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <button
                                                    type="submit"
                                                    class="btn btn-primary btn-block"
                                                >
                                                    Create Group
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-center">
                                        Add Members to Group
                                    </h4>
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
                                                v-for="(user,
                                                index) in orderUsers"
                                                :key="index"
                                            >
                                                <th scope="row">
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="user_id[]"
                                                            id="user-id"
                                                            :value="user.id"
                                                        />
                                                        <label
                                                            class="form-check-label"
                                                            for="user-id"
                                                            >{{
                                                                index + 1
                                                            }}</label
                                                        >
                                                    </div>
                                                </th>
                                                <td scope="row">
                                                    {{ user.first_name }}
                                                    {{ user.last_name }}
                                                </td>
                                                <td scope="row">
                                                    +{{ user.phone }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
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
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            location: location.origin,
        };
    },
    computed: {
        orderUsers() {
            return _.orderBy(this.users, "first_name", "asc");
        },
    },
};
</script>
