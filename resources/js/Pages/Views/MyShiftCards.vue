<template>
    <AppLayout title="Мои смены">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Мои смены
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
                        <div class="flex justify-end">
                            <button v-if="card.status === 0" @click="start(card.id)" class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded">
                                Начать смену
                            </button>
                            <button v-if="card.status === 1" @click="end(card.id)" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                Завершить смену
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            localCards: this.cards
        };
    },
    props: ['cards'],
    mounted() {
        setInterval(() => {
            this.localCards = this.localCards.map(card => ({
                ...card,
                timeLeft: this.timeLeft(card.end_time)
            }));
        }, 60000);
    },
    methods: {
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
