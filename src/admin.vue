<template>
  <div id="app">
    <admin-navbar :user="user" @logout="logout" @load-module="loadModule"></admin-navbar>
    <div class="d-flex flex-column min-vh-100">
      <div id="content" class="flex-grow-1">
        <order-menu v-if="currentcontent === 'order-menu'" :user="user" :cart="cart" :city="city" :area="area"
          :store="store" :categorydata="categorydata" :menudata="menudata" @add-to-cart="addToCart"
          @order-success="orderSuccess" @category-select="categorySelect" @update:cart="updateCart"
          v-model:selectcity="selectcity" v-model:selectarea="selectarea" v-model:selectstore="selectstore">
        </order-menu>
        <order-list v-if="currentcontent === 'order-list'" :orders="orders" :orderdetail="orderdetail"
          @detail-order="detailOrder">
        </order-list>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import AdminNavbar from './components/admin/AdminNavbar.vue';
import OrderMenu from './components/admin/OrderMenu.vue';
import OrderList from './components/admin/OrderList.vue';

export default {
  name: 'AdminPage',
  components: {
    AdminNavbar,
    OrderMenu,
    OrderList,
  },
  data() {
    return {
      currentcontent: 'order-menu',
      mylink: '',
      menudata: [],
      categorydata: [],
      cart: [],
      selectcity: '',
      selectarea: '',
      selectstore: '',
      city: [],
      area: [],
      store: [],
      user: [],
      orders: [],
      orderdetail: [],
    };
  },
  created() {
    this.checkUid();
    this.loadCityData();
    this.loadCategoryData();
    setTimeout(() => this.loadOrders(), 1000);
  },
  watch: {
    selectcity(newValue) {
      if (newValue) this.loadAreaData(newValue);
    },
    selectarea(newValue) {
      if (newValue) this.loadStoreData(newValue);
    },
  },
  methods: {
    loadModule(module) {
      this.currentcontent = module;
    },
    async checkUid() {
      const uid = this.getCookie('Uid01');
      if (uid) {
        try {
          const response = await axios.post('http://laravel.local/api/checkuid', { uid01: uid });
          this.user = response.data.data;
          this.showDiscountInfo();
        } catch (error) {
          Swal.fire({ title: 'API介接錯誤', text: 'checkuid', icon: 'error' });
        }
      }
    },
    async loadCityData() {
      try {
        const response = await axios.get('http://laravel.local/api/selectcity');
        this.city = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'selectcity', icon: 'error' });
      }
    },
    async loadAreaData(city) {
      try {
        const response = await axios.post('http://laravel.local/api/selectarea', { city });
        this.area = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'selectarea', icon: 'error' });
      }
    },
    async loadStoreData(area) {
      try {
        const response = await axios.post('http://laravel.local/api/selectstore', { city: this.selectcity, area });
        this.store = response.data.data;
        this.selectstore = '';
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'selectstore', icon: 'error' });
      }
    },
    async loadCategoryData() {
      try {
        const response = await axios.get('http://laravel.local/api/category');
        this.categorydata = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'category', icon: 'error' });
      }
    },
    async categorySelect(category) {
      try {
        const response = await axios.post('http://laravel.local/api/menudata', { category });
        this.menudata = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'menudata', icon: 'error' });
      }
    },
    async loadOrders() {
      try {
        const response = await axios.post('http://laravel.local/api/usergetorder', { user_id: this.user.user_id });
        this.orders = response.data.data;
        if (!response.data.state) {
          Swal.fire({ title: '沒有訂購紀錄!', text: '快來點餐吧', icon: 'info' }).then(() => {
            this.currentcontent = 'order-menu';
          });
        }
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'usergetorder', icon: 'error' });
      }
    },
    async detailOrder(order_id) {
      try {
        const response = await axios.post('http://laravel.local/api/usergetdetailorder', { order_id });
        this.orderdetail = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'usergetdetailorder', icon: 'error' });
      }
    },
    addToCart(item) {
      let found = this.cart.find(cartItem => cartItem.menu_id === item.menu_id);
      if (found) {
        if (found.quantity < 6) {
          found.quantity++;
          Swal.fire({ title: '加入成功~', icon: 'success' });
        } else {
          Swal.fire({ title: '單杯飲料不可超出6杯', icon: 'error' });
        }
      } else {
        this.cart.push({ ...item, quantity: 1 });
      }

    },
    updateCart(newCart) {
      this.cart = newCart;
    },
    async orderSuccess() {
      if (this.total < 1000) {
        try {
          const response = await axios.post('http://laravel.local/api/createorder', {
            user_id: this.user.user_id,
            store_id: this.selectstore,
            total_price: this.total,
            order_details: this.cart.map(item => ({ menu_id: item.menu_id, quantity: item.quantity })),
          });
          Swal.fire({ title: '訂單已送出', icon: 'success' }).then(() => {
            window.location.href = '/admin';
          });
        } catch (error) {
          Swal.fire({ title: '訂單未能送出', text: 'createorder', icon: 'error' });
        }
      } else {
        Swal.fire({ title: '訂單金額超出1000', text: '1000以上訂單請來電訂購', icon: 'error' });
      }
    },
    logout() {
      this.setCookie('Uid01', '', -1);
      window.location.href = '/';
    },
    setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
      let expires = 'expires=' + d.toUTCString();
      document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
    },
    getCookie(cname) {
      let name = cname + '=';
      let decodedCookie = decodeURIComponent(document.cookie);
      let ca = decodedCookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
      }
      return '';
    },
    showDiscountInfo() {
      const discounts = {
        30: { text: '尊貴的黃金會員每杯飲料都有7折優惠!!!', discount: 0.7 },
        20: { text: '高級的白銀會員每杯飲料都有8折優惠!!!', discount: 0.8 },
        10: { text: '新進的銅牌會員每杯飲料都有9折優惠!!!', discount: 0.9 },
        0: { text: '普通的普通會員，提升到銅牌會員有9折優惠!!', discount: 1 },
      };
      const info = discounts[this.user.vip_level] || discounts[0];
      Swal.fire({ title: '優惠資訊', icon: 'info', text: info.text });
    },
  },
  computed: {
    total() {
      const discounts = { 30: 0.7, 20: 0.8, 10: 0.9, 0: 1 };
      const discount = discounts[this.user.vip_level] || 1;
      return this.cart.reduce((sum, item) => sum + item.price * discount * item.quantity, 0);
    },
  },
};
</script>

<style scoped>
.table-rwd {
  width: 100%;
}
</style>