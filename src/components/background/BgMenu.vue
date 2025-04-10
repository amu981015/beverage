<template>
  <div class="container">
    <div class="card mt-3">
      <div class="card-header bg-info h3">
        菜單管理
        <div class="float-end">
          <button class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#createModal">新增菜單</button>
          <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editModal">修改菜單</button>
        </div>
      </div>
      <div class="card-body">
        <data-table :data="menu" :columns="columns"></data-table>
      </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header text-bg-warning fw-900">
            <h1 class="modal-title fs-5" id="createModalLabel">新增菜單</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="createModal_name" class="form-label">飲料名稱(8字以內)</label>
              <input type="text" class="form-control" id="createModal_name" v-model="menuName"
                :class="{ 'is-valid': createMenuNameCheck, 'is-invalid': !createMenuNameCheck }" />
              <div class="valid-feedback">{{ createMenuNameValue }}</div>
              <div class="invalid-feedback">{{ createMenuNameValue }}</div>
            </div>
            <div class="mb-3">
              <label for="createModal_price" class="form-label">價格(低於200)</label>
              <input type="number" class="form-control" id="createModal_price" v-model="menuPrice"
                :class="{ 'is-valid': createMenuPriceCheck, 'is-invalid': !createMenuPriceCheck }" />
              <div class="valid-feedback">符合規定</div>
              <div class="invalid-feedback">標價錯誤</div>
            </div>
            <div class="mb-3">
              <label for="createModal_category" class="form-label">類別</label>
              <select class="form-select" id="createModal_category" v-model="menuCategory"
                :class="{ 'is-valid': createMenuCategoryCheck, 'is-invalid': !createMenuCategoryCheck }">
                <option disabled value="">請選擇類別</option>
                <option v-for="item in uniqueCategories" :key="item" :value="item">{{ item }}</option>
                <option value="其他">其他</option>
              </select>
              <div class="valid-feedback">符合規定</div>
              <div class="invalid-feedback">請選擇類別</div>
              <div v-if="menuCategory === '其他'" class="mt-3">
                <label for="customCategory" class="form-label">請輸入自定義類別(5字以內)</label>
                <input type="text" class="form-control" id="customCategory" v-model="createCustomCategory"
                  :class="{ 'is-valid': createMenuCustomCategoryCheck, 'is-invalid': !createMenuCustomCategoryCheck }" />
                <div class="valid-feedback">符合規定</div>
                <div class="invalid-feedback">字數錯誤</div>
              </div>
            </div>
            <div class="mb-3">
              <label for="createModal_status" class="form-label">狀態</label>
              <select class="form-select" id="createModal_status" v-model="menuStatus"
                :class="{ 'is-valid': createMenuStatusCheck, 'is-invalid': !createMenuStatusCheck }">
                <option disabled value="">請選擇狀態</option>
                <option value="上架">上架</option>
                <option value="下架">下架</option>
              </select>
              <div class="valid-feedback">符合規定</div>
              <div class="invalid-feedback">請選擇狀態</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="handleCreateMenu">確認</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header text-bg-warning fw-900">
            <h1 class="modal-title fs-5" id="editModalLabel">修改菜單</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="select-menuName" class="form-label">飲料名稱</label>
              <select id="select-menuName" class="form-select form-select-lg mt-3" :value="selectedMenuid"
                @input="updateSelectedMenuid($event.target.value)">
                <option value="" selected disabled>---選擇菜單名稱---</option>
                <option v-for="item in menu">{{ item.name }}</option>
              </select>
            </div>
            <div v-if="editMenuData">
              <div class="mb-3">
                <label for="editModal_price" class="form-label">價格(低於200)</label>
                <input type="number" class="form-control" id="editModal_price" v-model="editMenuData.price"
                  :class="{ 'is-valid': editMenuPriceCheck, 'is-invalid': !editMenuPriceCheck }" />
                <div class="valid-feedback">符合規定</div>
                <div class="invalid-feedback">標價錯誤</div>
              </div>
              <div class="mb-3">
                <label for="editModal_category" class="form-label">類別</label>
                <select class="form-select" id="editModal_category" v-model="editMenuData.category"
                  :class="{ 'is-valid': editMenuCategoryCheck, 'is-invalid': !editMenuCategoryCheck }">
                  <option disabled value="">請選擇類別</option>
                  <option v-for="item in uniqueCategories" :key="item" :value="item">{{ item }}</option>
                  <option value="其他">其他</option>
                </select>
                <div class="valid-feedback">符合規定</div>
                <div class="invalid-feedback">請選擇類別</div>
                <div v-if="editMenuData.category === '其他'" class="mt-3">
                  <label for="editCustomCategory" class="form-label">請輸入自定義類別(5字以內)</label>
                  <input type="text" class="form-control" id="editCustomCategory" v-model="editCustomCategory"
                    :class="{ 'is-valid': editMenuCustomCategoryCheck, 'is-invalid': !editMenuCustomCategoryCheck }" />
                  <div class="valid-feedback">符合規定</div>
                  <div class="invalid-feedback">字數錯誤</div>
                </div>
              </div>
              <div class="mb-3">
                <label for="editModal_status" class="form-label">狀態</label>
                <select class="form-select" id="editModal_status" v-model="editMenuData.status"
                  :class="{ 'is-valid': editMenuStatusCheck, 'is-invalid': !editMenuStatusCheck }">
                  <option disabled value="">請選擇狀態</option>
                  <option value="上架">上架</option>
                  <option value="下架">下架</option>
                </select>
                <div class="valid-feedback">符合規定</div>
                <div class="invalid-feedback">請選擇狀態</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
              @click="handleUpdateMenu">確認修改</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net-bs5';

