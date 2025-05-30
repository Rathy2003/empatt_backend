<template>
  <MainLayout>
    <h2 class="text-2xl font-bold mb-4">Welcome to the Home Page</h2>
    <p>This is the content of the home page.</p>
  </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/MainLayout.vue'

export default {
  name: 'Home',
  components: {
    MainLayout
  }
}
</script>
