<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">Gerenciamento de Afiliados</h2>
            <div class="flex gap-4">
                <input v-model="filters.search" type="text" placeholder="Buscar afiliado..."
                    class="p-2 border rounded-md" />
                <select v-model="filters.status" class="p-2 border rounded-md">
                    <option value="">Todos</option>
                    <option value="1">Ativos</option>
                    <option value="0">Inativos</option>
                </select>
                <button @click="fetchAffiliates" class="bg-blue-600 text-white px-4 py-2 rounded-md">
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
                <tr v-for="affiliate in affiliates" :key="affiliate.id">
                    <td class="py-2 px-4 border">{{ affiliate.name }}</td>
                    <td class="py-2 px-4 border">{{ affiliate.email }}</td>
                    <td class="py-2 px-4 border">
                        <span :class="affiliate.status === 1 ? 'text-green-500' : 'text-red-500'">
                            {{ affiliate.status === 1 ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="py-2 px-4 border flex gap-2">
                        <button @click="openEditPopup(affiliate)" class="bg-yellow-500 text-white px-3 py-1 rounded-md">
                            Editar
                        </button>
                        <button @click="confirmStatusChange(affiliate)"
                            class="bg-red-500 text-white px-3 py-1 rounded-md">
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
                    <InputGroup type="text" label="Nome" id="name" v-model="editAffiliate.name" :error="errors.name"
                        placeholder="Digite seu nome" />
                    <InputGroup type="email" label="E-mail" id="email" v-model="editAffiliate.email"
                        :error="errors.email" placeholder="Digite seu e-mail" />
                    <InputGroup type="text" label="CPF" id="cpf" v-model="editAffiliate.cpf" :error="errors.cpf"
                        placeholder="Digite seu CPF" />

                    <div class="mb-4">
                        <label for="birthday" class="block text-sm font-semibold text-gray-700">Aniversário</label>
                        <VueDatePicker v-model="editAffiliate.birthday" locale="pt-BR" format="dd/MM/yyyy"
                            placeholder="dd/mm/yyyy" :time-picker="false" :max="maxDate" />
                        <span v-if="errors.birthday" class="text-xs text-red-500 mt-1">{{ errors.birthday }}</span>
                    </div>

                    <InputGroup type="text" label="Telefone" id="phone" v-model="editAffiliate.phone"
                        :error="errors.phone" placeholder="Digite seu telefone" />
                    <InputGroup type="text" label="Endereço" id="address" v-model="editAffiliate.address"
                        :error="errors.address" placeholder="Digite seu endereço" />

                    <InputGroup type="select" label="Estado" id="state" v-model="editAffiliate.state"
                        :error="errors.state" placeholder="Selecione seu estado" :options="states" />
                    <InputGroup type="select" label="Cidade" id="city" v-model="editAffiliate.city" :error="errors.city"
                        placeholder="Selecione sua cidade" :options="cities" />

                    <div class="flex justify-end gap-4">
                        <Button type="submit" text="Salvar alterações" />
                        <Button type="button" text="Cancelar" @click="showEditPopup = false" />
                    </div>
                </form>
            </div>
        </div>

        <div v-if="showStatusPopup" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4">Alterar Status do Afiliado</h3>
                <p>Tem certeza que deseja alterar o status de <strong>{{ statusAffiliate.name }}</strong>?</p>
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
import api from "@utils/api.js";
import { ref } from 'vue';
import InputGroup from "@components/common/InputGroup.vue";
import Button from "@components/common/Button.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import Logo from '@components/common/Logo.vue';

export default {
    data() {
        return {
            affiliates: [],
            editAffiliate: {},
            statusAffiliate: {},
            filters: {
                search: "",
                status: "",
            },
            page: 1,
            hasMore: true,
            showEditPopup: false,
            showStatusPopup: false,
            maxDate: new Date(),
            cities: ref([]),
            states: [
                { value: 'AC', label: 'AC' },
                { value: 'AL', label: 'AL' },
                { value: 'AP', label: 'AP' },
                { value: 'AM', label: 'AM' },
                { value: 'BA', label: 'BA' },
                { value: 'CE', label: 'CE' },
                { value: 'DF', label: 'DF' },
                { value: 'ES', label: 'ES' },
                { value: 'GO', label: 'GO' },
                { value: 'MA', label: 'MA' },
                { value: 'MT', label: 'MT' },
                { value: 'MS', label: 'MS' },
                { value: 'MG', label: 'MG' },
                { value: 'PA', label: 'PA' },
                { value: 'PB', label: 'PB' },
                { value: 'PR', label: 'PR' },
                { value: 'PE', label: 'PE' },
                { value: 'PI', label: 'PI' },
                { value: 'RJ', label: 'RJ' },
                { value: 'RN', label: 'RN' },
                { value: 'RS', label: 'RS' },
                { value: 'RO', label: 'RO' },
                { value: 'RR', label: 'RR' },
                { value: 'SC', label: 'SC' },
                { value: 'SP', label: 'SP' },
                { value: 'SE', label: 'SE' },
                { value: 'TO', label: 'TO' },
            ],
            errors: {},
            form: {
                name: "",
                email: "",
                cpf: "",
                birthday: "",
                phone: "",
                address: "",
                state: "",
                city: "",
                password: "",
            }
        };
    },
    components: {
        InputGroup,
        Button,
        Logo,
        VueDatePicker,
    },
    methods: {
        async fetchCities(state) {
            console.log(state)
            const stateValue = typeof state === 'string' ? state : state?.value;
            console.log(stateValue)
            if (!stateValue) {
                this.form.city = '';
                this.cities = [];
                return;
            }

            if (this.form.stateValue === stateValue) {
                return;
            }

            try {
                this.cities = [{ value: '', label: 'Carregando cidades...' }];
                const response = await api.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${stateValue}/municipios`);

                this.cities = response.map((city) => ({
                    value: city.nome,
                    label: city.nome,
                }));

                if (!this.cities.some(city => city.value === this.form.city)) {
                    this.form.city = '';
                }
            } catch (error) {
                console.error("Erro ao carregar cidades:", error);
                this.cities = [{ value: '', label: 'Erro ao carregar cidades.' }];
                this.form.city = '';
            }
        },
        async fetchAffiliates(page = 1) {
            try {
                const { search, status } = this.filters;
                let query = `?page=${page}`;
                if (search) query += `&search=${search}`;
                if (status) query += `&status=${status}`;

                const response = await api.get(`/affiliate${query}`);
                this.affiliates = response.data;
                this.hasMore = response.next_page_url !== null;
            } catch (error) {
                console.error('Erro ao carregar afiliados', error);
            }
        },
        openEditPopup(affiliate) {
            this.editAffiliate = { ...affiliate };
            this.fetchCities(this.editAffiliate.state);
            this.showEditPopup = true;
        },
        closeEditPopup() {
            this.showEditPopup = false;
            this.editAffiliate = {};
        },
        async submitEdit() {
            try {
                await api.put(`/affiliate/${this.editAffiliate.id}`, this.editAffiliate);
                this.fetchAffiliates();
                this.closeEditPopup();
            } catch (error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Erro desconhecido:', error);
                }
            }
        },
        confirmStatusChange(affiliate) {
            this.statusAffiliate = affiliate;
            this.showStatusPopup = true;
        },
        closeStatusPopup() {
            this.showStatusPopup = false;
        },
        async changeStatus() {
            const newStatus = this.statusAffiliate.status === 1 ? 0 : 1;
            try {
                await api.post(`/affiliate/${this.statusAffiliate.id}/status`, { status: newStatus });
                this.fetchAffiliates();
                this.closeStatusPopup();
            } catch (error) {
                console.error('Erro ao alterar status', error);
            }
        },
        nextPage() {
            this.page++;
            this.fetchAffiliates();
        },
        prevPage() {
            if (this.page > 1) {
                this.page--;
                this.fetchAffiliates();
            }
        },
    },
    mounted() {
        this.fetchAffiliates();
    },
    watch: {
        'form.state': function (newState) {
            this.fetchCities(newState);
        }
    },
};
</script>
