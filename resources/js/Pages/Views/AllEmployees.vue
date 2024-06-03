<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components:{
        AppLayout
    },
    props: [
        'users',
    ]
}
</script>

<template>
    <AppLayout title="Все сотрудники">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Все сотрудники
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Завершенные смены</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Общее часы работы</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap ">{{ user.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.ended_shifts }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.work_hours }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a :href="`/other_shifts/${user.id}`" class="text-indigo-400 hover:text-indigo-600">Посмотреть смены</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
