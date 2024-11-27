<script setup>
import { ref, provide } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '@utils/api';
import '@styles/main.css';

//components
import Header from "@components/layouts/Header.vue";

const router = useRouter();
const route = useRoute();
const routes = router.getRoutes();

provide('api', api);
</script>
<template>
  <Header
    v-if="route.path != '/affiliate/login' && route.path != '/affiliate/register' && route.path != '/user/login' && route.path != '/user/register'">
  </Header>
  <router-view></router-view>
</template>
<script>
export default {
  name: 'App',
  components: {
    Header
  }
};
</script>