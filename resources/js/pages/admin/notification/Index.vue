<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <h5>Send notification</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label mt-2">Title</label>
                                        <argon-input id="title" type="text" name="title" placeholder="Title"
                                                     @change="this.updateInput" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mt-2">Message</label>
                                        <argon-textarea
                                            id="message"
                                            name="message"
                                            class="multisteps-form__textarea mt-0"
                                            :rows="8"
                                            placeholder="Message"
                                            @change="this.updateInput"
                                        />
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-2">Audience</label>
                                        <select
                                            id="audience"
                                            class="form-control"
                                            name="audience"
                                            @change="this.updateInput"
                                        >
                                            <option value="Subscribed Users" selected>Send to Subscribed Users</option>
                                            <option value="Inactive Users">Send to Inactive Users</option>
                                            <option value="Active Users">Send to Active Users</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-2">When this message start sending?</label>
                                        <select
                                            id="when"
                                            class="form-control"
                                            name="when"
                                            @change="this.updateInput"
                                        >
                                            <option value="immediately" selected>Immediately</option>
                                            <option value="schedule">Schedule</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-2">Per user optimization?</label>
                                        <select
                                            id="perUser"
                                            class="form-control"
                                            name="perUser"
                                            @change="this.updateInput"
                                        >
                                            <option value="now" selected>Send to everyone at the same time</option>
                                            <option value="intelligent">Intelligent delivery (recommended)</option>
                                            <option value="timezone">Custom time per user timezone</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="col-12">
                                    <label class="form-label mt-2">Image ( not work at this time )</label>
                                    <argon-input id="image" type="text" name="image" placeholder="Image" value="https://1-content-s3-estateweb.s3.amazonaws.com/assets/8898/webdefaults/awaiting_image.jpg"
                                                 @change="this.updateInput" />
                                    <img class="w-100 img-fluid rounded" :src="this.image" alt="">
                                </div>
                                <div class="col-12">
                                    <label class="form-label mt-2">Launch URL</label>
                                    <argon-input id="launchUrl" type="text" name="launchUrl" placeholder="Launch URL"
                                                 @change="this.updateInput" />
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary mb-0 mt-3 w-100" @click="send">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonTextarea from "@/components/Argons/ArgonTextarea.vue";

export default {
    name: "Notification",
    components: {
        ArgonInput,
        ArgonTextarea
    },
    data() {
        return {
            title: "",
            message: "",
            audience: "Subscribed Users",
            when: "immediately",
            perUser: "now",
            image: "https://1-content-s3-estateweb.s3.amazonaws.com/assets/8898/webdefaults/awaiting_image.jpg",
            launchUrl: ""
        };
    },
    methods: {
        updateInput(e) {
            this[e.target.name] = e.target.value;
        },
        send(){
            if(this.title === "" || this.message === ""){
                return;
            }else{
                this.$store.dispatch("adminSetting/sendNotification", {
                    title: this.title,
                    message: this.message,
                    audience: this.audience,
                    when: this.when,
                    perUser: this.perUser,
                    image: this.image,
                    launchUrl: this.launchUrl
                }).then((response)=>{
                    if (response) this.$root.$data.snackbar = {
                        color: "success",
                        message: "Send notification successfully!"
                    };
                    else this.$root.$data.snackbar = {
                        color: "danger",
                        message: "Send notification failed!"
                    };
                }).finally(()=>{
                    setTimeout(() => {
                        this.$root.$data.snackbar = null;
                    }, 3000);
                });
            }
        }
    }
};
</script>

<style scoped>

</style>
