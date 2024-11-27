<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">Gerenciamento de Usuários</h2>
            <div class="flex gap-4">
                <input v-model="filters.search" type="text" placeholder="Buscar usuário..."
                    class="p-2 border rounded-md" />
                <select v-model="filters.status" class="p-2 border rounded-md">
                    <option value="">Todos</option>
                    <option value="1">Ativos</option>
                    <option value="0">Inativos</option>
                </select>
                <button @click="fetchUsers" class="bg-blue-600 text-white px-4 py-2 rounded-md">
                    Filtrar
                </button>
            </div>
        </div>

        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">Nome</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Status</th>
                    <th class="py-2 px-4 border">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td class="py-2 px-4 border">{{ user.name }}</td>
                    <td class="py-2 px-4 border">{{ user.email }}</td>
                    <td class="py-2 px-4 border">
                        <span :class="user.status === 1 ? 'text-green-500' : 'text-red-500'">
                            {{ user.status === 1 ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="py-2 px-4 border flex gap-2">
                        <button @click="openEditPopup(user)" class="bg-yellow-500 text-white px-3 py-1 rounded-md">
                            Editar
                        </button>
                        <button @click="confirmStatusChange(user)" class="bg-red-500 text-white px-3 py-1 rounded-md">
                            Alterar Status
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-end">
            <button @click="prevPage" :disabled="page <= 1" class="px-4 py-2 bg-gray-300 rounded-md">
                Anterior
            </button>
            <button @click="nextPage" :disabled="!hasMore" class="ml-2 px-4 py-2 bg-gray-300 rounded-md">
                Próximo
            </button>
        </div>

        <!-- Popup de Edição -->
        <div v-if="showEditPopup" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
            <div class="popup-content">
                <form @submit.prevent="submitEdit"
                    class="sm:max-w-md w-11/12 sm:w-full mx-auto p-6 border border-gray-300 rounded-lg bg-gray-50 min-w-60">
                    <InputGroup type="text" label="Nome" id="name" v-model="editUser.name" :error="errors.name"
                        placeholder="Digite o nome" />
                    <InputGroup type="email" label="E-mail" id="email" v-model="editUser.email" :error="errors.email"
                        placeholder="Digite o e-mail" />

                    <div class="flex justify-end gap-4">
                        <Button type="submit" text="Salvar alterações" />
                        <Button type="button" text="Cancelar" @click="showEditPopup = false" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Popup de Alteração de Status -->
        <div v-if="showStatusPopup" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4">Alterar Status do Usuário</h3>
                <p>Tem certeza que deseja alterar o status de <strong>{{ statusUser.name }}</strong>?</p>
                <div class="flex justify-end gap-4 mt-4">
                    <button type="button" @click="closeStatusPopup" class="bg-gray-500 text-white px-4 py-2 rounded-md">
                        Cancelar
                    </button>
                    <button @click="changeStatus" class="bg-red-600 text-white px-4 py-2 rounded-md">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@utils/api.js'; // Se necessário, ajusta para seu arquivo de API
import { ref } from 'vue';
import InputGroup from '@components/common/InputGroup.vue';
import Button from '@components/common/Button.vue';

export default {
    data() {
        return {
            users: [],
            editUser: {},
            statusUser: {},
            filters: {
                search: '',
                status: '',
            },
            page: 1,
            hasMore: true,
            showEditPopup: false,
            showStatusPopup: false,
            errors: {},
        };
    },
    components: {
        InputGroup,
        Button,
    },
    methods: {
        async fetchUsers(page = 1) {
            try {
                const { search, status } = this.filters;
                let query = `?page=${page}`;
                if (search) query += `&search=${search}`;
                if (status) query += `&status=${status}`;

                const response = await api.get(`/user${query}`);
                this.users = response.data;
                this.hasMore = response.next_page_url !== null;
            } catch (error) {
                console.error('Erro ao carregar usuários', error);
            }
        },
        openEditPopup(user) {
            this.editUser = { ...user };
            this.showEditPopup = true;
        },
        closeEditPopup() {
            this.showEditPopup = false;
            this.editUser = {};
        },
        async submitEdit() {
            try {
                await api.put(`/user/${this.editUser.id}`, this.editUser);
                this.fetchUsers();
                this.closeEditPopup();
            } catch (error) {
                if (error.response && error.response && error.response.errors) {
                    this.errors = error.response.errors;
                } else {
                    console.error('Erro desconhecido:', error);
                }
            }
        },
        confirmStatusChange(user) {
            this.statusUser = user;
            this.showStatusPopup = true;
        },
        closeStatusPopup() {
            this.showStatusPopup = false;
        },
        async changeStatus() {
            const newStatus = this.statusUser.status === 1 ? 0 : 1;
            try {
                await api.post(`/user/${this.statusUser.id}/status`, { status: newStatus });
                this.fetchUsers();
                this.closeStatusPopup();
            } catch (error) {
                console.error('Erro ao alterar status', error);
            }
        },
        nextPage() {
            this.page++;
            this.fetchUsers();
        },
        prevPage() {
            if (this.page > 1) {
                this.page--;
                this.fetchUsers();
            }
        },
    },
    mounted() {
        this.fetchUsers();
    },
};
</script>