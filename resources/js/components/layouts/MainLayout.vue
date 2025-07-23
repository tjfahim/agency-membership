<template>
    <div>
        <HeaderComponent></HeaderComponent>
         <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
               <SidebarComponent></SidebarComponent>
            </div>
            <div id="layoutSidenav_content">
               <div id="app"></div>
                <main name="main">
                    <slot/>  
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2025</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import HeaderComponent from './HeaderComponent.vue';
import SidebarComponent from './SidebarComponent.vue';

defineOptions({
    name:'MainLayout'
});
</script>