<template>
  <div id="app">
    <store-bg-navbar :user="user" @logout="logout" @load-module="loadModule"></store-bg-navbar>
    <div class="d-flex flex-column min-vh-100">
      <div id="content" class="flex-grow-1">
        <bg-order v-if="currentcontent === 'bg-order'" :user="user" :order="order"
          :filter-status-data="filterStatusData" :selectstatus="selectstatus"
          @update:selectstatus="selectstatus = $event" @status-edit="statusEdit">
        </bg-order>
        <bg-chart v-if="currentcontent === 'bg-chart'" :order="order" :filtered-orders="filteredOrders"
          :chart-data-list="chartDataList" :selectdate="selectdate" @update:selectdate="selectdate = $event">
        </bg-chart>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import StoreBgNavbar from './components/storebackground/StoreBgNavbar.vue';
import BgOrder from './components/storebackground/BgOrder.vue';
import BgChart from './components/storebackground/BgChart.vue';

export default {
  name: 'StoreBackgroundPage',
  components: {
    StoreBgNavbar,
    BgOrder,
    BgChart,
  },
  data() {
    return {
      currentcontent: 'bg-order',
      user: [],
      order: [],
      filterStatusData: [],
      selectstatus: '全部',
      selectdate: '',
      filteredOrders: [],
      chartDataList: [
        {
          title: '柱狀圖',
          options: {
            chart: { type: 'bar', id: 'chart-1', toolbar: { show: false } },
            xaxis: { categories: [], title: { text: '餐點名稱' } },
            yaxis: { title: { text: '銷售量' } },
            title: { text: '本月銷售量 - 柱狀圖', align: 'center' },
          },
          series: [{ name: '銷售量', data: [] }],
        },
        {
          title: '折線圖',
          options: {
            chart: { type: 'line', id: 'chart-2', toolbar: { show: false } },
            xaxis: { categories: [], title: { text: '餐點名稱' } },
            yaxis: { title: { text: '銷售量' } },
            title: { text: '本月銷售量 - 折線圖', align: 'center' },
          },
          series: [{ name: '銷售量', data: [] }],
        },
        {
          title: '圓環圖',
          options: {
            chart: { type: 'donut', id: 'chart-3', toolbar: { show: false } },
            labels: [],
            title: { text: '本月銷售量 - 圓環圖', align: 'center' },
          },
          series: [],
        },
      ],
    };
  },
  created() {
    this.checkUid();
    setTimeout(() => this.loadOrderData(), 500);
  },
  watch: {
    selectstatus() {
      this.updateFilterStatusData();
    },
    order() {
      this.updateFilterStatusData();
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
    async loadOrderData() {
      try {
        const response = await axios.post('http://laravel.local/api/getorderdata', { store_id: this.user.store_id });
        this.order = response.data.data;
        this.filterStatusData = this.order;
      } catch (error) {
        Swal.fire({ title: 'API介接錯誤', text: 'getorderdata', icon: 'error' });
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