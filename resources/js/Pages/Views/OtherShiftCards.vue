<template>
    <AdminLayout v-if="$page.props.auth.user.role === 1" title="Смены сотрудника">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Смены сотрудника {{employee.name}}
                </h2>
                <a :href="`/shift/add/${employee.id}`" class="bg-blue-400 text-white hover:bg-blue-500 px-4 py-2 rounded">Добавить смену</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="card in localCards" :key="card.id" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 relative" style="display: flex; flex-direction: column;">
                        <p class="font-semibold text-lg">{{ card.start_time.slice(0,10).replace(/-/g, '.') }}</p>
                        <p class="font-semibold text-lg">{{ card.start_time.slice(11, 16) }} - {{ card.end_time ? card.end_time.slice(11, 16) : 'не завершено' }}</p>
                        <p v-if="card.status === 0" class="text-orange-500">Смена не начата</p>
                        <p v-if="card.status === 1 || card.status === 2" class="text-blue-400">Смена начата в {{ card.start_of_shift_time.slice(11, 16) }}</p>
                        <p v-if="card.status === 2" class="text-green-500">Смена закончена в {{ card.end_of_shift_time.slice(11, 16) }}</p>
                        <p v-if="card.status === 2" class="text-green-500">Время работы: {{ getWorkDuration(card.start_of_shift_time, card.end_of_shift_time) }}</p>
                        <p v-if="card.status === 1">Осталось времени: {{ timeLeft(card.end_time) }}</p>
                        <div class="flex justify-end mt-auto">
                            <button @click="deletee(card.id)" class="bg-red-500 text-white hover:bg-red-600 px-2 py-1 rounded">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
    <AppLayout v-else title="Смены сотрудника">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Смены сотрудника {{employee.name}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="card in localCards" :key="card.id" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                        <p class="font-semibold text-lg">{{ card.start_time.slice(0,10).replace(/-/g, '.') }}</p>
                        <p class="font-semibold text-lg">{{ card.start_time.slice(11, 16) }} - {{ card.end_time ? card.end_time.slice(11, 16) : 'не завершено' }}</p>
                        <p v-if="card.status === 0" class="text-orange-500">Смена не начата</p>
                        <p v-if="card.status === 1 || card.status === 2" class="text-blue-400">Смена начата в
                            {{ card.start_of_shift_time.slice(11, 16) }}</p>
                        <p v-if="card.status === 2" class="text-green-500">Смена закончена в {{ card.end_of_shift_time.slice(11, 16) }}</p>
                        <p v-if="card.status === 2" class="text-green-500">Время работы: {{ getWorkDuration(card.start_of_shift_time, card.end_of_shift_time) }}</p>
                        <p v-if="card.status === 1">Осталось времени: {{ timeLeft(card.end_time) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    components: {
        AdminLayout,
        AppLayout,
    },
    data() {
        return {
            localCards: this.cards
        };
    },
    props: ['cards','employee'],
    mounted() {
        setInterval(() => {
            this.localCards = this.localCards.map(card => ({
                ...card,
                timeLeft: this.timeLeft(card.end_time)
            }));
        }, 60000);
    },
    methods: {
        async deletee(id) {
            const response = await axios.get(`/shift/delete/${id}/user/${this.employee.id}`);

            if (response.status === 200) {
                console.log(response.data);
                this.localCards = response.data
            }
        },
        async start(id) {
            const response = await axios.get(`my_shift_cards/start/${id}`);

            if (response.status === 200) {
                this.localCards = response.data
            }
        },
        async end(id) {
            const response = await axios.get(`my_shift_cards/end/${id}`);

            if (response.status === 200) {
                this.localCards = response.data
            }
        },
        timeLeft(endTime) {
            const end = new Date(endTime);
            const now = new Date();
            let diff = end - now;
            console.log(diff);
            if (diff < 0) {
                return 'Смена закончилась';
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            diff -= hours * 1000 * 60 * 60;
            const minutes = Math.floor(diff / (1000 * 60));

            return `${hours}:${minutes < 10 ? '0' : ''}${minutes}`;
        },
        getWorkDuration(startTime, endTime) {
            const start = new Date(startTime);
            const end = new Date(endTime);
            const diff = end - start;
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            return `${hours} часов ${minutes} минут`;
        }
    }
};
</script>
