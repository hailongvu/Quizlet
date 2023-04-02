<template>
    <div class="container pt-3">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-radius-xl justify-content-between">
            <a class="navbar-brand" href="#">Back</a>
            <div class="justify-content-center" id="navbarNav">
                <ul class="nav nav-pills bg-transparent border-0" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true" style="color: black; font-weight: bold">News
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false" style="color: black; font-weight: bold">Excercite
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false" style="color: black; font-weight: bold">Members
                        </button>
                    </li>

                </ul>
            </div>
            <div class="d-flex align-items-center">
                <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                     style="height: 30px" class="rounded-circle">
                <span class="mr-2">Long Cute pho mai que</span>
            </div>
        </nav>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="card my-4">
                    <div class="card-header border-radius-xl bg-info"
                         style="color: black; font-weight: bold; font-size: 18px">
                        IS1431
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="onSubmit">
                            <div class="form-group">
                                <label for="message-input">Announce something to the class:</label>
                                <textarea class="form-control" id="message-input" name="message"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file-input">Attach a file:</label>
                                <input type="file" class="form-control-file" id="file-input" name="file">
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute pho mai que</span>
                        </div>
                        <div class="pt-2">
                            <p>Chúc các lớp nghỉ hè vui vẻ
                                ....và chuẩn bị tinh thần ôn thi tốt</p>
                        </div>
                        <hr>
                        <form>
                            <div class="form-group">
                                <label for="comment-input">Write a comment:</label>
                                <textarea class="form-control" id="comment-input" rows="1"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </form>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute pho mai que</span>
                        </div>
                        <div class="pt-2">
                            <p>Tài liệu ôn thi</p>
                            <p>https://quizlet.com/466530368/acc101-flash-cards/</p>
                        </div>
                        <hr>
                        <form>
                            <div class="form-group">
                                <label for="comment-input">Write a comment:</label>
                                <textarea class="form-control" id="comment-input" rows="1"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="card my-4  mx-md-12" v-for="(lesson, index) in lessons" :key="index">
                    <div class="row">
                        <div class="col">
                            <div
                                class="card-body"
                                :data-toggle="'#lesson-detail-' + index"
                                @click="toggleLessonDetails(index)"
                                style="color: black; font-weight: bold"
                            >
                                {{ lesson.title }}
                            </div>
                            <div
                                :id="'lesson-detail-' + index"
                                class="collapse"
                                :class="{ show: lesson.showDetails }"
                            >
                                <hr/>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <p>{{ lesson.duration }}</p>
                                            <p>{{ lesson.numQuestions }} questions</p>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary">Start</button>
                                        </div>
                                        <div class="col-2">
<!--                                            <button class="btn btn-primary" @click="viewExerciseResult(lesson.id)">Result</button>-->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Result
                                            </button>
                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detailed test results of students</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="pb-0 card-header">
                                                                <div class="d-lg-flex">
                                                                    <div>
                                                                        <h5 class="mb-0">Java - Lesson 1</h5>
                                                                        <p class="mb-0 text-sm">List all result of test</p>
                                                                    </div>
                                                                    <div class="my-auto mt-4 ms-auto mt-lg-0">
                                                                        <div class="my-auto ms-auto">
                                                                            <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm">Export to exel</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="px-0 pb-0 pt-0 card-body">
                                                                <div class="table-responsive">
                                                                    <DataTable id="setdata" :options="{select: 'single'}" ref="table" class="table table-flush mx-3">
                                                                        <thead class="thead-light">
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>Name</th>
                                                                            <th>Score</th>
                                                                            <th>Time to finish</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr v-for="(result, index) in results" :key="index">
                                                                            <td>{{ result.id }}</td>
                                                                            <td>{{ result.name }}</td>
                                                                            <td>{{ result.score }}</td>
                                                                            <td>{{ result.time }}</td>
                                                                            <td>{{ result.status }}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </DataTable>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <div class="container">
                    <div class="fixed-plugin">
                        <a class="fixed-plugin-button  position-fixed p-3">Add</a>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="pt-3">Teacher</h4>
                            <div class="card my-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute pho mai que</span>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <h4 class="pt-3">Students</h4>
                            <div class="card my-4">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-1" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                        <ul class="dropdown-menu" aria-labelledby="student-options-1">
                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                            <li><a class="dropdown-item" href="#">Hide</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-4">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-2" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                        <ul class="dropdown-menu" aria-labelledby="student-options-2">
                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                            <li><a class="dropdown-item" href="#">Hide</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-4">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-3" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                        <ul class="dropdown-menu" aria-labelledby="student-options-3">
                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                            <li><a class="dropdown-item" href="#">Hide</a></li>
                                        </ul>
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
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';

DataTable.use(DataTablesLib);
export default {
    components:{
        DataTable,
    },
    data() {
        return {
            lessons: [
                {
                    title: 'Java - Lesson 1',
                    duration: '30 min',
                    numQuestions: '25',
                    showDetails: false,
                },
                {
                    title: 'Java - Lesson 2',
                    duration: '40 min',
                    numQuestions: '25',
                    showDetails: false,
                },
                {
                    title: 'Java - Lesson 3',
                    duration: '30 min',
                    numQuestions: '25',
                    showDetails: false,
                },
            ],
            table: null,
            results: [
                { id: 1, name: "Vu Hai Long", score: "80", time: "15:34", status: "submitted" },
                { id: 2, name: "Ngo Tuan Anh", score: "89", time: "15:38", status: "unsubmitted" },
                { id: 3, name: "Dao Xuan Dat", score: "87", time: "15:34", status: "submitted" },
                { id: 4, name: "Nguyen Quang Tuan", score: "45", time: "35:34", status: "unsubmitted" },
                { id: 5, name: "Tran Manh Dat", score: "25", time: "15:34", status: "submitted" },
                { id: 6, name: "Pham Thi An", score: "95", time: "16:22", status: "unsubmitted" },
                { id: 7, name: "Nguyen Van A", score: "70", time: "14:56", status: "submitted" },
                { id: 8, name: "Le Thi B", score: "82", time: "16:08", status: "unsubmitted" },
                { id: 9, name: "Truong Duc C", score: "68", time: "15:12", status: "submitted" },
                { id: 10, name: "Doan Minh D", score: "93", time: "17:04", status: "submitted" },
                { id: 11, name: "Le Tran Gia Huy", score: "75", time: "15:50", status: "unsubmitted" },
                { id: 12, name: "Phan Thi Kim Ngan", score: "78", time: "16:34", status: "submitted" }

            ],
        };
    },
    mounted() {
        // Initialize CKEditor on the message input field
        ClassicEditor.create(document.querySelector('#message-input'))
            .catch(error => {
                console.error(error);
            });
        let delay = setInterval(() => {
            if (this.table) {
                this.table.clear();
                this.table.rows.add(this.results);
                this.table.draw();
                clearInterval(delay);
            }
        }, 100);
    },
    methods: {
        onSubmit() {
            // Handle form submission here
        },
        toggleLessonDetails(index) {
            this.lessons.forEach((lesson, i) => {
                if (i === index) {
                    lesson.showDetails = !lesson.showDetails;
                } else {
                    lesson.showDetails = false;
                }
            });
        },
        viewExerciseResult(exerciseId) {
            const url = '/class/detail/' + exerciseId;
            window.location.href = url;
        },
    }
}
</script>
