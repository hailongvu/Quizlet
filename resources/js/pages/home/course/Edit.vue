<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Update Course</h5>
                        <p class="mb-0 text-sm">Update course data</p>
                    </div>
                    <div class="card-body">
                        <div class="row" v-if="this.course">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                    placeholder="Enter a name, like:'MAE Chapter 1'" :value="this.course.name" />
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                    placeholder="Add a description..." :value="this.course.description" />
                            </div>
                            <div class="col-4">
                                <label class="form-label mt-2 fs-6">Status</label>
                                <argon-switch id="status" name="status" class="mb-3"
                                    :checked="this.course.status === 'active'">
                                    Active
                                </argon-switch>
                            </div>
                            <div class="col-2">
                                <label class="form-label mt-2 fs-6">Public</label>
                                <argon-switch id="is_public" name="is_public" class="mb-3" :checked="this.course.is_public">
                                    Public
                                </argon-switch>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-5 m-3">
                        <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                            @click="this.updateCourse">Update
                        </argon-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";

export default {
    name: "Edit",
    components: {
        ArgonInput,
        ArgonButton,
        ArgonSwitch
    },
    data() {
        return {
            course_id: this.$route.params.id,
            course: null,
        }
    },
    beforeMount() {
        this.$store.dispatch('course/getCourseInfo', this.course_id).then(
            course => {
                this.course = course;
            }
        );
    },
    methods: {
        updateCourse() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let status = document.getElementById('status').checked ? 'active' : 'inactive';
            let is_public = document.getElementById('is_public').checked ? 1 : 0;
            if (name === '' || description === '') {
                alert('Please fill all fields and add at least 3 cards');
            } else {
                this.$root.$data.snackbar = {
                    color: 'warning',
                    message: 'Updating course...',
                };
                setTimeout(() => {
                    this.$root.$data.snackbar = null;
                }, 1000);
                this.$store.dispatch('course/updateCourse', {
                    id: this.course_id,
                    name: name,
                    description: description,
                    status: status,
                    is_public: is_public,
                });
                this.$router.push({ name: 'home.course.index', params: { id: this.course_id } });
            }
        }
    },
}
</script>
