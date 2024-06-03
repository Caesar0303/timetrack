<template>
    <AdminLayout title="Все сотрудники">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Все сотрудники
                </h2>
                <a :href="`/admin/add_employee`" class="bg-blue-400 text-white hover:bg-blue-500 px-4 py-2 rounded">Добавить пользователя</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Почта</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Завершенные смены</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Общее часы работы</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in localUsers" :key="user.id" class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap ">{{ user.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.ended_shifts ? user.ended_shifts : '0' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ user.work_hours ? user.work_hours : '0' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button @click="openModal(user.id)" class="text-indigo-400 hover:text-indigo-600">Редактировать смену</button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button @click="openModalEdit(user.id)" class="text-indigo-400 hover:text-indigo-600">Редактировать</button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a :href="`/show/employee/${user.id}`" class="text-indigo-400 hover:text-indigo-600">Посмотреть</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button @click="deletee(user.id)" class="text-red-400 hover:text-red-600">Удалить</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModalEdit" title="Редактирование смены" @close="closeModalEdit">
            <form @submit.prevent="submitFormEdit(id)" class="p-6 max-w-md mx-auto">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Имя:</label>
                    <input type="text" v-model="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="text" v-model="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Пароль:</label>
                    <input type="text" v-model="password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-blue-400 text-white hover:bg-blue-500 px-4 py-2 rounded">
                        Изменить смену
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showModal" title="Редактирование смены" @close="closeModal">
                <div class="p-6 max-w-md mx-auto">
                    <div class="flex justify-between">
                        {{}}
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
                            <input type="checkbox" v-model="friday">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">СБ</p>
                            <input type="checkbox" v-model="saturday">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">ВС</p>
                            <input type="checkbox" v-model="sunday">
                        </div>
                    </div>
                </div>
                <form @submit.prevent="submitForm(id)" class="p-6 max-w-md mx-auto">
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
        </Modal>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";

export default {
    components: {
        AdminLayout,
        Modal,
    },

    props: [
        'users',
    ],
    data() {
        return {
            name: '',
            email: '',
            password: '',
            localUsers: this.users,
            showModal: false,
            showModalEdit: false,
            monday: false,
            tuesday: false,
            wednesday: false,
            thursday: false,
            friday: false,
            saturday: false,
            sunday: false,
            form: {
                start_time: '',
                end_time: '',
            },
            currentUser: null
        };
    },
    methods: {
        async deletee(id) {
            const response = await axios.get(`/admin/delete/${id}`);
            if (response.status === 200) {
                this.localUsers = response.data;
            }
        },
        async openModalEdit (id) {
            const response = await axios.get(`/admin/employee_edit/${id}`);
            this.currentUser = id;
            this.name = response.data.name;
            this.email = response.data.email;
            this.password = null;
            this.showModalEdit = true;
        },
        async openModal(id) {
            const response = await axios.get(`/shift/edit/${id}`);
            this.currentUser = id;
            this.currentCard = response.data.card;
            this.monday = this.currentCard.monday === 1 ? true : false;
            this.tuesday = this.currentCard.tuesday === 1 ? true : false;
            this.wednesday = this.currentCard.wednesday === 1 ? true : false;
            this.thursday = this.currentCard.thursday === 1 ? true : false;
            this.friday = this.currentCard.friday === 1 ? true : false;
            this.saturday = this.currentCard.saturday === 1 ? true : false;
            this.sunday = this.currentCard.sunday === 1 ? true : false;
            this.form.start_time = this.currentCard.start_time;
            this.form.end_time = this.currentCard.end_time;
            this.showModal = true;
        },

        submitFormEdit: async function() {
            const data = {
                name: this.name ,
                email: this.email,
                password: this.password,
            };
            console.log(data);
            try {
                const response = await axios.post(`/admin/employee_update/${this.currentUser}`, data);
                if (response.status === 200) {
                    this.localUsers = response.data;
                }
                this.closeModalEdit();
            } catch (error) {
                console.error(error);
            }
        },

        submitForm: async function() {
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

            try {
                const response = await axios.post(`/shift/store/${this.currentUser}`, data);
                this.closeModal();
            } catch (error) {
                console.error(error);
            }
        },
        closeModal() {
            this.showModal = false;
        },
        closeModalEdit() {
            this.showModalEdit = false;
        },

    }
}
</script>
