<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center pt-3">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" :action="login">
                            <input type="hidden" name="_token" :value="csrf" />
                            <div class="form-group row">
                                <label
                                    for="phone"
                                    class="col-md-4 col-form-label text-md-right"
                                >Phone Number</label>
                                <div class="col-md-6">
                                    <input
                                        id="phone"
                                        type="text"
                                        class="form-control"
                                        name="phone"
                                        autofocus
                                    />
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-block"
                                    >Send Code</button>
                                </div>
                            </div>
                            <div class="form-group text-center mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <p>Note: Enter your number with the country code.</p>
                                    <p>(Example: +911234567890)</p>
                                    <p>Here, +91 is country code.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            login: "/login",
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
};
</script>
