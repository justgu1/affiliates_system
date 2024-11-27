<script setup>
import { useRouter } from 'vue-router';
import { ref, inject, watch, toRaw } from 'vue';
import InputGroup from "@components/common/InputGroup.vue";
import Button from "@components/common/Button.vue";
import A from "@components/common/A.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import Logo from '@components/common/Logo.vue'

const api = inject('api');
const router = useRouter();
const errors = ref({});

const maxDate = new Date();
maxDate.setFullYear(maxDate.getFullYear() - 18);

const form = ref({
    name: '',
    email: '',
    cpf: '',
    birthday: '',
    phone: '',
    address: '',
    city: '',
    state: {},
    password: '',
    role: 'affiliate'
});

const cities = ref([]);

const fetchCities = async (state) => {
    if (!state) {
        form.value.city = '';
        cities.value = [];
        return;
    }

    const stateCode = typeof state === 'object' ? state.value : state;

    if (form.value.state === stateCode) {
        return;
    }

    try {
        cities.value = [{ value: '', label: 'Carregando cidades...' }];
        const response = await api.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${stateCode}/municipios`);
        cities.value = response.map((city) => ({
            value: city.nome,
            label: city.nome,
        }));

        if (!cities.value.some(city => city.value === form.value.city)) {
            form.value.city = '';
        }
    } catch (error) {
        console.error("Erro ao carregar cidades:", error);
        cities.value = [{ value: '', label: 'Erro ao carregar cidades.' }];
        form.value.city = '';
    }
};

watch(
    () => form.value.state,
    (newState) => {
        fetchCities(newState);
    }
);

const validateCPF = (cpf) => {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

    let soma = 0;
    let resto;
    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(9))) return false;

    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(10))) return false;

    return true;
};

const validatePhone = (phone) => {
    const cleanedPhone = phone.replace(/[^\d]/g, '');

    return cleanedPhone.length === 10 || cleanedPhone.length === 11;
};

const validateForm = () => {
    errors.value = {};

    if (!form.value.name) {
        errors.value.name = 'O nome é obrigatório.';
    }

    if (!form.value.email) {
        errors.value.email = 'O e-mail é obrigatório.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
        errors.value.email = 'O e-mail não é válido.';
    }

    if (!form.value.cpf) {
        errors.value.cpf = 'O CPF é obrigatório.';
    } else if (!validateCPF(form.value.cpf)) {
        errors.value.cpf = 'CPF inválido.';
    }

    if (!form.value.phone) {
        errors.value.phone = 'O telefone é obrigatório.';
    } else if (!validatePhone(form.value.phone)) {
        errors.value.phone = 'Telefone inválido. Exemplo: (11) 91234-5678';
    }

    if (!form.value.birthday) {
        errors.value.birthday = 'A data de nascimento é obrigatória.';
    }

    if (!form.value.address) {
        errors.value.address = 'O endereço é obrigatório.';
    }

    if (!form.value.city) {
        errors.value.city = 'A cidade é obrigatória.';
    }

    if (!form.value.state) {
        errors.value.state = 'O estado é obrigatório.';
    }

    if (!form.value.password) {
        errors.value.password = 'A senha é obrigatória.';
    } else if (form.value.password.length < 6) {
        errors.value.password = 'A senha deve ter pelo menos 6 caracteres.';
    }

    return Object.keys(errors.value).length === 0;
};

const states = [
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
];

const formatDateToYYYYMMDD = (birthday) => {
    const date = new Date(birthday);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
};

const submitForm = async (userData) => {
    if (validateForm()) {
        let data = toRaw(form.value);
        data.state = data.state.value;
        data.city = data.city.value;
        data.birthday = formatDateToYYYYMMDD(data.birthday);
        console.log(data);

        try {
            const response = await api.post('/register', data);
            router.push('/affiliate/login?registered=true')
        } catch (error) {
            console.log(error);
            let message = '';
            errors.value = {};
            switch (error.response.status) {
                case 422:
                    message = 'Já existe uma conta com este email.';
                    break;
                default:
                    message = error.response.data.message;
                    break;
            }
            errors.value.response = `Erro ao criar usuário.<br> ${message}`;
        }
    }
};
</script>

<template>
    <div class="flex justify-center items-center align-center py-8 bg-gray-950">
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

            <InputGroup type="text" label="Nome" id="name" v-model="form.name" :error="errors.name"
                placeholder="Digite seu nome">
            </InputGroup>

            <InputGroup type="email" label="E-mail" id="email" v-model="form.email" :error="errors.email"
                placeholder="Digite seu Email">
            </InputGroup>

            <InputGroup type="text" label="CPF" id="cpf" v-model="form.cpf" :error="errors.cpf"
                placeholder="Digite seu CPF">
            </InputGroup>

            <div class="mb-4">
                <label for="birthday" class="block text-sm font-semibold text-gray-700">Aniversário</label>
                <VueDatePicker v-model="form.birthday" locale="pt-BR" format="dd/MM/yyyy" placeholder="dd/mm/yyyy"
                    :time-picker="false" :max="maxDate" :class="{ 'border-red-500': errors.phone }" />
                <span v-if="errors.birthday" class="text-xs text-red-500 mt-1">{{ errors.birthday }}</span>
            </div>

            <InputGroup type="text" label="Telefone" id="phone" v-model="form.phone" :error="errors.phone"
                placeholder="Digite seu Telefone">
            </InputGroup>

            <InputGroup type="text" label="Endereço" id="address" v-model="form.address" :error="errors.address"
                placeholder="Digite seu endereço">
            </InputGroup>

            <InputGroup type="select" label="Estado" id="state" v-model="form.state" :error="errors.state"
                placeholder="Selecione seu estado" :options="states">
            </InputGroup>

            <InputGroup type="select" label="Cidade" id="city" v-model="form.city" :error="errors.city"
                placeholder="Selecione sua cidade" :options="cities">
            </InputGroup>

            <InputGroup type="password" label="Senha" id="password" v-model="form.password" :error="errors.password"
                placeholder="Digite sua senha">
            </InputGroup>

            <Button type="submit" text="Criar conta"></Button>
            <A href="/affiliate/login" text="já tem uma conta? clique aqui"></A>
        </form>
    </div>
</template>

<script>
export default {
    name: 'Register',
    components: {
        InputGroup,
        Button,
        A
    },
};
</script>