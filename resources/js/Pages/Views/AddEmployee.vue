<template>
    <AppLayout title="Добавить сотрудника">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Добавить сотрудника
            </h2>
        </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <form @submit.prevent="submit" class="p-6 max-w-md mx-auto bg-white shadow-lg rounded-lg container">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Имя:</label>
                        <input type="text" id="name" v-model="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" id="email" v-model="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Пароль:</label>
                        <input type="password" id="password" v-model="password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Админ</label>
                        <input type="checkbox" id="password" v-model="admin" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-blue-400 text-white hover:bg-blue-500 px-4 py-2 rounded">
                            Добавить сотрудника
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.container {
    position: relative;
    padding: 20px;
    border-radius: 0 50px 50px 50px;
    background: #ffffff;
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            name: '',
            email: '',
            password: '',
            admin: false,
        };
    },
    methods: {
        async submit() {
            const response = await axios.post(`/store_employee`, {
                name: this.name,
                email: this.email,
                password: this.password,
                admin: this.admin,
            });
            if (response.status === 200) {
                window.location.href = "/dashboard";
            }
        }
    }
};
</script>
