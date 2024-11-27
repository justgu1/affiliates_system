<script setup>
import { ref, inject } from 'vue';
import InputGroup from "@components/common/InputGroup.vue";
import Button from "@components/common/Button.vue";
import A from "@components/common/A.vue";
import Logo from '@components/common/Logo.vue'
import { useRouter, useRoute } from 'vue-router';
const api = inject('api');

const router = useRouter();
const route = useRoute();

const errors = ref({});
const form = ref({
    name: '',
    email: '',
    password: '',
    role: 'affiliate'
});

const validateForm = () => {
    errors.value = {};

    if (!form.value.email) {
        errors.value.email = 'O e-mail é obrigatório.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
        errors.value.email = 'O e-mail não é válido.';
    }

    if (!form.value.password) {
        errors.value.password = 'A senha é obrigatória.';
    }

    return Object.keys(errors.value).length === 0;
};

const submitForm = async (userData) => {
    if (validateForm()) {
        errors.value = {};
        let message = '';
        try {
            const response = await api.post('/login', form.value);
            if (response.token) {
                localStorage.setItem('user_token', response.token);
                localStorage.setItem('user_name', response.user.name);
                localStorage.setItem('user_email', response.user.email);
                localStorage.setItem('user_role', response.user.type);
                api.api.defaults.headers.common['Authorization'] = `Bearer ${response.token}`;

                window.location.href = '/';
            }
        } catch (error) {
            console.log(error);
            if (error.response?.status === 401) {
                message = 'Credenciais inválidas. Verifique o e-mail e senha.';
            } else {
                message = `${error.response?.data?.message || 'Erro desconhecido'}`;
            }
            errors.value.response = `Erro ao fazer login<br>${message}`
        }
    }
};
</script>
<template>
    <div class="flex justify-center items-center align-center h-screen bg-gray-950">
        <form @submit.prevent="submitForm"
            class="sm:max-w-md w-11/12 sm:w-full mx-auto p-6 border border-gray-300 rounded-lg bg-gray-50">
            <div class="logo w-20 m-auto my-4">
                <a href="/">
                    <Logo></Logo>
                </a>
            </div>
            <div v-if="errors.response"
                class=" text-center text-gray-100 py-2 px-4 bg-red-500 border border-gray-300 rounded-lg bg-gray-50 mb-4"
                v-html="errors.response"></div>

            <InputGroup type="email" label="E-mail" id="email" v-model="form.email" :error="errors.email"
                placeholder="Digite seu Email">
            </InputGroup>

            <InputGroup type="password" label="Senha" id="password" v-model="form.password" :error="errors.password"
                placeholder="Digite sua senha">
            </InputGroup>

            <Button type="submit" text="Entrar"></Button>
            <A href="/user/register" text="Não tem uma conta? clique aqui"></A>
        </form>
    </div>
</template>

<script>
export default {
    name: 'Login',
    components: {
        InputGroup,
        Button,
        A
    },
};
</script>