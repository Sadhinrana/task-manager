<script setup>
import {onMounted, reactive, ref, watch} from 'vue'
import draggable from 'vuedraggable'

const task = reactive({
    title: ''
})
const API_ENDPOINT = import.meta.env.VITE_API_ENDPOINT
let [
    loading,
    toDoTasks,
    inProgressTasks,
    doneTasks,
    errors
] = [
    ref(true),
    ref([]),
    ref([]),
    ref([]),
    reactive({})
]

const index = () => {
    fetch(`${API_ENDPOINT}/tasks`)
        .then((response) => response.json())
        .then((result) => {
            toDoTasks.value = result.toDoTasks
            inProgressTasks.value = result.inProgressTasks
            doneTasks.value = result.doneTasks
            loading.value = false
        })
        .catch((error) => {
            console.error('Error:', error)
            loading.value = false
        })
}

const store = async () => {
    loading.value = true
    const form = event.target

    await fetch(form.getAttribute('action'), {
        method: 'POST',
        body: new FormData(form)
    })
        .then((response) => response.json())
        .then((result) => {
            toDoTasks.value.push(result.data)
            task.title = ''
            errors = {}
            console.log('Success:', result.msg)
            loading.value = false
        })
        .catch((error) => {
            errors = error?.errors;
            console.error('Error:', error)
            loading.value = false
        })
}

const sort = (data) => {
    fetch(`${API_ENDPOINT}/tasks/sort`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((result) => {
            console.log('Success:', result.msg)
            loading.value = false
        })
        .catch((error) => {
            console.error('Error:', error)
            loading.value = false
        })
}

watch(toDoTasks, async (currentValue, oldValue) => {
    loading.value = true
    currentValue.forEach(item => item.status = 'to_do')

    await sort(currentValue)
});

watch(inProgressTasks, async (currentValue, oldValue) => {
    loading.value = true
    currentValue.forEach(item => item.status = 'in_progress')

    await sort(currentValue)
});

watch(doneTasks, async (currentValue, oldValue) => {
    loading.value = true
    currentValue.forEach(item => item.status = 'done')

    await sort(currentValue)
});

onMounted(async () => {
    await index()
})
</script>

<template>
    <main>
        <template v-if="loading">
            <h1 class="container">Loading...</h1>
        </template>
        <template v-else>
            <div class="form-container">
                <form :action="`${API_ENDPOINT}/tasks`"
                      method="post"
                      @submit.prevent="store"
                >
                    <input type="text"
                           name="title"
                           v-model="task.title"
                           placeholder="Write your task ..."
                           class="input-box"
                           required
                    >
                    <div class="error" v-if="errors.title">
                        {{ errors.title }}
                    </div>

                    <button type="submit" class="submit-button">Add</button>
                </form>
            </div>

            <div class="container">
                <draggable
                    class="card"
                    v-model="toDoTasks"
                    group="people"
                    item-key="id">
                    <template #header>
                        <div class="card-title">To Do</div>
                    </template>
                    <template #item="{element}">
                        <div class="task">{{ element.title }}</div>
                    </template>
                </draggable>

                <draggable
                    class="card"
                    v-model="inProgressTasks"
                    group="people"
                    item-key="id">
                    <template #header>
                        <div class="card-title">In Progress</div>
                    </template>
                    <template #item="{element}">
                        <div class="task">{{ element.title }}</div>
                    </template>
                </draggable>

                <draggable
                    class="card"
                    v-model="doneTasks"
                    group="people"
                    item-key="id">
                    <template #header>
                        <div class="card-title">Done</div>
                    </template>
                    <template #item="{element}">
                        <div class="task">{{ element.title }}</div>
                    </template>
                </draggable>
            </div>
        </template>
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
    height: 50vh;
    overflow: auto;
}

.card-title {
    font-size: 22px;
    font-weight: bold;
    background-color: rgb(255, 99, 71);
    text-align: center;
    border-bottom: 1px solid black;
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
