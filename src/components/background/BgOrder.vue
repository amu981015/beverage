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
        <select class="form-select form-select-lg mt-3" :value="selectstatus"
          @input="$emit('update:selectstatus', $event.target.value)">
          <option value="全部">全部</option>
          <option value="已支付">已支付</option>
          <option value="待支付">待支付</option>
          <option value="已取消">已取消</option>
        </select>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-header bg-info h3">訂單管理</div>
      <div class="card-body">
        <table class="table shadow-lg mt-5 table-rwd">
          <thead class="table-warning">
            <tr>
              <th class="align-middle">訂單編號</th>
              <th class="align-middle">餐點名稱</th>
              <th class="align-middle">狀態</th>
              <th class="align-middle">數量</th>
              <th class="align-middle">餐點單價</th>
              <th class="align-middle">
                訂餐時間
                <button class="btn" @click="sortTable('order_date')" v-if="!sortAsc"><i
                    class="fa-solid fa-arrow-up"></i></button>
                <button class="btn" @click="sortTable('order_date')" v-if="sortAsc"><i
                    class="fa-solid fa-arrow-down"></i></button>
              </th>
              <th class="align-middle">功能</th>
            </tr>
          </thead>
          <tbody>
            <div class="h3 text-center" v-if="!filterStatusData || filterStatusData.length === 0">請選擇資料</div>
            <tr v-for="item in filterStatusDataforPage[currentPage]">
              <td>{{ item.order_id }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.status }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.total_price }}</td>
              <td>{{ item.order_date }}</td>
              <td>
                <button class="btn btn-success me-2" v-if="item.status !== '已支付'"
                  @click="$emit('status-edit', item.order_id, '已支付')">付款完成</button>
                <button class="btn btn-warning me-2" v-if="item.status !== '待支付'"
                  @click="$emit('status-edit', item.order_id, '待支付')">等待支付</button>
                <button class="btn btn-danger me-2" v-if="item.status !== '已取消'"
                  @click="$emit('status-edit', item.order_id, '已取消')">訂單取消</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row my-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <li class="page-item" v-if="currentPage !== 0">
            <a class="page-link" aria-label="Previous" @click="currentPage -= 1"><span aria-hidden="true">«</span></a>
          </li>
          <li class="page-item" :class="{ 'active': currentPage === key }"
            v-for="(item, key) in filterStatusDataforPage" :key="key">
            <span class="page-link" @click="currentPage = key">{{ key + 1 }}</span>
          </li>
          <li class="page-item" v-if="currentPage !== filterStatusDataforPage.length - 1">
            <a class="page-link" aria-label="Next" @click="currentPage += 1"><span aria-hidden="true">»</span></a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    order: Array,
    city: Array,
    area: Array,
    store: Array,
    filterStatusData: Array,
    selectcity: String,
    selectarea: String,
    selectstore: String,
    selectstatus: String,
  },
  data() {
    return {
      currentPage: 0,
      sortBy: 'order_date',
      sortAsc: false,
    };
  },
  methods: {
    sortTable(column) {
      if (this.sortBy === column) {
        this.sortAsc = !this.sortAsc;
      } else {
        this.sortBy = column;
        this.sortAsc = true;
      }
    },
  },
  computed: {
    sortedOrders() {
      if (this.filterStatusData && this.filterStatusData.length > 0) {
        return this.filterStatusData.slice().sort((a, b) => {
          const dateA = new Date(a[this.sortBy]);
          const dateB = new Date(b[this.sortBy]);
          return this.sortAsc ? dateA - dateB : dateB - dateA;
        });
      }
      return [];
    },
    filterStatusDataforPage() {
      if (this.sortedOrders.length > 0) {
        const perPage = 10;
        const pages = [];
        this.sortedOrders.forEach((item, index) => {
          const page = Math.floor(index / perPage);
          if (!pages[page]) pages[page] = [];
          pages[page].push(item);
        });
        return pages;
      }
      return [];
    },
  },
};
</script>

<style scoped>
.table-rwd {
  width: 100%;
}
</style>