<template>
    <div class="container">
        <div class="row justify-content-center align-items-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" :action="login_check">
                            <input type="hidden" name="_token" :value="csrf" />
                            <div class="form-group row">
                                <label
                                    for="secret_code"
                                    class="col-md-4 col-form-label text-md-right"
                                >Enter Code</label>
                                <div class="col-md-6">
                                    <input
                                        id="secret_code"
                                        type="text"
                                        class="form-control"
                                        name="secret_code"
                                        autofocus
                                    />
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </div>
                            <div class="form-group text-center mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <p>(Check for code in your telegram app)</p>
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
            login_check: "login/check",
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
};
</script>
