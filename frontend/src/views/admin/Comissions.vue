<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">Gerenciamento de Comissões</h2>
            <button @click="showAddCommissionPopup = true" class="bg-green-600 text-white px-4 py-2 rounded-md">
                Adicionar Comissão
            </button>
        </div>

        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">Afiliado</th>
                    <th class="py-2 px-4 border">Descrição</th>
                    <th class="py-2 px-4 border">Valor</th>
                    <th class="py-2 px-4 border">Data</th>
                    <th class="py-2 px-4 border">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="commission in commissions" :key="commission.id">
                    <td class="py-2 px-4 border">{{ commission.affiliate.name }}</td>
                    <td class="py-2 px-4 border">{{ commission.description }}</td>
                    <td class="py-2 px-4 border">{{ formatCurrency(commission.amount) }}</td>
                    <td class="py-2 px-4 border">{{ formatDate(commission.created_at) }}</td>
                    <td class="py-2 px-4 border flex gap-2">
                        <button @click="openEditCommissionPopup(commission)"
                            class="bg-yellow-500 text-white px-3 py-1 rounded-md">
                            Editar
                        </button>
                        <button @click="openDeleteConfirmationPopup(commission)"
                            class="bg-red-500 text-white px-3 py-1 rounded-md">
                            Excluir
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Popup de Adicionar Comissão -->
        <div v-if="showAddCommissionPopup"
            class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
            <div class="popup-content">
                <form @submit.prevent="submitAddCommission"
                    class="sm:max-w-md w-11/12 sm:w-full mx-auto p-6 border border-gray-300 rounded-lg bg-gray-50 min-w-60">
                    <InputGroup type="select" label="Afiliado" id="affiliate" v-model="newCommission.affiliate_id"
                        :error="errors.affiliate_id" :options="affiliates" placeholder="Selecione o afiliado" />
                    <InputGroup type="text" label="Descrição" id="description" v-model="newCommission.description"
                        :error="errors.description" placeholder="Digite a descrição" />
                    <InputGroup type="text" label="Valor" id="amount" v-model="newCommission.amount"
                        :error="errors.amount" placeholder="Digite o valor" />

                    <div class="flex justify-end gap-4">
                        <Button type="submit" text="Adicionar Comissão" />
                        <Button type="button" text="Cancelar" @click="showAddCommissionPopup = false" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Popup de Editar Comissão -->
        <div v-if="showEditCommissionPopup"
            class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
            <div class="popup-content">
                <form @submit.prevent="submitEditCommission"
                    class="sm:max-w-md w-11/12 sm:w-full mx-auto p-6 border border-gray-300 rounded-lg bg-gray-50 min-w-60">
                    <InputGroup type="select" label="Afiliado" id="affiliate" v-model="editCommission.affiliate_id"
                        :error="errors.affiliate_id" :options="affiliates" placeholder="Selecione o afiliado" />
                    <InputGroup type="text" label="Descrição" id="description" v-model="editCommission.description"
                        :error="errors.description" placeholder="Digite a descrição" />
                    <InputGroup type="text" label="Valor" id="amount" v-model="editCommission.amount"
                        :error="errors.amount" placeholder="Digite o valor" />

                    <div class="flex justify-end gap-4">
                        <Button type="submit" text="Salvar alterações" />
                        <Button type="button" text="Cancelar" @click="showEditCommissionPopup = false" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Popup de Confirmação para Excluir Comissão -->
        <div v-if="showDeleteConfirmationPopup"
            class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
            <div
                class="popup-content sm:max-w-md w-11/12 sm:w-full mx-auto p-6 border border-gray-300 rounded-lg bg-gray-50 min-w-60">
                <h3 class="text-xl mb-4">Tem certeza que deseja excluir esta comissão?</h3>
                <div class="flex justify-end gap-4">
                    <button @click="deleteCommission(deleteCommissionData)"
                        class="bg-red-500 text-white px-4 py-2 rounded-md">
                        Sim
                    </button>
                    <button @click="showDeleteConfirmationPopup = false"
                        class="bg-gray-400 text-white px-4 py-2 rounded-md">
                        Não
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

export default {
    data() {
        return {
            commissions: [],
            affiliates: [],
            errors: {},
            newCommission: {
                affiliate_id: "",
                description: "",
                amount: "",
            },
            editCommission: {},
            deleteCommissionData: null,
            showAddCommissionPopup: false,
            showEditCommissionPopup: false,
            showDeleteConfirmationPopup: false,
        };
    },
    components: {
        InputGroup,
        Button,
    },
    methods: {
        formatDate(date) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return new Intl.DateTimeFormat('pt-BR', options).format(new Date(date));
        },
        async fetchCommissions() {
            try {
                const response = await api.get("/commission");
                this.commissions = response;
            } catch (error) {
                console.error('Erro ao carregar comissões', error);
            }
        },
        async fetchAffiliates() {
            try {
                const response = await api.get("/affiliate");
                this.affiliates = response.data.map(affiliate => ({
                    value: affiliate.id,
                    label: affiliate.name,
                }));
            } catch (error) {
                console.error('Erro ao carregar afiliados', error);
            }
        },
        openEditCommissionPopup(commission) {
            this.editCommission = { ...commission };
            this.showEditCommissionPopup = true;
        },
        closeEditCommissionPopup() {
            this.showEditCommissionPopup = false;
            this.editCommission = {};
        },
        formatCurrency(amount) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(amount);
        },
        async submitAddCommission() {
            const amount = parseFloat(this.newCommission.amount.replace(/[^\d,-]/g, '').replace(',', '.'));
            if (isNaN(amount)) {
                this.errors.amount = "Valor inválido.";
                return;
            }

            this.newCommission.amount = amount;

            // Garantir que o affiliate_id seja apenas o valor
            this.newCommission.affiliate_id = this.newCommission.affiliate_id.value;

            try {
                const response = await api.post("/commission", this.newCommission);
                this.fetchCommissions();
                this.showAddCommissionPopup = false;
                this.newCommission = { affiliate_id: "", description: "", amount: "" };
            } catch (error) {
                if (error.response && error.response.errors) {
                    this.errors = error.response.errors;
                } else {
                    console.error('Erro desconhecido:', error);
                }
            }
        }
        ,
        async submitEditCommission() {
            // Garantir que o affiliate_id seja apenas o valor
            this.editCommission.affiliate_id = this.editCommission.affiliate_id.value;

            try {
                await api.put(`/commission/${this.editCommission.id}`, this.editCommission);
                this.fetchCommissions();
                this.showEditCommissionPopup = false;
            } catch (error) {
                if (error.response && error.response.errors) {
                    this.errors = error.response.errors;
                } else {
                    console.error('Erro desconhecido:', error);
                }
            }
        }
        ,
        async deleteCommission(commission) {
            try {
                await api.delete(`/commission/${commission.id}`);
                this.fetchCommissions();
                this.showDeleteConfirmationPopup = false;
                this.deleteCommissionData = null;
            } catch (error) {
                console.error('Erro ao excluir comissão', error);
            }
        },
        openDeleteConfirmationPopup(commission) {
            this.deleteCommissionData = commission;
            this.showDeleteConfirmationPopup = true;
        },
    },
    mounted() {
        this.fetchCommissions();
        this.fetchAffiliates();
    },
};
</script>