DataTable.use(DataTablesLib);

export default {
  components: {
    DataTable,
  },
  props: {
    menu: Array,
    selectedMenuid: String,
  },
  data() {
    return {
      menuName: '',
      menuPrice: 0,
      menuCategory: '',
      menuStatus: '',
      createCustomCategory: '',
      editCustomCategory: '',
      createMenuNameCheck: false,
      createMenuPriceCheck: false,
      createMenuCategoryCheck: false,
      createMenuCustomCategoryCheck: false,
      createMenuStatusCheck: false,
      createMenuNameValue: '請輸入',
      editMenuPriceCheck: false,
      editMenuCategoryCheck: false,
      editMenuCustomCategoryCheck: false,
      editMenuStatusCheck: false,
      editMenuData: null, columns: [
        { title: '餐點名稱', data: 'name' },
        { title: '價格', data: 'price' },
        { title: '類別', data: 'category' },
        { title: '狀態', data: 'status' },
      ],
    };
  },
  watch: {
    menuName(newValue) {
      if (newValue.length > 0 && newValue.length <= 8) {
        axios.post('http:          .then(response => {
            this.createMenuNameValue = response.data.message;
        this.createMenuNameCheck = response.data.state;
      })
          .catch(() => Swal.fire({ title: 'API介接錯誤', text: 'checkmenuuni', icon: 'error' }));
  } else {
    this.createMenuNameValue = '字數不符';
    this.createMenuNameCheck = false;
  }
},
menuPrice(newValue) {
  this.createMenuPriceCheck = newValue > 0 && newValue <= 200;
},
menuCategory(newValue) {
  if (!newValue) {
    this.createMenuCategoryCheck = false;
    this.createMenuCustomCategoryCheck = false;
  } else if (newValue !== '其他') {
    this.createCustomCategory = '';
    this.createMenuCategoryCheck = true;
    this.createMenuCustomCategoryCheck = true;
  } else {
    this.createMenuCategoryCheck = true;
    this.createMenuCustomCategoryCheck = false;
  }
},
createCustomCategory(newValue) {
  this.createMenuCustomCategoryCheck = this.menuCategory === '其他' && newValue.length > 0 && newValue.length <= 5;
},
menuStatus(newValue) {
  this.createMenuStatusCheck = !!newValue;
},
selectedMenuid(newValue) {
  const selected = this.menu.find(item => item.name === newValue);
  this.editMenuData = selected ? { ...selected } : null;
  this.editCustomCategory = '';
},
'editMenuData.price': function (newValue) {
  this.editMenuPriceCheck = newValue > 0 && newValue <= 200;
},
'editMenuData.category': function (newValue) {
  if (!newValue) {
    this.editMenuCategoryCheck = false;
    this.editMenuCustomCategoryCheck = false;
  } else if (newValue !== '其他') {
    this.editCustomCategory = '';
    this.editMenuCategoryCheck = true;
    this.editMenuCustomCategoryCheck = true;
  } else {
    this.editMenuCategoryCheck = true;
    this.editMenuCustomCategoryCheck = false;
  }
},
editCustomCategory(newValue) {
  this.editMenuCustomCategoryCheck = this.editMenuData?.category === '其他' && newValue.length > 0 && newValue.length <= 5;
},
'editMenuData.status': function (newValue) {
  this.editMenuStatusCheck = !!newValue;
},
  },
computed: {
  uniqueCategories() {
    return [...new Set(this.menu.map(item => item.category))];
  },
},
methods: {
  updateSelectedMenuid(value) {
    this.$emit('update:selectedMenuid', value);
  },
  handleCreateMenu() {
    if (this.createMenuNameCheck && this.createMenuPriceCheck && this.createMenuCategoryCheck && this.createMenuCustomCategoryCheck && this.createMenuStatusCheck) {
      const data = {
        name: this.menuName,
        price: this.menuPrice,
        category: this.menuCategory === '其他' && this.createCustomCategory ? this.createCustomCategory : this.menuCategory,
        status: this.menuStatus,
      };
      this.$emit('create-menu', data);
      this.menuName = '';
      this.menuPrice = 0;
      this.menuCategory = '';
      this.createCustomCategory = '';
      this.menuStatus = '';
    } else {
      Swal.fire({ title: '資料錯誤', icon: 'error' });
    }
  },
  handleUpdateMenu() {
    if (this.editMenuPriceCheck && this.editMenuCategoryCheck && this.editMenuCustomCategoryCheck && this.editMenuStatusCheck) {
      const data = {
        name: this.editMenuData.name,
        price: this.editMenuData.price,
        category: this.editMenuData.category === '其他' && this.editCustomCategory ? this.editCustomCategory : this.editMenuData.category,
        status: this.editMenuData.status,
      };
      console.log(data)
      this.$emit('update-menu', data);
      this.editMenuData = null; this.editCustomCategory = '';
    } else {
      Swal.fire({ title: '資料錯誤', icon: 'error' });
    }
  },
},
};
</script>

<style scoped>
.table-rwd {
  width: 100%;
}
</style>