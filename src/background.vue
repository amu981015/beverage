<template>
  <div id="app">
    <bg-navbar :user="user" @logout="logout" @load-module="loadModule"></bg-navbar>
    <div class="d-flex flex-column min-vh-100">
      <div id="content" class="flex-grow-1">
        <bg-menu v-if="currentcontent === 'bg-menu'" :menu="menu" :selected-menuid="selectedMenuid"
          @update:selected-menuid="selectedMenuid = $event" @create-menu="createMenu" @update-menu="updateMenu">
        </bg-menu>
        <bg-admin v-if="currentcontent === 'bg-admin'" :adminuser="adminuser" :edit-admin-name="editAdminName"
          @update:edit-admin-name="editAdminName = $event" @create-admin="createAdmin" @update-admin="updateAdmin"
          @delete-admin="deleteAdmin">
        </bg-admin>
        <bg-order v-if="currentcontent === 'bg-order'" :order="order" :city="city" :area="area" :store="store"
          :filter-status-data="filterStatusData" :selectcity="selectcity" :selectarea="selectarea"
          :selectstore="selectstore" :selectstatus="selectstatus" @update:selectcity="selectcity = $event"
          @update:selectarea="selectarea = $event" @update:selectstore="selectstore = $event"
          @update:selectstatus="selectstatus = $event" @status-edit="statusEdit">
        </bg-order>
        <bg-chart v-if="currentcontent === 'bg-chart'" :order="order" :city="city" :area="area" :store="store"
          :filtered-orders="filteredOrders" :chart-data-list="chartDataList" :selectcity="selectcity"
          :selectarea="selectarea" :selectstore="selectstore" :selectdate="selectdate"
          @update:selectcity="selectcity = $event" @update:selectarea="selectarea = $event"
          @update:selectstore="selectstore = $event" @update:selectdate="selectdate = $event">
        </bg-chart>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import BgNavbar from './components/background/BgNavbar.vue';
import BgMenu from './components/background/BgMenu.vue';
import BgAdmin from './components/background/BgAdmin.vue';
import BgOrder from './components/background/BgOrder.vue';
import BgChart from './components/background/BgChart.vue';

