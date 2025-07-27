<template>
  <div>
    <MainLayout>
      <DashboardComponent 
        :summary="summary" 
        :recentPayments="recentPayments"
        :users="users"
        :pagination="pagination"
      />
    </MainLayout>
  </div>
</template>

<script setup lang="ts">
import MainLayout from '../components/layouts/MainLayout.vue';
import DashboardComponent from '../components/Dashboard/DashboardComponent.vue';
import { defineProps } from 'vue';

const props = defineProps({
  summary: Object,
  recentPayments: Array,
  users: Object,
  pagination: Object
});
</script>