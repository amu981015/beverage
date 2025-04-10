<template>
  <div class="container mt-5">
    <h1 class="text-center">會員登入</h1>
    <div class="mb-3">
      <label for="username_login" class="form-label">帳號</label>
      <input type="text" class="form-control" v-model="loginUsername" />
    </div>
    <div class="mb-3">
      <label for="password_login" class="form-label">密碼</label>
      <input type="password" class="form-control" v-model="loginPassword" />
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary" @click="loginbtn">登入</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      loginUsername: "",
      loginPassword: "",
    };
  },
  methods: {
    async loginbtn() {
      try {
        const response = await axios.post("http:          username: this.loginUsername,
          password: this.loginPassword,
        });
      if (response.data.state) {
        Swal.fire({ title: response.data.message, icon: "success" }).then(() => {
          this.setCookie("Uid01", response.data.data.Uid01, 7);
          this.$emit('user-logged-in'); this.$router.push("/");
        });
      } else {
        Swal.fire({ title: response.data.message, icon: "error" });
      }
    } catch(error) {
      Swal.fire({ title: "API介接錯誤", text: "login", icon: "error" });
    }
  },
  setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  },
},
};
</script>