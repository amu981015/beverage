<template>
  <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="/background">
        <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="#" @click="$emit('load-module', 'bg-menu')">菜單管理</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" @click="$emit('load-module', 'bg-admin')">店長管理</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" @click="$emit('load-module', 'bg-order')">訂單管理</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" @click="$emit('load-module', 'bg-chart')">報表</a>
          </li>
        </ul>
        <div>
          <li class="dropdown d-none" id="s02_background_btn">
            <a style="display: inline" class="nav-link dropdown-toggle" href="#" role="button"
              data-bs-toggle="dropdown">
              <span class="h4 text-center" id="s02_username_text" style="color: var(--white)">{{ user.username || 'XXX'
                }}</span>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/">回首頁</a></li>
              <li><a class="dropdown-item" @click="$emit('logout')">登出</a></li>
            </ul>
          </li>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  props: {
    user: Object,
  },
};
</script>