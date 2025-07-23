<template>
    <div>
        <MainLayout>
            <DashboardComponent :users="users" :pagination="pagination"></DashboardComponent>
        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../components/layouts/MainLayout.vue';
import DashboardComponent from '../components/Dashboard/DashboardComponent.vue';
import { defineProps, onMounted } from 'vue'
import Pagination from '../components/Pagination.vue';

const props = defineProps({
    users: Object,
    pagination: Object,
});

onMounted(() => {
    console.log(props.users);
});
</script>