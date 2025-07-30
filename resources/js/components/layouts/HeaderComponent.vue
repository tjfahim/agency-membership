<template>
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
   <div class="w-48 h-auto">
     <Link href="/"><img src="/public/assets/img/homepage_logo.png" class="object-contain cursor-pointer" alt="" ></img></Link>
   </div>
    <!-- Navbar Search (optional) -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
      <!-- Optional search -->
    </form>
    
    <!-- Navbar Right Menu -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
      <li class="nav-item dropdown flex space-x-6">
          <div class="!text-amber-600 mt-[25px] mr-0">{{ $page?.props?.user?.name }}</div>
        <a
          class="nav-link dropdown-toggle !flex justify-center items-center"
          id="navbarDropdown"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <img :src="$page.props.avatar" class="w-10 h-10 rounded-full my-2" alt="">
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <li>
            <Link
              href="/profile/setting"
              class="dropdown-item"
            >
              Profile
            </Link>
          </li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <Link
              href="/setting"
              class="dropdown-item"
            >
              Settings
            </Link>
          </li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <Link
              href="/logout"
              method="post"
              as="button"
              class="dropdown-item text-danger"
            >
              Logout
            </Link>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script setup lang="ts">
import { Link ,usePage} from '@inertiajs/vue3';
import {onMounted,ref} from 'vue';
import axios from 'axios';
defineOptions({
  name: 'Header',
});

const page = usePage();

</script>
