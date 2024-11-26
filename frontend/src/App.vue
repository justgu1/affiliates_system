<script setup>
import { ref, provide } from 'vue';
import { useRouter, useRoute } from 'vue-router';

import '@styles/main.css';

const router = useRouter();
const route = useRoute();
const routes = router.getRoutes();



</script>
<template>
  <router-view></router-view>
</template>
<script>
export default {
  name: 'App',
  components: {

  }
};
</script>