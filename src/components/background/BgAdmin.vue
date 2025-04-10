<template>
  <div class="container">
    <div class="card mt-3">
      <div class="card-header bg-info h3">
        店長管理
        <div class="float-end">
          <button class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#createAdminModal">新增店長</button>
          <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editAdminModal">修改店長</button>
        </div>
      </div>
      <div class="card-body">
        <data-table :data="adminuser" :columns="columns"></data-table>
      </div>
    </div>

    <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header text-bg-warning fw-900">
            <h1 class="modal-title fs-5" id="createAdminModalLabel">新增店長</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="createAdminModal_store" class="form-label">門市編號</label>
              <input type="number" class="form-control" id="createAdminModal_store" v-model="adminStore"
                :class="{ 'is-valid': createAdminStoreCheck, 'is-invalid': !createAdminStoreCheck }" />
              <div class="valid-feedback">{{ createAdminStoreValue }}</div>
              <div class="invalid-feedback">{{ createAdminStoreValue }}</div>
            </div>
            <div class="mb-3">
              <label for="createAdminModal_name" class="form-label">用戶名(不超過12字符)</label>
              <input type="text" class="form-control" id="createAdminModal_name" v-model="adminName"
                :class="{ 'is-valid': createAdminNameCheck, 'is-invalid': !createAdminNameCheck }" />
              <div class="valid-feedback">{{ createAdminNameValue }}</div>
              <div class="invalid-feedback">{{ createAdminNameValue }}</div>
            </div>
            <div class="mb-3">
              <label for="createAdminModal_pwd" class="form-label">密碼</label>
              <input type="text" class="form-control" id="createAdminModal_pwd" v-model="adminPassword"
                :class="{ 'is-valid': createAdminPasswordCheck, 'is-invalid': !createAdminPasswordCheck }" />
              <div class="valid-feedback">符合規定</div>
              <div class="invalid-feedback">
                <p>1.至少 8 ~ 16 個字元</p>
                <p>2.大小寫字母</p>
                <p>3.至少一個數字</p>
                <p>4.特殊符號</p>
              </div>
            </div>
            <div class="mb-3">
              <label for="createAdminModal_email" class="form-label">信箱</label>
              <input type="text" class="form-control" id="createAdminModal_email" v-model="adminEmail"
                :class="{ 'is-valid': createAdminEmailCheck, 'is-invalid': !createAdminEmailCheck }" />
              <div class="valid-feedback">符合規定</div>
              <div class="invalid-feedback">不符合規定</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="handleCreateAdmin">確認</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header text-bg-warning fw-900">
            <h1 class="modal-title fs-5" id="editAdminModalLabel">修改店長</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="editAdminModal_username" class="form-label">用戶名</label>
              <input type="text" class="form-control" id="editAdminModal_username" :value="editAdminName"
                @input="$emit('update:editAdminName', $event.target.value)"
                :class="{ 'is-valid': selectAdminName != null, 'is-invalid': selectAdminName == null }" />
              <div class="valid-feedback">有此資料</div>
              <div class="invalid-feedback">無此資料</div>
            </div>
            <div v-if="selectAdminName">
              <div class="mb-3">
                <label for="editAdminModal_store" class="form-label">門市代碼</label>
                <input type="number" class="form-control" id="editAdminModal_store" v-model="selectAdminName.store_id"
                  :class="{ 'is-valid': editAdminStoreCheck, 'is-invalid': !editAdminStoreCheck }" />
                <div class="valid-feedback">{{ editAdminStoreValue }}</div>
                <div class="invalid-feedback">{{ editAdminStoreValue }}</div>
              </div>
              <div class="mb-3">
                <button class="btn btn-danger" @click="handleDeleteAdmin">註銷此帳號</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
              @click="handleUpdateAdmin">確認修改</button>
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
    adminuser: Array,
    editAdminName: String,
  },
  data() {
    return {
      adminStore: '',
      adminName: '',
      adminPassword: '',
      adminEmail: '',
      createAdminStoreCheck: false,
      createAdminStoreValue: '請輸入',
      createAdminNameCheck: false,
      createAdminNameValue: '請輸入',
      createAdminPasswordCheck: false,
      createAdminEmailCheck: false,
      editAdminStoreCheck: false,
      editAdminStoreValue: '請輸入',
      columns: [
        { title: '商店代碼', data: 'store_id' },
        { title: '商店代號', data: 'name' },
        { title: '帳號', data: 'username' },
        { title: '信箱', data: 'email' },
      ],
    };
  },
  watch: {
    adminStore(newValue) {
      if (newValue) {
        axios.post('http://laravel.local/api/checkstoreuni', { store_id: newValue })
          .then(response => {
            this.createAdminStoreValue = response.data.state ? `${response.data.message} ${response.data.data.name}` : response.data.message;
            this.createAdminStoreCheck = response.data.state;
          })
          .catch(() => Swal.fire({ title: 'API介接錯誤', text: 'checkstoreuni', icon: 'error' }));
      }
    },
    adminName(newValue) {
      if (newValue.length <= 12) {
        axios.post('http://laravel.local/api/checkuni', { username: newValue })
          .then(response => {
            this.createAdminNameValue = response.data.message;
            this.createAdminNameCheck = response.data.state;
          })
          .catch(() => Swal.fire({ title: 'API介接錯誤', text: 'checkuni', icon: 'error' }));
      } else {
        this.createAdminNameCheck = false;
      }
    },
    adminPassword(newValue) {
      const rules = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/;
      this.createAdminPasswordCheck = rules.test(newValue);
    },
    adminEmail(newValue) {
      const rules = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
      this.createAdminEmailCheck = rules.test(newValue);
    },
    'selectAdminName.store_id': function (newValue) {
      if (this.selectAdminName && newValue) {
        axios.post('http://laravel.local/api/checkstoreuni', { store_id: newValue })
          .then(response => {
            this.editAdminStoreValue = response.data.state ? `${response.data.message} ${response.data.data.name}` : response.data.message;
            this.editAdminStoreCheck = response.data.state;
          })
          .catch(() => Swal.fire({ title: 'API介接錯誤', text: 'checkstoreuni', icon: 'error' }));
      }
    },
  },
  computed: {
    selectAdminName() {
      return this.adminuser.find(item => item.username === this.editAdminName) || null;
    },
  },
  methods: {
    handleCreateAdmin() {
      if (this.createAdminStoreCheck && this.createAdminNameCheck && this.createAdminPasswordCheck && this.createAdminEmailCheck) {
        const data = {
          store_id: this.adminStore,
          username: this.adminName,
          password: this.adminPassword,
          email: this.adminEmail,
        };
        this.$emit('create-admin', data);
        this.adminStore = '';
        this.adminName = '';
        this.adminPassword = '';
        this.adminEmail = '';
      } else {
        Swal.fire({ title: '資料錯誤', icon: 'error' });
      }
    },
    handleUpdateAdmin() {
      if (this.editAdminStoreCheck) {
        const data = {
          store_id: this.selectAdminName.store_id,
          username: this.selectAdminName.username,
        };
        this.$emit('update-admin', data);
      } else {
        Swal.fire({ title: '資料錯誤', icon: 'error' });
      }
    },
    handleDeleteAdmin() {
      if (this.selectAdminName) {
        this.$emit('delete-admin', this.selectAdminName.username);
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