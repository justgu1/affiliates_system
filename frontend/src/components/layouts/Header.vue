<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { getAffiliateRoutes, getUserRoutes, getCommonRoutes, getAdminRoutes } from '@/router.js';
import Nav from '@components/common/Nav.vue';
import Logo from '@components/common/Logo.vue'
import Icon from "@components/common/Icon.vue";

const router = useRouter();
const routes = router.options.routes;
const userRole = localStorage.getItem('user_role')
const isAdmin = localStorage.getItem('user_isAdmin')
const filteredRoutes = computed(() => {
    if (userRole == null) {
        return getCommonRoutes();
    }

    if(isAdmin){
        return getAdminRoutes()
    }

    switch (userRole) {
        case 'affiliate':
            return getAffiliateRoutes();
        case 'user':
            return getUserRoutes()
        default:
            return getCommonRoutes();
    }
})

const authRoutes = computed(() => {
    if (userRole == null) {
        return { "affiliate": { "routes": getAffiliateRoutes(true), "name": "Afiliado" }, "user": { "routes": getUserRoutes(true), "name": "Usuário" } };
    }
});

const activeCategory = ref(null);

function toggleCategory(key) {
    activeCategory.value = activeCategory.value === key ? null : key;
}
</script>


<template>
    <header class="px-4 py-2 bg-gray-950 flex justify-between items-center">
        <div class="logo w-12">
            <a href="/">
                <Logo></Logo>
            </a>
        </div>
        <Nav :items="filteredRoutes"></Nav>

        <div class="authHeaderSection relative">
            <div class="top h-full flex gap-4" v-if="userRole == null">
                teste
                <a href="javascript:void(0)" class="text-gray-100 text-base h-min"
                    :class="{ 'btn-active': activeCategory === categoryKey }"
                    v-for="(category, categoryKey) in authRoutes" :key="categoryKey"
                    @click="toggleCategory(categoryKey)">
                    {{ category.name }}
                    <span class="icon">
                        <Icon name="fa-caret-down" color="rgb(229 231 235)" />
                    </span>
                </a>
            </div>
            <div class="top h-full flex gap-4" v-else>
                <a href="javascript:void(0)" class="text-gray-100 text-base h-min">
                    Profile
                </a>
                <a href="/logout" class="text-gray-100 text-base h-min">
                    Logout
                </a>
            </div>
            <div class="bottom absolute top-100 right-0" v-if="userRole == null">
                <ul v-show="activeCategory === categoryKey" v-for="(category, categoryKey) in authRoutes"
                    :key="categoryKey" class="px-4 flex flex-col gap-4 py-4 rounded-md bg-gray-950 rounded-lg">
                    <li v-for="(item, idx) in category.routes" :key="idx">
                        <RouterLink :to="item.path" class="text-gray-100 text-base py-2">
                            {{ item.name.split('.')[1] }}
                        </RouterLink>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>



<script>
export default {
    components: {
        Nav
    },
    data() {
        return {
            affiliateRoutes: getAffiliateRoutes(),
            userRoutes: getUserRoutes(),
            commonRoutes: getCommonRoutes(),
        };
    },
}
</script>