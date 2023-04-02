<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-3xl ">{{ question }}</div>
                    <hr>
                    <div class="card-body">
                        <div v-for="(answer, index) in answers" :key="index">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="text-center">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-sm btn-outline-success" @click="upvoteAnswer(index)">
                                                    {{ answer.upvote }}
                                                </button>
                                                <div class="col-md- justify-content-end align-items-center">
                                                    <div>{{ answer.count }}</div>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger mt-2" @click="downvoteAnswer(index)">
                                                    {{ answer.downvote }}
                                                </button>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                                <div class="col-md-9 m-lg-2 d-flex align-items-center">
                                    <div>{{ answer.text }}</div>
                                </div>
                                <div class="col-md-1">
                                    <div class="text-center">
                                        <img :src="answer.avatar" class="rounded-circle d-flex align-items-center" alt="avatar"
                                             style="width: 50px;">
                                        <div>{{ answer.username }}</div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submitAnswer">
                            <div class="form-group">
                                <label for="answerInput">Your Answer</label>
                                <textarea class="form-control" id="answerInput" rows="3" v-model="newAnswer"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
            question: 'What is your favorite programming language?',
            answers: [
                {
                    upvote: '👍',
                    downvote: '👎',
                    count: 10,
                    text: 'My favorite programming language is JavaScript.',
                    avatar: 'https://i.pravatar.cc/150?img=3',
                    username: 'John Doe'
                },
                {
                    upvote: '👍',
                    downvote: '👎',
                    count: 5,
                    text: 'I prefer Python over JavaScript.',
                    avatar: 'https://i.pravatar.cc/150?img=2',
                    username: 'Jane Doe'
                }
            ],
            newAnswer: '',
            newAnswerUsername: 'Hai Long'
        }
    },
    methods: {
        upvoteAnswer(index) {
            this.answers[index].count++
        },
        downvoteAnswer(index) {
            this.answers[index].count--
        },
        submitAnswer() {
            try {
                if (this.newAnswer === '') {
                    throw new Error('Answer cannot be empty.')
                }

                this.answers.push({
                    upvote: '👍',
                    downvote: '👎',
                    count: 0,
                    text: this.newAnswer,
                    avatar: 'https://i.pravatar.cc/150?img=1',
                    username: this.newAnswerUsername
                })

                this.newAnswer = ''
            } catch (e) {
                alert(e.message)

            }
        }
    }
}
</script>
