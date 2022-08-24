<script setup>
import {createApp} from 'vue'

const app = createApp({
    data() {
        return {
            task: {
                title: ''
            },
            toDoTasks: [],
            inProgressTasks: [],
            doneTasks: [],
            errors: {},
        }
    },
    methods: {
        store() {
            const [vm, form] = [this, event.target]

            fetch(form.getAttribute(form), {
                method: 'POST',
                body: new FormData(form)
            })
                .then((response) => response.json())
                .then((result) => {
                    vm.task.title = '';
                    vm.errors = {};
                    console.log('Success:', result)
                })
                .catch((error) => {
                    console.error('Error:', error)
                })
        }
    }
})

const API_ENDPOINT = import.meta.env.VITE_API_ENDPOINT
</script>

<template>
    <main>
        <div class="form-container">
            <form :action="`${API_ENDPOINT}/tasks`"
                  method="post"
                  @submit.prevent="store"
            >
                <input type="text"
                       name="title"
                       placeholder="Write your task ..."
                       class="input-box"
                       required
                >
<!--                <div class="error" v-if="errors.title">
                    {{ errors.title }}
                </div>-->

                <button type="submit" class="submit-button">Add</button>
            </form>
        </div>

        <div class="container">
            <div class="card">
                <div class="card-title">To Do</div>

                <div class="card-body">
                    <div class="task" v-for="toDoTask in toDoTasks">
                        {{ toDoTask.title }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">In Progress</div>

                <div class="task" v-for="inProgressTask in inProgressTasks">
                    {{ inProgressTask.title }}
                </div>
            </div>

            <div class="card">
                <div class="card-title">Done</div>

                <div class="task" v-for="doneTask in doneTasks">
                    {{ doneTask.title }}
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>
main {
    width: 1280px;
}

.form-container {
    text-align: center;
}

.input-box {
    margin: 10px;
    padding: 10px;
}

.submit-button {
    margin: 10px;
    padding: 10px 30px;
    color: rgb(255, 99, 71);
    font-weight: bold;
}

.container {
    display: flex;
    justify-content: center;
}

.card {
    display: inline-block;
    width: 200px;
    border: 1px solid black;
    margin-right: 20px;
}

.card-title {
    font-size: 22px;
    font-weight: bold;
    background-color: rgb(255, 99, 71);
    text-align: center;
    border-bottom: 1px solid black;
}

.card-body {
    height: 50vh;
    overflow: auto;
}

.task {
    margin: 10px;
    padding: 10px;
    font-weight: bold;
    text-align: center;
    border: 1px solid black;
    background-color: rgb(221, 221, 221);
    line-height: 1.2;
    letter-spacing: .01em;
    word-wrap: break-word !important;
    cursor: pointer;
}

.error {
    color: red;
}
</style>
