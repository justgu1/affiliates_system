<script setup>
import { routeState } from '@/router';
import { useRouter } from 'vue-router';
import { computed, inject, nextTick, ref } from 'vue';
import Button from '@components/common/Button.vue';
import A from '@components/common/A.vue';

const api = inject('api');
const router = useRouter();
const previousRoute = computed(() => routeState.previousRoute);
const errors = ref({});

const handleAction = async (action) => {
    console.log(action)
    if (action == 'logout') {
        try {
            const response = await api.post('/logout');
            if (response.status == 200) {
                localStorage.removeItem('user_token');
                localStorage.removeItem('user_role');
                localStorage.removeItem('user_name');
                localStorage.removeItem('user_email');
                localStorage.removeItem('user_isAdmin');
                window.location.href = '/';
            }
        } catch (error) {
            errors.value = {};
            console.log(error);
            errors.value.response = `Erro ao sair da conta,<br>${error.message}`
        }
    } else {
        if (previousRoute && previousRoute.fullPath) {
            router.push(previousRoute.fullPath);
        } else {
            window.location.href = '/';
        }
    }
}
</script>

<template>
    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="w-full max-w-lg bg-white p-6 rounded-lg flex flex-col">

            <div v-if="errors.response"
                class="text-center text-xs text-red-500 py-2 px-4 bg-red-100 border border-red-500 rounded-lg mb-4"
                v-html="errors.response"></div>

            <h2 class="mb-6 text-center text-lg font-semibold text-blue-700 border-b-2 border-blue-400 uppercase">
                Tem certeza que deseja sair?
            </h2>

            <div class="flex justify-end gap-4">
                <Button type="button" text="Sair" @action-click="handleAction('logout')"
                    class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md" />
                <Button type="button" text="Voltar" @action-click="handleAction('backToPage')"
                    class="bg-gray-500 text-white hover:bg-gray-600 px-4 py-2 rounded-md" />
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'Logout'
};
</script>