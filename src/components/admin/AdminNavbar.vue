<template>
  <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#" @click="$emit('load-module', 'order-menu')">
        <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="#" @click="$emit('load-module', 'order-menu')">點餐</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" @click="$emit('load-module', 'order-list')">菜單</a>
          </li>
        </ul>
        <button type="button" class="btn me-3" id="cartbtn" data-bs-toggle="modal" data-bs-target="#cardPage"
          :class="{ 'd-none': currentcontent === 'order-list' }">
          <i class="fa-solid text-white fa-cart-shopping fa-2x"></i>
        </button>
        <div>
          <li class="nav-item dropdown" id="s02_background_btn">
            <a style="display: inline" class="nav-link dropdown-toggle" href="#" role="button"
              data-bs-toggle="dropdown">
              <span class="h4 text-center" id="s02_username_text" style="color: var(--white)">{{ user.username }}</span>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/">回首頁</a></li>
              <li><a id="logout_btn" class="dropdown-item" @click="$emit('logout')">登出</a></li>
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
    currentcontent: String,
  },
};
</script>