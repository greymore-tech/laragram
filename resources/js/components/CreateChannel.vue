<template>
    <div class="auth-wrapper">
        <div class="auth-card" style="max-width: 500px;">
            <div class="auth-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <h2>New Channel</h2>

            <form :action="location + '/dashboard/channel/create'" method="POST" class="auth-form">
                <input type="hidden" name="_token" :value="csrf" />

                <div class="form-group">
                    <label for="title" class="form-label mb-1">Channel Name</label>
                    <input id="title" type="text" name="title" class="form-control" placeholder="Enter channel name..." required>
                </div>

                <div class="form-group">
                    <label for="about" class="form-label mb-1">Description (Optional)</label>
                    <textarea id="about" name="about" class="form-control" placeholder="Enter a short description..." rows="3"></textarea>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        Create Channel
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            location: window.location.origin,
        };
    },
};
</script>