export default {
  name: 'BackgroundPage',
  components: {
    BgNavbar,
    BgMenu,
    BgAdmin,
    BgOrder,
    BgChart,
  },
  data() {
    return {
      currentcontent: 'bg-menu',
      user: [],
      menu: [],
      adminuser: [],
      order: [],
      city: [],
      area: [],
      store: [],
      filterStatusData: [],
      selectcity: '',
      selectarea: '',
      selectstore: '',
      selectstatus: '全部',
      selectdate: '',
      filteredOrders: [],
      selectedMenuid: '',
      editAdminName: '',
      chartDataList: [
        {
          title: '柱狀圖',
          options: { chart: { type: 'bar', id: 'chart-1', toolbar: { show: false } }, xaxis: { categories: [], title: { text: '餐點名稱' } }, yaxis: { title: { text: '銷售量' } }, title: { text: '本月銷售量 - 柱狀圖', align: 'center' } },
          series: [{ name: '銷售量', data: [] }],
        },
        {
          title: '折線圖',
          options: { chart: { type: 'line', id: 'chart-2', toolbar: { show: false } }, xaxis: { categories: [], title: { text: '餐點名稱' } }, yaxis: { title: { text: '銷售量' } }, title: { text: '本月銷售量 - 折線圖', align: 'center' } },
          series: [{ name: '銷售量', data: [] }],
        },
        {
          title: '圓環圖',
          options: { chart: { type: 'donut', id: 'chart-3', toolbar: { show: false } }, labels: [], title: { text: '本月銷售量 - 圓環圖', align: 'center' } },
          series: [],
        },
      ],
    };
  },
  created() {
    this.checkUid();
    this.fetchCities();
    setTimeout(() => {
      this.searchMenu();
      this.searchAdmin();
    }, 700);
  },
  watch: {
    selectcity() {
      this.selectarea = '';
      this.selectstore = '';
      this.order = [];
      this.filterStatusData = [];
      this.fetchAreas();
    },
    selectarea() {
      this.selectstore = '';
      this.order = [];
      this.filterStatusData = [];
      this.fetchStores();
    },
    selectstatus() {
      this.updateFilterStatusData();
    },
    order() {
      this.updateFilterStatusData();
    },
    selectstore() {
      this.loadOrderData();
    },
    selectdate(newValue) {
      this.filterOrders(newValue);
      this.updateCharts();
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
          if (this.user.vip_level < 100) {
            Swal.fire({ title: '權限不足', icon: 'error' }).then(() => {
              window.location.href = '/';
            });
          } else {
            document.getElementById('s02_background_btn').classList.remove('d-none');
            document.getElementById('s02_username_text').textContent = this.user.username;
          }
        } catch (error) {
          Swal.fire({ title: 'API介接錯誤', text: 'checkuid', icon: 'error' });
        }
      }
    },
    async searchMenu() {
      try {
        const response = await axios.get('http://laravel.local/api/allmenu');
        this.menu = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'allmenu', icon: 'error' });
      }
    },
    async createMenu(menuData) {
      try {
        const response = await axios.post('http://laravel.local/api/createmenu', menuData);
        Swal.fire({ title: response.data.message, icon: 'success' }).then(() => {
          this.searchMenu();
        });
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'createmenu', icon: 'error' });
      }
    },
    async updateMenu(menuData) {
      try {
        const result = await Swal.fire({
          title: '確定修改嗎?',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: '取消',
          confirmButtonText: '確定修改',
          icon: 'question',
        });
        if (result.isConfirmed) {
          const response = await axios.post('http://laravel.local/api/editmenu', menuData);
          Swal.fire({ title: response.data.message, icon: 'success' }).then(() => this.searchMenu());
        }
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'editmenu', icon: 'error' });
      }
    },
    async searchAdmin() {
      try {
        const response = await axios.get('http://laravel.local/api/getalluser');
        this.adminuser = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'getalluser', icon: 'error' });
      }
    },
    async createAdmin(adminData) {
      try {
        const response = await axios.post('http://laravel.local/api/createadmindata', adminData);
        Swal.fire({ title: response.data.message, icon: 'success' }).then(() => {
          this.searchAdmin();
        });
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'createadmindata', icon: 'error' });
      }
    },
    async updateAdmin(adminData) {
      try {
        const result = await Swal.fire({
          title: '確定修改嗎?',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: '取消',
          confirmButtonText: '確定修改',
          icon: 'question',
        });
        if (result.isConfirmed) {
          const response = await axios.post('http://laravel.local/api/editadmindata', adminData);
          Swal.fire({ title: response.data.message, icon: 'success' }).then(() => {
            this.editAdminName = '';
            this.searchAdmin();
          });
        }
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'editadmindata', icon: 'error' });
      }
    },
    async deleteAdmin(username) {
      const confirm = await Swal.fire({
        title: '確定註銷此帳號?',
        showCancelButton: true,
        confirmButtonColor: '#F00',
        cancelButtonColor: '#d33',
        cancelButtonText: '取消',
        confirmButtonText: '註銷',
        icon: 'warning',
      });
      if (!confirm.isConfirmed) return;
      const confirm2 = await Swal.fire({
        title: '重要須知',
        text: '註銷動作無法返回，確定註銷?',
        showCancelButton: true,
        confirmButtonColor: '#F00',
        cancelButtonColor: '#d33',
        cancelButtonText: '取消',
        confirmButtonText: '註銷',
        icon: 'warning',
      });
      if (!confirm2.isConfirmed) return;
      const confirm3 = await Swal.fire({
        title: '最終返回機會',
        showCancelButton: true,
        confirmButtonColor: '#F00',
        cancelButtonColor: '#d33',
        cancelButtonText: '取消',
        confirmButtonText: '註銷',
        icon: 'warning',
      });
      if (confirm3.isConfirmed) {
        try {
          const response = await axios.post('http://laravel.local/api/deleteadmindata', { username });
          Swal.fire({ title: response.data.message, icon: 'success' }).then(() => this.searchAdmin());
        } catch (error) {
          Swal.fire({ title: 'API介接錯誤', text: 'deleteadmindata', icon: 'error' });
        }
      }
    },
    async fetchCities() {
      try {
        const response = await axios.get('http://laravel.local/api/selectcity');
        this.city = response.data.data;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'selectcity', icon: 'error' });
      }
    },
    async fetchAreas() {
      if (this.selectcity) {
        try {
          const response = await axios.post('http://laravel.local/api/selectarea', { city: this.selectcity });
          this.area = response.data.data;
        } catch (error) {
          Swal.fire({ title: 'API介接錯誤', text: 'selectarea', icon: 'error' });
        }
      }
    },
    async fetchStores() {
      if (this.selectarea) {
        try {
          const response = await axios.post('http://laravel.local/api/selectstore', { city: this.selectcity, area: this.selectarea });
          this.store = response.data.data;
        } catch (error) {
          Swal.fire({ title: 'API介接錯誤', text: 'selectstore', icon: 'error' });
        }
      }
    },
    async loadOrderData() {
      if (this.selectstore) {
        try {
          const response = await axios.post('http://laravel.local/api/getorderdata', { store_id: this.selectstore });
          this.order = response.data.data;
          this.updateFilterStatusData();
        } catch (error) {
          Swal.fire({ title: 'API介接錯誤', text: 'getorderdata', icon: 'error' });
        }
      }
    },
    async statusEdit(order_id, status) {
      try {
        await axios.post('http://laravel.local/api/editorderstatusdata', { order_id, status });
        this.selectstatus = status;
        await this.loadOrderData();
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'editorderstatusdata', icon: 'error' });
      }
    },
    updateFilterStatusData() {
      if (this.selectstatus === '全部') {
        this.filterStatusData = this.order;
      } else {
        this.filterStatusData = this.order.filter(item => item.status === this.selectstatus);
      }
    },
    filterOrders(month) {
      if (!month) {
        this.filteredOrders = [];
        return;
      }
      const filtered = this.order.filter(order => order.status === '已支付' && order.order_date.startsWith(month));
      const processedOrderIds = new Set();
      const grouped = filtered.reduce((acc, order) => {
        if (!acc[order.name]) {
          acc[order.name] = { name: order.name, totalQuantity: 0, totalPrice: 0 };
        }
        acc[order.name].totalQuantity += order.quantity;
        if (!processedOrderIds.has(order.order_id)) {
          acc[order.name].totalPrice += order.total_price;
          processedOrderIds.add(order.order_id);
        }
        return acc;
      }, {});
      this.filteredOrders = Object.values(grouped);
    },
    updateCharts() {
      this.chartDataList.forEach(chartData => {
        const newOptions = { ...chartData.options };
        let newSeries = chartData.options.chart.type === 'donut' ? [] : [{ ...chartData.series[0], data: [] }];
        if (newOptions.xaxis) newOptions.xaxis.categories = [];
        if (newOptions.labels) newOptions.labels = [];
        this.filteredOrders.forEach(item => {
          if (newOptions.xaxis) newOptions.xaxis.categories.push(item.name);
          if (newOptions.labels) newOptions.labels.push(item.name);
          if (chartData.options.chart.type === 'donut') {
            newSeries.push(item.totalQuantity);
          } else {
            newSeries[0].data.push(item.totalQuantity);
          }
        });
        chartData.options = newOptions;
        chartData.series = newSeries;
      });
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
  },
  computed: {
    uniquedate() {
      const filtered = this.order.filter(order => order.status === '已支付');
      const uniqueMonths = [...new Set(filtered.map(item => {
        const date = new Date(item.order_date);
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
      }))];
      return uniqueMonths.sort((a, b) => new Date(b + '-01') - new Date(a + '-01'));
    },
    totalPrice() {
      return this.filteredOrders.reduce((sum, item) => sum + item.totalPrice, 0);
    },
  },
};
</script>

<style scoped>
.table-rwd {
  width: 100%;
}

.chart-container {
  margin-bottom: 20px;
}
</style>