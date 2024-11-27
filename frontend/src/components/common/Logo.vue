<template>
    <img :src="logo" alt="Logo" />
  </template>
  
  <script>
  import logo from '@/assets/images/logo.jfif';
  
  export default {
    data() {
      return {
        logo,
      };
    },
  };
  </script>