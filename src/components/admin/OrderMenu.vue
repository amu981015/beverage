<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-12">
        <select class="form-select form-select-lg mt-4" v-model="selectcity">
          <option value="" selected disabled>---選擇縣市名稱---</option>
          <option :value="item.city" v-for="(item, key) in city" :key="key">{{ item.city }}</option>
        </select>
        <select class="form-select form-select-lg mt-3" v-model="selectarea">
          <option value="" selected disabled>---選擇鄉鎮區名稱---</option>
          <option :value="item.area" v-for="(item, key) in area" :key="key">{{ item.area }}</option>
        </select>
        <select class="form-select form-select-lg mt-3" v-model="selectstore">
          <option value="" selected disabled>---選擇門市---</option>
          <option :value="item.store_id" v-for="item in store" :key="item.store_id">
            店名:{{ item.name }} 地址:{{ item.address }}
          </option>
        </select>
      </div>
      <div class="col-lg-8 col-12 mt-3" :class="{ 'd-none': !selectstore }">
        <ul class="nav nav-tabs">
          <li class="nav-item" v-for="item in categorydata" :key="item.category"
            @click="mylink = item.category; categorySelect()">
            <a class="nav-link" href="#" :class="{ 'active': mylink === item.category }">{{ item.category }}</a>
          </li>
        </ul>
        <ul class="list-group" v-if="mylink">
          <template v-for="item in menudata" :key="item.menu_id">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              {{ item.name }} - {{ item.price }} 元
              <button class="btn btn-primary ms-2" @click="$emit('add-to-cart', item)">加入購物車</button>
            </li>
          </template>
        </ul>
      </div>
    </div>
    <cart-modal :cart="cart" :total="total" @order-success="$emit('order-success')"
      @remove-from-cart="removeFromCart"></cart-modal>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import CartModal from './CartModal.vue';

export default {
  components: { CartModal },
  props: {
    user: Object,
    cart: Array,
    city: Array,
    area: Array,
    store: Array,
    categorydata: Array,
    menudata: Array,
  },
  data() {
    return {
      mylink: '',
      selectcity: '',
      selectarea: '',
      selectstore: '',
    };
  },
  watch: {
    selectcity(newValue) {
      this.$emit('update:selectcity', newValue);
    },
    selectarea(newValue) {
      this.$emit('update:selectarea', newValue);
    },
    selectstore(newValue) {
      this.$emit('update:selectstore', newValue);
    },
  },
  methods: {
    categorySelect() {
      this.$emit('category-select', this.mylink);
    },
    removeFromCart(cartItem) {
      this.$emit('update:cart', this.cart.filter(item => item.menu_id !== cartItem.menu_id));
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