<template>
    <AppLayout title="Мои смены">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Смены сотрудника {{user.name}}
            </h2>
        </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="card in localCards" :key="card.id" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <p class="font-semibold text-lg">{{ card.start_time }} - {{ card.end_time }}</p>
                    <span :class="{'bg-blue-500 text-white': card.monday === 1, 'bg-red-500 text-white': card.monday !== 1}" class="rounded px-2 py-1">ПН</span>
                    <span :class="{'bg-blue-500 text-white ml-1': card.tuesday === 1, 'bg-red-500 text-white ml-1': card.tuesday !== 1}" class="rounded px-2 py-1">ВТ</span>
                    <span :class="{'bg-blue-500 text-white ml-1': card.wednesday === 1, 'bg-red-500 text-white ml-1': card.wednesday !== 1}" class="rounded px-2 py-1">СР</span>
                    <span :class="{'bg-blue-500 text-white ml-1': card.thursday === 1, 'bg-red-500 text-white ml-1': card.thursday !== 1}" class="rounded px-2 py-1">ЧТ</span>
                    <span :class="{'bg-blue-500 text-white ml-1': card.friday === 1, 'bg-red-500 text-white ml-1': card.friday !== 1}" class="rounded px-2 py-1">ПТ</span>
                    <span :class="{'bg-blue-500 text-white ml-1': card.saturday === 1, 'bg-red-500 text-white ml-1': card.saturday !== 1}" class="rounded px-2 py-1">СБ</span>
                    <span :class="{'bg-blue-500 text-white ml-1': card.sunday === 1, 'bg-red-500 text-white ml-1': card.sunday !== 1}" class="rounded px-2 py-1">ВС</span>
                    <p v-if="card.status === 0" class="text-orange-500">Смена не начата</p>
                    <p v-if="card.status === 1 || card.status === 2" class="text-blue-400">Смена начата в {{ card.start_of_shift_time }}</p>
                    <p v-if="card.status === 2" class="text-green-500">Смена закончена в {{ card.end_of_shift_time }}</p>
                    <p v-if="card.status === 2" class="text-green-500">Время работы: {{ getWorkDuration(card.start_of_shift_time, card.end_of_shift_time) }}</p>
                    <p v-if="card.status === 1">Осталось времени: {{ timeLeft(card.end_time) }}</p>
<!--                    <div class="flex justify-end">-->
<!--                        <button v-if="card.status === 0 && access === true" @click="start(card.id)" class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded">-->
<!--                            Начать смену-->
<!--                        </button>-->
<!--                        <button disabled v-if="access === false" @click="start(card.id)" class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">-->
<!--                            Сегодня смены нету-->
<!--                        </button>-->
<!--                        <button v-if="card.status === 1" @click="end(card.id)" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">-->
<!--                            Завершить смену-->
<!--                        </button>-->
<!--                    </div>-->
                </div>
            </div>
            <div class="flex">
                <div class="mt-6">
                    <calendar @record-info="handleRecordInfo" :user_id="user.id"/>
                </div>
                <div class="mt-6 ml-4" v-if="record_data">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 container">
                        Информация о смене <span v-if="record_data">{{formattedDate(record_data.created_at) }}</span> | <span>{{record_data.start_time}}</span> - <span>{{record_data.end_time}}</span>
                        <div>
                            <span>Время когда смена началась {{record_data.start_of_shift_time}}</span>
                            <br>
                            <span>Время когда смена закончилась {{record_data.end_of_shift_time}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.container {
    position: relative;
    padding: 20px;
    border-radius: 0 50px 50px 50px; /* Радиус скругления угла */
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Calendar from '@/Components/Calendar.vue';


export default {
    components: {
        AppLayout,
        Calendar,
    },
    data() {
        return {
            record_data: '',
            localCards: this.cards
        };
    },
    props: ['cards','access','user'],
    mounted() {
        setInterval(() => {
            this.localCards = this.localCards.map(card => ({
                ...card,
                timeLeft: this.timeLeft(card.end_time)
            }));
        }, 3000);
    },
    methods: {
        formattedDate() {
            const parts = this.record_data.created_at.split('T');
            const datePart = parts[0];
            return `${datePart}`;
        },

        async start(id) {
            try {
                const response = await axios.get(`my_shift_cards/start/${id}`);
                if (response.status === 200) {
                    this.localCards = response.data;
                }
            } catch (error) {
                if (error.response && error.response.status === 400) {
                    alert("Смена закончена");
                } else {
                    console.error(error);
                }
            }
        },

        handleRecordInfo(data) {
            this.record_data = data;
        },

        async end(id) {
            const response = await axios.get(`my_shift_cards/end/${id}`);

            if (response.status === 200) {
                this.localCards = response.data
                console.log(response.data);
            }
        },
        timeLeft(endTime) {
            const now = new Date();
            const [hours, minutes] = endTime.split(':');
            const end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
            const diff = end - now;
            if (diff < 0) {
                return 'Смена завершена';
            }
            const diffHours = Math.floor(diff / (1000 * 60 * 60));
            const diffMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            return `${diffHours} часов ${diffMinutes} минут`;
        },
        getWorkDuration(startTime, endTime) {
            const today = new Date().toISOString().split('T')[0];

            const start = new Date(`${today}T${startTime}`);
            const end = new Date(`${today}T${endTime}`);


            const diff = end - start;

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

            return `${hours} часов ${minutes} минут`;
        }
    }
};
</script>
