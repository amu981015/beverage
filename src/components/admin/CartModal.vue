<template>
  <div class="modal fade" id="cardPage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">購物車</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group">
            <li class="list-group-item" v-for="cartItem in cart" :key="cartItem.menu_id">
              <div class="d-flex justify-content-between">
                <div>
                  {{ cartItem.name }} - ${{ cartItem.price }}
                </div>
                <div class="d-flex align-items-center">
                  <button class="btn btn-outline-secondary" type="button"
                    @click="cartItem.quantity > 1 && cartItem.quantity--">
                    -
                  </button>
                  <input type="number" class="form-control" :value="cartItem.quantity"
                    style="width: 60px; text-align: center;" aria-label="數量" aria-describedby="quantityHelp" />
                  <button class="btn btn-outline-secondary" type="button"
                    @click="cartItem.quantity < 6 && cartItem.quantity++">
                    +
                  </button>
                </div>
              </div>
              <button class="btn btn-danger btn-sm float-end" @click="$emit('remove-from-cart', cartItem)">
                刪除
              </button>
            </li>
          </ul>

          <div class="mt-3">
            <strong>總計: ${{ total }}</strong>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            我還要選
          </button>
          <button type="button" class="btn btn-primary" @click="$emit('order-success')">
            前往結帳
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    cart: Array,
    total: Number,
  },
};
</script>
