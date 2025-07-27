<template>
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="/">Tech Lab33</a>

    <!-- Navbar Search (optional) -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
      <!-- Optional search -->
    </form>

    <!-- Navbar Right Menu -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
      <li class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle"
          id="navbarDropdown"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fas fa-user fa-fw"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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
import { Link } from '@inertiajs/vue3';

defineOptions({
  name: 'Header',
});
</script>
