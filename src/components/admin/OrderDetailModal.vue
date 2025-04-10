<template>
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header text-bg-warning fw-900">
          <h1 class="modal-title fs-5" id="exampleModalLabel">訂單明細</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table shadow-lg mt-5">
            <thead class="table-info">
              <tr>
                <th>品名</th>
                <th>金額</th>
                <th>數量</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in orderdetail" :key="item.menu_id">
                <td>{{ item.name }}</td>
                <td>{{ item.price }}</td>
                <td>{{ item.quantity }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">確認</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    orderdetail: Array,
  },
};
</script>