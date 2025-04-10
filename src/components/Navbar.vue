<template>
  <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#" @click="$router.push('/')">
        <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="#" @click="$router.push('/')">首頁</a></li>
          <li class="nav-item"><a class="nav-link active" href="#" @click="$router.push('/menu')">菜單</a></li>
          <li class="nav-item"><a class="nav-link active" href="#" @click="$router.push('/faq')">常見問題</a></li>
          <li class="nav-item"><a class="nav-link active" href="#" @click="$router.push('/about')">關於我們</a></li>
          <li class="nav-item"><a class="nav-link active" href="#" @click="$router.push('/storemap')">門市據點</a></li>
        </ul>
        <div>
          <li class="nav-item dropdown d-none" id="s02_background_btn">
            <a style="display: inline" class="nav-link dropdown-toggle" href="#" role="button"
              data-bs-toggle="dropdown">
              <span class="h4" id="s02_username_text" style="color: var(--white)">{{ user.username || 'XXX' }}</span>
            </a>
            <ul class="dropdown-menu">
              <li><a id="background" href="admin/background.html" class="dropdown-item d-none">總管理後臺</a></li>
              <li><a id="storebackground" href="admin/storebackground.html" class="dropdown-item d-none">管理後臺</a></li>
              <li><a id="order_btn" href="admin/admin.html" class="dropdown-item d-none">點餐去</a></li>
              <li><a id="s02_logout_btn" class="dropdown-item" @click="logout">登出</a></li>
            </ul>
          </li>
          <a class="btn" href="#" style="background-color: var(--tropics); color: var(--white)"
            @click="$router.push('/register')" id="s02_reg_btn">註冊</a>
          <a class="btn" href="#" style="background-color: var(--endeavor); color: var(--white)"
            @click="$router.push('/login')" id="s02_login_btn">登入</a>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      user: {},
    };
  },
  created() {
    this.checkUid();
  },
  watch: {
    '$route'(to, from) {
      this.checkUid();
    }
  },
  methods: {
    async checkUid() {
      const uid = this.getCookie("Uid01");
      if (uid) {
        try {
          const response = await axios.post("http://laravel.local/api/checkuid", { uid01: uid });
          this.handleCheckUidResponse(response.data);
        } catch (error) {
          Swal.fire({ title: "API介接錯誤", text: "checkuid", icon: "error" });
        }
      } else {
        this.resetUserState();
      }
    },
    handleCheckUidResponse(data) {
      this.user = data.data || {};
      if (data.state) {
        const regBtn = document.getElementById("s02_reg_btn");
        const loginBtn = document.getElementById("s02_login_btn");
        const usernameText = document.getElementById("s02_username_text");
        const logoutBtn = document.getElementById("s02_logout_btn");
        const backgroundBtn = document.getElementById("s02_background_btn");

        if (regBtn) regBtn.style.display = "none";
        if (loginBtn) loginBtn.style.display = "none";
        if (usernameText) usernameText.textContent = this.user.username || "XXX";
        if (logoutBtn) logoutBtn.classList.remove("d-none");
        if (backgroundBtn) backgroundBtn.classList.remove("d-none");

        if (this.user.vip_level === 1000) {
          const backgroundLink = document.getElementById("background");
          if (backgroundLink) backgroundLink.classList.remove("d-none");
        } else if (this.user.vip_level === 100) {
          const storeBackgroundLink = document.getElementById("storebackground");
          if (storeBackgroundLink) storeBackgroundLink.classList.remove("d-none");
        } else {
          const orderBtn = document.getElementById("order_btn");
          if (orderBtn) orderBtn.classList.remove("d-none");
        }
      }
    },
    resetUserState() {
      this.user = {};
      const regBtn = document.getElementById("s02_reg_btn");
      const loginBtn = document.getElementById("s02_login_btn");
      const logoutBtn = document.getElementById("s02_logout_btn");
      const backgroundBtn = document.getElementById("s02_background_btn");
      const backgroundLink = document.getElementById("background");
      const storeBackgroundLink = document.getElementById("storebackground");
      const orderBtn = document.getElementById("order_btn");

      if (regBtn) regBtn.style.display = "inline-block";
      if (loginBtn) loginBtn.style.display = "inline-block";
      if (logoutBtn) logoutBtn.classList.add("d-none");
      if (backgroundBtn) backgroundBtn.classList.add("d-none");
      if (backgroundLink) backgroundLink.classList.add("d-none");
      if (storeBackgroundLink) storeBackgroundLink.classList.add("d-none");
      if (orderBtn) orderBtn.classList.add("d-none");
    },
    logout() {
      this.setCookie("Uid01", "", -1);
      this.resetUserState();
      this.$router.push("/");
    },
    setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
      let expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    },
    getCookie(cname) {
      let name = cname + "=";
      let decodedCookie = decodeURIComponent(document.cookie);
      let ca = decodedCookie.split(";");
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
      }
      return "";
    },
  },
};
</script>