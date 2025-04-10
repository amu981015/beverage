<template>
  <div class="container">
    <div class="card mt-3">
      <div class="card-header bg-info">訂單明細</div>
      <div class="card-body">
        <table id="orderdetailinfo" class="table shadow-lg mt-5 table-rwd">
          <thead class="table-info">
            <tr>
              <th>編號</th>
              <th>店家</th>
              <th>總金額</th>
              <th>狀態</th>
              <th>點餐時間</th>
              <th>功能</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in orders" :key="item.order_id">
              <td>{{ item.order_id }}</td>
              <td>{{ item.name }} {{ item.address }}</td>
              <td>{{ item.total_price }}</td>
              <td>{{ item.status }}</td>
              <td>{{ item.order_date }}</td>
              <td>
                <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#detailModal"
                  @click="$emit('detail-order', item.order_id)">
                  詳細資料
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <order-detail-modal :orderdetail="orderdetail"></order-detail-modal>
  </div>
</template>

<script>
import DataTable from "datatables.net-bs5";
import OrderDetailModal from "./OrderDetailModal.vue";

export default {
  components: { OrderDetailModal },
  props: {
    orders: Array,
    orderdetail: Array,
  },
  mounted() {
    this.$nextTick(() => {
      new DataTable("#orderdetailinfo", { order: [[4, "desc"]] });
    });
  },
};
</script>

<style scoped>
.table-rwd {
  width: 100%;
}
</style>
