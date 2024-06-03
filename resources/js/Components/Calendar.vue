<template>
    <div class="inline-block bg-white shadow-lg rounded-lg p-4">
        <div class="text-center mb-4">
            <button @click="prevMonth" class="text-gray-500 hover:text-gray-700">&lt;</button>
            <span class="mx-4">{{ currentMonthName }} {{ currentYear }}</span>
            <button @click="nextMonth" class="text-gray-500 hover:text-gray-700">&gt;</button>
        </div>
        <div class="grid grid-cols-7 gap-1">
            <template v-for="day in weekDays">
                <div class="text-center text-gray-500">{{ day }}</div>
            </template>
            <template v-for="(week, index) in weeks" :key="index">
                <div v-for="day in week" :key="day.date" :class="[{ 'not-current-month': day.notCurrentMonth, 'record-date': isRecordDate(day.date) }, 'text-center', 'text-gray-700', 'cursor-pointer', 'hover:bg-gray-100', 'transition', 'duration-300', 'rounded', 'py-1']">
                    <button @click="getRecordInfo(day.date)">{{ day.date }}</button>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import dayjs from 'dayjs';
import axios from 'axios';

export default {
    name: 'Calendar',
    props: ['user_id'],
    setup(props, { emit }) {
        const currentDate = ref(dayjs());
        const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        const currentYear = computed(() => currentDate.value.year());
        const currentMonth = computed(() => currentDate.value.month() + 1);
        const currentMonthName = computed(() => currentDate.value.format('MMMM'));

        const daysInMonth = computed(() => currentDate.value.daysInMonth());
        const firstDayOfMonth = computed(() => currentDate.value.startOf('month').day());

        const recordDates = ref([]);

        const isRecordDate = (day) => {
            const dateString = `${currentYear.value}-${currentMonth.value.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            return recordDates.value.some(record => record.date === dateString);
        };

        const getRecordLink = (day) => {
            const dateString = `${currentYear.value}-${currentMonth.value.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const record = recordDates.value.find(record => record.date === dateString);
            return record ? `/record/${record.id}` : '#';
        };

        const getRecordInfo = async (day) => {
            const link = getRecordLink(day);
            try {
                const response = await axios.get(link);
                const data = response.data;
                console.log(data);
                emit('record-info', data);
            } catch (error) {
                console.error('Failed to fetch record dates:', error);
            }
        };

        const currentMonthDays = computed(() => {
            const days = [];
            for (let i = 1; i <= daysInMonth.value; i++) {
                days.push({
                    date: i,
                    notCurrentMonth: false,
                });
            }
            return days;
        });


        const weeks = computed(() => {
            const days = [...currentMonthDays.value];
            const weeks = [];
            for (let i = 0; i < days.length; i += 7) {
                weeks.push(days.slice(i, i + 7));
            }
            return weeks;
        });

        const prevMonth = () => {
            currentDate.value = currentDate.value.subtract(1, 'month');
            fetchRecordDates();
        };

        const nextMonth = () => {
            currentDate.value = currentDate.value.add(1, 'month');
            fetchRecordDates();
        };

        const fetchRecordDates = async () => {
            try {
                let response; // Объявите переменную здесь
                if (props.user_id) {
                    response = await axios.get(`/records/${props.user_id}`);
                    console.log(response);
                } else {
                    response = await axios.get('/records');
                    console.log('Nopwe');
                }
                const currentMonthRecords = response.data.filter(record => {
                    const dateObj = dayjs(record.created_at);
                    return dateObj.year() === currentDate.value.year() && dateObj.month() === currentDate.value.month();
                });
                recordDates.value = currentMonthRecords.map(record => ({
                    id: record.id,
                    date: dayjs(record.created_at).format('YYYY-MM-DD')
                }));
            } catch (error) {
                console.error('Failed to fetch record dates:', error);
            }
        };


        onMounted(fetchRecordDates);

        return {
            getRecordInfo,
            currentYear,
            currentMonthName,
            currentMonth,
            weekDays,
            weeks,
            prevMonth,
            nextMonth,
            isRecordDate,
            getRecordLink,
        };
    },
};
</script>

<style>
.not-current-month {
    color: #ccc;
}

.record-date {
    background-color: #5d7b20;
    color: white;
}
</style>
