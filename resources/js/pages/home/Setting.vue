<template>
    <div class="row mt-3">
        <h2 style="display: flex; justify-content: center; margin-bottom: 50px">Setting</h2>
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-envelope fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Update your email address</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="new-email">New Email Address</label>
                        <input type="text" id="new-email" name="new-email" class="form-control"
                               placeholder="Enter new email">
                    </div>

                    <div class="form-group">
                        <label for="learn-password">Learn Password</label>
                        <input type="password" id="learn-password" name="learn-password" class="form-control"
                               placeholder="Enter Learn password">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-lock fs-1" ></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Change PassWord</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="new-email">Current PassWord</label>
                        <input type="text" id="new-email" name="new-email" class="form-control"
                               placeholder="Enter password">
                    </div>

                    <div class="form-group">
                        <label for="learn-password">New Password</label>
                        <input type="password" id="learn-password" name="learn-password" class="form-control"
                               placeholder="Enter new password">
                    </div>
                    <div class="form-group">
                        <label for="learn-password">Re-New Password</label>
                        <input type="password" id="learn-password" name="learn-password" class="form-control"
                               placeholder="Enter re-new password">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-moon-cloud fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">NightMode</h5>
                <div class="form-check form-switch" style="margin-left: 10px; margin-top: 20px">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-eye fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">How you appear on Quizlet</h5>
                <div class="form-check" style="margin-left: 10px">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Show your real name on Let Learn
                    </label>
                </div>
                <div class="form-check" style="margin-left: 10px">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                    <label class="form-check-label" for="defaultCheck2">
                        Show your profile on Let Learn
                    </label>
                    <p>Removing a page from Google takes a variable amount of time depending on how recently Google
                        indexed the page. If you have an urgent need to remove your profile from Google search, please
                        contact us and we can do it for you manually</p>

                </div>
                <div class="form-check">
                    <button type="submit" class="btn btn-behance">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-x fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Permanently delete Account</h5>
                <div class="card-body">
                    <p>Be careful - this will delete all your data and cannot be undone.</p>
                    <button type="submit" class="btn btn-warning">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Setting",
};
</script>

<style scoped>
.card-body .form-group {
    margin-bottom: 1rem;
}

.card-body input[type="text"],
.card-body input[type="password"] {
    width: 100%;
}
</style>
