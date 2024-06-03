<template>
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-gradient-to-br from-blue-500 to-blue-700 text-white">
        <div class="text-center mt-30">
            <h1 class="text-4xl font-bold">Система Учета Рабочих Часов сотрудников</h1>
            <p class="mt-4 text-lg">Добро пожаловать в нашу систему учета рабочих часов. Мы предоставляем удобный и эффективный способ отслеживать рабочее время сотрудников, управлять графиками смен и многое другое.</p>
        </div>
        <div v-if="canLogin" class="absolute bottom-0 right-0 p-6 text-end z-10">
            <Link :href="route('login')" class="mt-8 mr-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded text-lg font-semibold shadow-md transition duration-300 ease-in-out transform hover:scale-105">Логин</Link>
            <Link v-if="canRegister" :href="route('register')" class="mt-8 px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded text-lg font-semibold shadow-md transition duration-300 ease-in-out transform hover:scale-105">Регистрация</Link>
        </div>
    </div>
</template>

<script setup>
import {defineProps} from 'vue';
import {Link} from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<style>
.bg-gradient-to-br {
    background: linear-gradient(to bottom right, #3182ce, #6b46c1);
}

button {
    color: #fff;
    border: none;
    outline: none;
}

button:hover {
    cursor: pointer;
}

button:active {
    transform: scale(0.98);
}

</style>
