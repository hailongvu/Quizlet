<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <h5>Basic Info</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-2">Site name</label>
                                <argon-input id="name" type="text" name="name" placeholder="Site name"
                                             :value="this.setting.name"
                                             @change="this.update" />
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-2">TimeZone</label>
                                <select
                                    id="timezone"
                                    class="form-control"
                                    name="timezone"
                                    @change="this.update"
                                >
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2">Description</label>
                                <argon-textarea
                                    id="description"
                                    name="description"
                                    class="multisteps-form__textarea mt-0"
                                    :rows="3"
                                    placeholder="Description"
                                    @change="this.update"
                                />
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2">Add HTML to header</label>
                                <argon-textarea
                                    id="header"
                                    name="header"
                                    :rows="8"
                                    class="multisteps-form__textarea mt-0"
                                    placeholder="Description"
                                    @change="this.update"
                                />
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2">Keywords</label>
                                <input
                                    id="keywords"
                                    name="keywords"
                                    class="form-control"
                                    type="text"
                                    :value="this.setting.keywords"
                                    placeholder="Keywords separated by comma"
                                    onfocus="focused(this)"
                                    @change="this.update"
                                />
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="form-label mt-2">Favicon</label>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <img class="rounded img-fluid w-25" :src="this.setting.favicon"
                                                 alt="favicon" id="favicon-preview">
                                            <input
                                                type="file"
                                                name="favicon"
                                                placeholder="Browse file..."
                                                class="mb-3 mt-3 form-control"
                                                accept="image/x-icon"
                                                @change="this.update"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <label class="form-label mt-2">Logo</label>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <img class="rounded img-fluid w-25" :src="this.setting.logo"
                                                 alt="logo" id="logo-preview">
                                            <input
                                                type="file"
                                                name="logo"
                                                placeholder="Browse file..."
                                                class="mb-3 mt-3 form-control"
                                                accept="image/png"
                                                @change="this.update"
                                            />
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
import Choices from "choices.js";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonTextarea from "@/components/Argons/ArgonTextarea.vue";
import { mapActions } from "vuex";

export default {
    name: "Settings",
    components: {
        ArgonInput,
        ArgonTextarea
    },
    title() {
        return "System Setting" + " - " + document.querySelector("meta[name=\"title\"]").getAttribute("content");
    },
    data() {
        return {
            setting: {}
        };
    },
    beforeMount() {
        this.index();
        this.setting = this.$store.state.adminSetting.settings;
    },
    mounted() {
        // lesson timezone
        this.setTimeZone();
        // lesson description
        document.getElementById("description").value = this.setting.description;
        // lesson header
        document.getElementById("header").value = this.setting.header;
        // lesson keywords
        this.setKeywords();
    },
    methods: {
        ...mapActions({
            index: "adminSetting/index",
            updateSetting: "adminSetting/update"
        }),
        update(e) {
            console.log(e.target);
            let key = e.target.getAttribute("name");
            let value = e.target.value;
            this.$root.$data.snackbar = {
                color: "warning",
                message: "Updating " + key + "..."
            };
            if (key === "favicon" || key === "logo") {
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById(key + "-preview").src = e.target.result;
                };
                reader.readAsDataURL(file);
                value = file;
            }
            this.updateSetting({ key: key, value: value }).then((response) => {
                if (response) this.$root.$data.snackbar = {
                    color: "success",
                    message: "Updated " + key + " successfully!"
                };
                else this.$root.$data.snackbar = {
                    color: "danger",
                    message: "Updated " + key + " failed!"
                };
            }).then(() => {
                setTimeout(() => {
                    this.$root.$data.snackbar = "";
                }, 1000);
            });
        },
        setTimeZone() {
            let listTimeZone = Intl.supportedValuesOf("timeZone");
            let select = document.getElementById("timezone");
            listTimeZone.forEach(function(item) {
                let option = document.createElement("option");
                option.value = item;
                option.text = item;
                select.appendChild(option);
            });
            document.getElementById("timezone").value = this.setting.timezone;
            new Choices("#timezone", {
                searchEnabled: true,
                itemSelectText: "",
                allowHTML: true
            });
        },
        setKeywords() {
            new Choices("#keywords", {
                delimiter: ",",
                editItems: true,
                maxItemCount: 10,
                removeItemButton: true,
                addItems: true,
                allowHTML: true
            });
        }
    }
};
</script>
