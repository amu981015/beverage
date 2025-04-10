<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <select class="form-select form-select-lg mt-3" :value="selectcity"
          @input="$emit('update:selectcity', $event.target.value)">
          <option value="" selected disabled>---選擇縣市名稱---</option>
          <option v-for="item in city" :key="item.city" :value="item.city">{{ item.city }}</option>
        </select>
      </div>
      <div class="col-lg-3">
        <select class="form-select form-select-lg mt-3" :value="selectarea"
          @input="$emit('update:selectarea', $event.target.value)">
          <option value="" selected disabled>---選擇鄉鎮區名稱---</option>
          <option v-for="item in area" :key="item.area" :value="item.area">{{ item.area }}</option>
        </select>
      </div>
      <div class="col-lg-3">
        <select class="form-select form-select-lg mt-3" :value="selectstore"
          @input="$emit('update:selectstore', $event.target.value)">
          <option value="" selected disabled>---選擇門市---</option>
          <option v-for="item in store" :key="item.store_id" :value="item.store_id">店名:{{ item.name }} 地址:{{
            item.address }}</option>
        </select>
      </div>
      <div class="col-lg-3">
        <select class="form-select form-select-lg mt-3" :value="selectdate"
          @input="$emit('update:selectdate', $event.target.value)">
          <option value="" selected disabled>---選擇月份---</option>
          <option v-for="item in uniquedate" :key="item" :value="item">{{ item }}</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-9">
        <div class="card mt-3">
          <div class="card-header bg-info">
            <div v-if="filteredOrders.length > 0">
              <h3>查詢結果：</h3>
            </div>
            <div v-else>
              <h3>沒有符合條件的資料</h3>
            </div>
          </div>
          <div class="card-body" v-if="filteredOrders.length > 0">
            <table class="table shadow-lg mt-5 table-rwd">
              <thead class="table-warning">
                <tr>
                  <th>餐點名稱</th>
                  <th>數量</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in filteredOrders" :key="item.name">
                  <td>{{ item.name }}</td>
                  <td>{{ item.totalQuantity }}</td>
                </tr>
              </tbody>
            </table>
            <div class="h3 text-end">本月訂單總金額： {{ totalPrice }}</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 mt-3">
        <div v-for="(chartData, index) in chartDataList" :key="index" class="chart-container">
          <h2>{{ chartData.title }}</h2>
          <apexchart :options="chartData.options" :series="chartData.series" height="350"></apexchart>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts';

export default {
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    order: Array,
    city: Array,
    area: Array,
    store: Array,
    filteredOrders: Array,
    chartDataList: Array,
    selectcity: String,
    selectarea: String,
    selectstore: String,
    selectdate: String,
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