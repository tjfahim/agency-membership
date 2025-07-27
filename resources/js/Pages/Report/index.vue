<template>
    <div>
        <MainLayout>
            <ReportComponent :users="users" :pagination="pagination"></ReportComponent>
        </MainLayout>
    </div>
</template>
<script setup lang="ts">
import MainLayout from '../../components/layouts/MainLayout.vue';
import ReportComponent from '../../components/Report/ReportComponent.vue';
import { defineProps, onMounted } from 'vue'
import Pagination from '../../components/Pagination.vue';

const props = defineProps({
    users: Object,
    pagination: Object,
});

onMounted(() => {
    console.log(props.users);
});
</script>