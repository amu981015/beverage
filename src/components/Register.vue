<template>
  <div class="container mt-5">
    <h1 class="text-center">會員註冊</h1>
    <div class="row">
      <div class="col-12 col-md-6">
        <div class="display-6 fw-900 my-3">會員守則</div>
        <p>
          1.會員資格：僅限年滿18歲的個人可註冊為會員，並需提供真實、準確的個人資料，確保帳戶安全。
        </p>
        <p>
          2.帳戶安全：會員應妥善保管自己的帳號和密碼，對帳號下的所有活動負責。若發現任何異常或未經授權的使用，應立即通知我們。
        </p>
        <p>
          3.訂單與付款：會員需按照網站提供的流程進行訂單確認與付款，並確保支付資訊的正確性及有效性。
        </p>
        <p>
          4.產品資訊：網站提供的產品資訊均以最真實的資料為準，但由於顯示設備差異，實際商品顏色、尺寸及規格可能與圖片有所不同，敬請理解。
        </p>
        <p>
          5.退換貨政策：會員如需退換貨，需遵循網站的退換貨政策，並提供有效的購買證明，具體條件請參閱我們的退換貨規定。
        </p>
        <p>
          6.隱私保護：我們將對會員的個人資料進行保密，並僅用於提供服務或促銷活動。詳情可參見我們的隱私政策。
        </p>
        <p>
          7.網站使用：會員應遵守本網站的使用條款，不得發布任何不當、非法或有害的內容。違規行為將導致帳號暫停或取消。
        </p>
        <div class="form-check form-switch mt-auto">
          <input type="checkbox" class="form-check-input" v-model="chk01"
            :class="{ 'is-valid': chk01, 'is-invalid': !chk01 }" />
          <div class="valid-feedback">已同意始能註冊!</div>
          <div class="invalid-feedback">須同意始能註冊!</div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="mb-3">
          <label for="username_reg" class="form-label">帳號</label>
          <input type="text" class="form-control" v-model="username" :class="{
            'is-valid': flag_username_reg,
            'is-invalid': !flag_username_reg,
          }" />
          <div class="valid-feedback">符合規則</div>
          <div class="invalid-feedback">{{ flag_username_value }}</div>
        </div>
        <div class="mb-3">
          <label for="password_reg" class="form-label">密碼</label>
          <input type="password" class="form-control" v-model="password" :class="{
            'is-valid': flag_password_reg,
            'is-invalid': !flag_password_reg,
          }" />
          <div class="valid-feedback">符合規則</div>
          <div class="invalid-feedback">
            至少 8 個字符，包含大寫、小寫、數字和特殊字符
          </div>
        </div>
        <div class="mb-3">
          <label for="re_password_reg" class="form-label">確認密碼</label>
          <input type="password" class="form-control" v-model="re_password" :class="{
            'is-valid': flag_re_password_reg,
            'is-invalid': !flag_re_password_reg,
          }" />
          <div class="valid-feedback">符合規則</div>
          <div class="invalid-feedback">不符合規則</div>
        </div>
        <div class="mb-3">
          <label for="email_reg" class="form-label">Email</label>
          <input type="email" class="form-control" v-model="email" :class="{
            'is-valid': flag_email_reg,
            'is-invalid': !flag_email_reg,
          }" />
          <div class="valid-feedback">符合規則</div>
          <div class="invalid-feedback">不符合規則</div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary" @click="reg_btn">
        註冊
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      username: "",
      password: "",
      re_password: "",
      email: "",
      chk01: false,
      flag_username_reg: false,
      flag_username_value: "請輸入",
      flag_password_reg: false,
      flag_re_password_reg: false,
      flag_email_reg: false,
    };
  },
  watch: {
    username(newValue) {
      if (newValue.length > 2 && newValue.length < 8) {
        this.checkUsername(newValue);
      } else {
        this.flag_username_value = "不符合規則";
        this.flag_username_reg = false;
      }
    },
    password(newValue) {
      const rules =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
      this.flag_re_password_reg = false;
      this.flag_password_reg = rules.test(newValue);
    },
    re_password(newValue) {
      this.flag_re_password_reg =
        this.flag_password_reg && newValue === this.password;
    },
    email(newValue) {
      const rules = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      this.flag_email_reg = rules.test(newValue);
    },
  },
  methods: {
    async checkUsername(username) {
      try {
        const response = await axios.post(
          "http://laravel.local/api/checkuni",
          { username }
        );
        this.flag_username_value = response.data.message;
        this.flag_username_reg = response.data.state;
      } catch (error) {
        Swal.fire({ title: "API介接錯誤", text: "checkuni", icon: "error" });
      }
    },
    async reg_btn() {
      if (
        this.flag_username_reg &&
        this.flag_password_reg &&
        this.flag_re_password_reg &&
        this.flag_email_reg &&
        this.chk01
      ) {
        try {
          const response = await axios.post(
            "http://laravel.local/api/register",
            {
              username: this.username,
              password: this.password,
              email: this.email,
            }
          );
          if (response.data.state) {
            Swal.fire({ title: response.data.message, icon: "success" }).then(
              () => {
                this.setCookie("Uid01", response.data.data.Uid01, 7);
                this.$router.push("/");
              }
            );
          } else {
            Swal.fire({ title: response.data.message, icon: "error" });
          }
        } catch (error) {
          Swal.fire({ title: "API介接錯誤", text: "register", icon: "error" });
        }
      } else {
        Swal.fire({ title: "欄位有錯誤，請修正", icon: "warning" });
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
