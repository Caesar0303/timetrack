<template>
    <AdminLayout title="Создать смену">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Редактирование смену пользователю {{user.name}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 max-w-md mx-auto">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">ПН</p>
                                <input type="checkbox"  v-model="monday">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">ВТ</p>
                                <input type="checkbox" v-model="tuesday">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">СР</p>
                                <input type="checkbox" v-model="wednesday">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">ЧТ</p>
                                <input type="checkbox" v-model="thursday">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">ПТ</p>
                                <input type="checkbox" v-model="friday" >
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">СБ</p>
                                <input type="checkbox" v-model="saturday" >
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">ВС</p>
                                <input type="checkbox" v-model="sunday">
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="submitForm" class="p-6 max-w-md mx-auto">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Начало смены:</label>
                            <input type="time" id="start_time" v-model="form.start_time" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div class="mt-4">
                            <label for="end_time" class="block text-sm font-medium text-gray-700">Конец смены:</label>
                            <input type="time" id="end_time" v-model="form.end_time" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-blue-400 text-white hover:bg-blue-500 px-4 py-2 rounded">
                                Изменить смену
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Checkbox from "@/Components/Checkbox.vue";

export default {
    components: {
        Checkbox,
        AdminLayout,
    },
    props: [
      'user',
      'card',
    ],
    mounted() {
        this.monday = this.card.monday === 1 ? true : false;
        this.tuesday = this.card.tuesday === 1 ? true : false;
        this.wednesday = this.card.wednesday === 1 ? true : false;
        this.thursday = this.card.thursday === 1 ? true : false;
        this.friday = this.card.friday === 1 ? true : false;
        this.saturday = this.card.saturday === 1 ? true : false;
        this.sunday = this.card.sunday === 1 ? true : false;
    },
    data() {
        return {
            form: {
                start_time: this.card.start_time,
                end_time: this.card.end_time,
            },
            monday: this.card.monday,
            tuesday: this.card.tuesday,
            wednesday: this.card.wednesday,
            thursday: this.card.thursday,
            friday: this.card.friday,
            saturday: this.card.saturday,
            sunday: this.card.sunday,
        };
    },
    methods: {
        submitForm() {
            const data = {
                ...this.form,
                monday: this.monday ? 1 : 0,
                tuesday: this.tuesday ? 1 : 0,
                wednesday: this.wednesday ? 1 : 0,
                thursday: this.thursday ? 1 : 0,
                friday: this.friday ? 1 : 0,
                saturday: this.saturday ? 1 : 0,
                sunday: this.sunday ? 1 : 0
            };

            axios.post(`/shift/store/${this.user.id}`, data)
                .then(response => {
                    alert('Смена успешно обновлена');
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
}
</script>
