<template>
  <div class="container">
    <div class="row m-3" id="menu-container">
      <div class="col-12 text-center" v-if="menu.length === 0">
        <p class="h4">正在加載菜單...</p>
      </div>
      <template v-else>
        <div class="col-md-6 col-lg-4 mb-4" v-for="(items, category) in menuCategories" :key="category">
          <div class="h3">{{ category }}</div>
          <ul>
            <li class="row" v-for="item in items" :key="item.id">
              <div class="h4">{{ item.name }} <span>{{ item.price }}</span></div>
            </li>
          </ul>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      menu: [],
      menuCategories: {},
    };
  },
  mounted() {
    this.getMenu();
  },
  methods: {
    async getMenu() {
      try {
        const response = await axios.get("http://laravel.local/api/allupmenu");
        if (response.data && response.data.data) {
          this.menu = response.data.data;
          this.menuCategories = this.menu.reduce((acc, item) => {
            acc[item.category] = acc[item.category] || [];
            acc[item.category].push(item);
            return acc;
          }, {});
        }
      } catch (error) {
        Swal.fire({ title: "API介接錯誤", text: "allupmenu", icon: "error" });
      }
    },
  },
};
</script>