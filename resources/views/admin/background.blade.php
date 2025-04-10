<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>點餐系統 - 管理後台</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
  <link href="https://cdn.jsdelivr.net/npm/datatables.net-dt@1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>

<body>
  <div id="app">
    @verbatim
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.background') }}">
          <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" href="#" @click="loadModule('bg-menu')">菜單管理</a></li>
            <li class="nav-item"><a class="nav-link active" href="#" @click="loadModule('bg-admin')">店長管理</a></li>
            <li class="nav-item"><a class="nav-link active" href="#" @click="loadModule('bg-order')">訂單管理</a></li>
            <li class="nav-item"><a class="nav-link active" href="#" @click="loadModule('bg-chart')">報表</a></li>
          </ul>
          <div>
            <li class="nav-item dropdown d-none" id="s02_background_btn">
              <a style="display: inline" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <span class="h4 text-center" id="s02_username_text" style="color: var(--white)">XXX</span>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/">回首頁</a></li>
                <li><a id="logout_btn" class="dropdown-item">登出</a></li>
              </ul>
            </li>
          </div>
        </div>
      </div>
    </nav>

    <div class="d-flex flex-column min-vh-100">
      <div id="content" class="flex-grow-1">
        <!-- 菜單管理 -->
        <div v-if="currentcontent === 'bg-menu'">
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

            <!-- 新增菜單 Modal -->
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
                      <input type="text" class="form-control" id="createModal_name" v-model="menuName" :class="{ 'is-valid': createMenuNameCheck, 'is-invalid': !createMenuNameCheck }" />
                      <div class="valid-feedback">{{ createMenuNameValue }}</div>
                      <div class="invalid-feedback">{{ createMenuNameValue }}</div>
                    </div>
                    <div class="mb-3">
                      <label for="createModal_price" class="form-label">價格(低於200)</label>
                      <input type="number" class="form-control" id="createModal_price" v-model="menuPrice" :class="{ 'is-valid': createMenuPriceCheck, 'is-invalid': !createMenuPriceCheck }" />
                      <div class="valid-feedback">符合規定</div>
                      <div class="invalid-feedback">標價錯誤</div>
                    </div>
                    <div class="mb-3">
                      <label for="createModal_category" class="form-label">類別</label>
                      <select class="form-select" id="createModal_category" v-model="menuCategory" :class="{ 'is-valid': createMenuCategoryCheck, 'is-invalid': !createMenuCategoryCheck }">
                        <option disabled value="">請選擇類別</option>
                        <option v-for="item in uniqueCategories" :key="item" :value="item">{{ item }}</option>
                        <option value="其他">其他</option>
                      </select>
                      <div class="valid-feedback">符合規定</div>
                      <div class="invalid-feedback">請選擇類別</div>
                      <div v-if="menuCategory === '其他'" class="mt-3">
                        <label for="createCustomCategory" class="form-label">自定義類別(5字以內)</label>
                        <input type="text" class="form-control" id="createCustomCategory" v-model="createCustomCategory" :class="{ 'is-valid': createMenuCustomCategoryCheck, 'is-invalid': !createMenuCustomCategoryCheck }" />
                        <div class="valid-feedback">符合規定</div>
                        <div class="invalid-feedback">字數錯誤</div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="createModal_status" class="form-label">狀態</label>
                      <select class="form-select" id="createModal_status" v-model="menuStatus" :class="{ 'is-valid': createMenuStatusCheck, 'is-invalid': !createMenuStatusCheck }">
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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="createMenu()">確認</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- 修改菜單 Modal -->
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
                      <select id="select-menuName" class="form-select form-select-lg mt-3">
                        <option value="" selected disabled>---選擇菜單名稱---</option>
                        <option v-for="item in menu" :key="item.menu_id" :value="item.menu_id">{{ item.name }}</option>
                      </select>
                    </div>
                    <div v-if="selectedMenu">
                      <div class="mb-3">
                        <label for="editModal_price" class="form-label">價格(低於200)</label>
                        <input type="number" class="form-control" id="editModal_price" v-model="selectedMenu.price" :class="{ 'is-valid': editMenuPriceCheck, 'is-invalid': !editMenuPriceCheck }" />
                        <div class="valid-feedback">符合規定</div>
                        <div class="invalid-feedback">標價錯誤</div>
                      </div>
                      <div class="mb-3">
                        <label for="editModal_category" class="form-label">類別</label>
                        <select class="form-select" id="editModal_category" v-model="selectedMenu.category" :class="{ 'is-valid': editMenuCategoryCheck, 'is-invalid': !editMenuCategoryCheck }">
                          <option disabled value="">請選擇類別</option>
                          <option v-for="item in uniqueCategories" :key="item" :value="item">{{ item }}</option>
                          <option value="其他">其他</option>
                        </select>
                        <div class="valid-feedback">符合規定</div>
                        <div class="invalid-feedback">請選擇類別</div>
                        <div v-if="selectedMenu.category === '其他'" class="mt-3">
                          <label for="editCustomCategory" class="form-label">自定義類別(5字以內)</label>
                          <input type="text" class="form-control" id="editCustomCategory" v-model="editCustomCategory" :class="{ 'is-valid': editMenuCustomCategoryCheck, 'is-invalid': !editMenuCustomCategoryCheck }" />
                          <div class="valid-feedback">符合規定</div>
                          <div class="invalid-feedback">字數錯誤</div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="editModal_status" class="form-label">狀態</label>
                        <select class="form-select" id="editModal_status" v-model="selectedMenu.status" :class="{ 'is-valid': editMenuStatusCheck, 'is-invalid': !editMenuStatusCheck }">
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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="updateMenu()">確認修改</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 訂單管理 -->
        <div v-if="currentcontent === 'bg-order'">
          <div class="container">
            <div class="row">
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectcity">
                  <option value="" selected disabled>---選擇縣市名稱---</option>
                  <option v-for="(item, key) in city" :key="key" :value="item.city">{{ item.city }}</option>
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectarea">
                  <option value="" selected disabled>---選擇鄉鎮區名稱---</option>
                  <option v-for="(item, key) in area" :key="key" :value="item.area">{{ item.area }}</option>
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectstore">
                  <option value="" selected disabled>---選擇門市---</option>
                  <option v-for="item in store" :key="item.store_id" :value="item.store_id">店名:{{ item.name }} 地址:{{ item.address }}</option>
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectstatus">
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
                      <th>訂單編號</th>
                      <th>餐點名稱</th>
                      <th>狀態</th>
                      <th>數量</th>
                      <th>總金額</th>
                      <th>
                        訂餐時間
                        <button class="btn" @click="sortTable('order_date')" v-if="!sortAsc"><i class="fa-solid fa-arrow-up"></i></button>
                        <button class="btn" @click="sortTable('order_date')" v-if="sortAsc"><i class="fa-solid fa-arrow-down"></i></button>
                      </th>
                      <th>功能</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="filterStatusData.length === 0">
                      <td colspan="7" class="h3 text-center">請選擇資料</td>
                    </tr>
                    <tr v-for="item in filterStatusDataforPage[currentPage]" :key="item.order_id + '-' + item.name">
                      <td>{{ item.order_id }}</td>
                      <td>{{ item.name }}</td>
                      <td>{{ item.status }}</td>
                      <td>{{ item.quantity }}</td>
                      <td>{{ item.total_price }}</td>
                      <td>{{ item.order_date }}</td>
                      <td>
                        <button class="btn btn-success me-2" v-if="item.status !== '已支付'" @click="statusedit(item.order_id, '已支付')">付款完成</button>
                        <button class="btn btn-warning me-2" v-if="item.status !== '待支付'" @click="statusedit(item.order_id, '待支付')">等待支付</button>
                        <button class="btn btn-danger me-2" v-if="item.status !== '已取消'" @click="statusedit(item.order_id, '已取消')">訂單取消</button>
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
                    <a class="page-link" @click="currentPage -= 1">«</a>
                  </li>
                  <li class="page-item" :class="{ 'active': currentPage === key }" v-for="(page, key) in filterStatusDataforPage" :key="key">
                    <span class="page-link" @click="currentPage = key">{{ key + 1 }}</span>
                  </li>
                  <li class="page-item" v-if="currentPage !== filterStatusDataforPage.length - 1">
                    <a class="page-link" @click="currentPage += 1">»</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>

        <!-- 報表 -->
        <div v-if="currentcontent === 'bg-chart'">
          <div class="container">
            <div class="row">
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectcity">
                  <option value="" selected disabled>---選擇縣市名稱---</option>
                  <option v-for="(item, key) in city" :key="key" :value="item.city">{{ item.city }}</option>
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectarea">
                  <option value="" selected disabled>---選擇鄉鎮區名稱---</option>
                  <option v-for="(item, key) in area" :key="key" :value="item.area">{{ item.area }}</option>
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectstore">
                  <option value="" selected disabled>---選擇門市---</option>
                  <option v-for="item in store" :key="item.store_id" :value="item.store_id">店名:{{ item.name }} 地址:{{ item.address }}</option>
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-select form-select-lg mt-3" v-model="selectdate">
                  <option value="" selected disabled>---選擇月份---</option>
                  <option v-for="item in uniquedate" :key="item" :value="item">{{ item }}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-9">
                <div class="card mt-3">
                  <div class="card-header bg-info">
                    <h3 v-if="filteredOrders.length > 0">查詢結果：</h3>
                    <h3 v-else>沒有符合條件的資料</h3>
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
                      <div class="h3 text-end">本月訂單總金額： {{ totalPrice }}</div>
                    </table>
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
        </div>

        <!-- 店長管理 -->
        <div v-if="currentcontent === 'bg-admin'">
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
                <data-table :data="adminuser" :columns="columnsUser"></data-table>
              </div>
            </div>

            <!-- 新增店長 Modal -->
            <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header text-bg-warning fw-900">
                    <h1 class="modal-title fs-5" id="createAdminModalLabel">新增店長</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="createAdminModal_store" class="form-label">門市編號</label>
                      <input type="number" class="form-control" id="createAdminModal_store" v-model="adminStore" :class="{ 'is-valid': createAdminStoreCheck, 'is-invalid': !createAdminStoreCheck }" />
                      <div class="valid-feedback">{{ createAdminStoreValue }}</div>
                      <div class="invalid-feedback">{{ createAdminStoreValue }}</div>
                    </div>
                    <div class="mb-3">
                      <label for="createAdminModal_name" class="form-label">用戶名(不超過12字符)</label>
                      <input type="text" class="form-control" id="createAdminModal_name" v-model="adminName" :class="{ 'is-valid': createAdminNameCheck, 'is-invalid': !createAdminNameCheck }" />
                      <div class="valid-feedback">{{ createAdminNameValue }}</div>
                      <div class="invalid-feedback">{{ createAdminNameValue }}</div>
                    </div>
                    <div class="mb-3">
                      <label for="createAdminModal_pwd" class="form-label">密碼</label>
                      <input type="text" class="form-control" id="createAdminModal_pwd" v-model="adminPassword" :class="{ 'is-valid': createAdminPasswordCheck, 'is-invalid': !createAdminPasswordCheck }" />
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
                      <input type="text" class="form-control" id="createAdminModal_email" v-model="adminEmail" :class="{ 'is-valid': createAdminEmailCheck, 'is-invalid': !createAdminEmailCheck }" />
                      <div class="valid-feedback">符合規定</div>
                      <div class="invalid-feedback">不符合規定</div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="createAdmin()">確認</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- 修改店長 Modal -->
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
                      <input type="text" class="form-control" id="editAdminModal_username" v-model="editAdminName" @input="editAdmin" :class="{ 'is-valid': selectAdminName != null, 'is-invalid': selectAdminName == null }" />
                      <div class="valid-feedback">有此資料</div>
                      <div class="invalid-feedback">無此資料</div>
                    </div>
                    <div v-if="selectAdminName">
                      <div class="mb-3">
                        <label for="editAdminModal_store" class="form-label">門市代碼</label>
                        <input type="number" class="form-control" id="editAdminModal_store" v-model="selectAdminName.store_id" :class="{ 'is-valid': editAdminStoreCheck, 'is-invalid': !editAdminStoreCheck }" />
                        <div class="valid-feedback">{{ editAdminStoreValue }}</div>
                        <div class="invalid-feedback">{{ editAdminStoreValue }}</div>
                      </div>
                      <div class="mb-3">
                        <button class="btn btn-danger" @click="deleteAdmin()">註銷此帳號</button>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="updateAdmin()">確認修改</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endverbatim
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    const {
      createApp,
      defineComponent
    } = Vue;

    const DataTable = defineComponent({
      props: ["data", "columns"],
      mounted() {
        this.$nextTick(() => {
          this.dt = $(this.$el).DataTable({
            data: this.data,
            columns: this.columns.map(col => ({
              title: col.label,
              data: col.key
            })),
            responsive: true
          });
        });
      },
      watch: {
        data(newData) {
          if (this.dt) this.dt.clear().rows.add(newData).draw();
        }
      },
      beforeUnmount() {
        if (this.dt) this.dt.destroy();
      },
      template: "<table class='display' style='width:100%'></table>"
    });

    const ApexChart = defineComponent({
      props: ["options", "series", "height"],
      mounted() {
        this.chart = new ApexCharts(this.$el, {
          ...this.options,
          series: this.series
        });
        this.chart.render();
      },
      watch: {
        options(newOptions) {
          if (this.chart) this.chart.updateOptions(newOptions);
        },
        series(newSeries) {
          if (this.chart) this.chart.updateSeries(newSeries);
        }
      },
      beforeUnmount() {
        if (this.chart) this.chart.destroy();
      },
      template: "<div :style=\"{height: height + 'px'}\"></div>"
    });

    const app = {
      components: {
        DataTable,
        apexchart: ApexChart
      },
      data() {
        return {
          currentcontent: "bg-menu",
          user: [],
          menu: [],
          adminuser: [],
          columns: [{
              label: "餐點名稱",
              key: "name"
            },
            {
              label: "價格",
              key: "price"
            },
            {
              label: "類別",
              key: "category"
            },
            {
              label: "狀態",
              key: "status"
            }
          ],
          columnsUser: [{
              label: "商店代碼",
              key: "store_id"
            },
            {
              label: "商店代號",
              key: "name"
            },
            {
              label: "帳號",
              key: "username"
            },
            {
              label: "信箱",
              key: "email"
            }
          ],
          menuName: "",
          menuPrice: 0,
          menuCategory: "",
          menuStatus: "",
          createCustomCategory: "",
          editCustomCategory: "",
          selectedMenu: null,
          selectedMenuid: null,
          createMenuNameCheck: false,
          createMenuPriceCheck: false,
          createMenuCategoryCheck: false,
          createMenuCustomCategoryCheck: false,
          createMenuStatusCheck: false,
          createMenuNameValue: "請輸入",
          editMenuPriceCheck: false,
          editMenuCategoryCheck: false,
          editMenuCustomCategoryCheck: false,
          editMenuStatusCheck: false,
          adminStore: "",
          adminName: "",
          adminPassword: "",
          adminEmail: "",
          editAdminName: "",
          selectAdminName: null,
          createAdminStoreCheck: false,
          createAdminStoreValue: "請輸入",
          createAdminNameCheck: false,
          createAdminNameValue: "請輸入",
          createAdminPasswordCheck: false,
          createAdminEmailCheck: false,
          editAdminStoreCheck: false,
          editAdminStoreValue: "請輸入",
          order: [],
          city: [],
          area: [],
          store: [],
          filterStatusData: [],
          selectcity: "",
          selectarea: "",
          selectstore: "",
          selectstatus: "全部",
          currentPage: 0,
          sortBy: "order_date",
          sortAsc: false,
          selectdate: "",
          filteredOrders: [],
          chartDataList: [{
              title: "柱狀圖",
              options: {
                chart: {
                  type: "bar",
                  id: "chart-1",
                  toolbar: {
                    show: false
                  }
                },
                xaxis: {
                  categories: [],
                  title: {
                    text: "餐點名稱"
                  }
                },
                yaxis: {
                  title: {
                    text: "數量"
                  }
                },
                title: {
                  text: "本月銷售量 - 柱狀圖",
                  align: "center"
                }
              },
              series: [{
                name: "數量",
                data: []
              }]
            },
            {
              title: "折線圖",
              options: {
                chart: {
                  type: "line",
                  id: "chart-2",
                  toolbar: {
                    show: false
                  }
                },
                xaxis: {
                  categories: [],
                  title: {
                    text: "餐點名稱"
                  }
                },
                yaxis: {
                  title: {
                    text: "數量"
                  }
                },
                title: {
                  text: "本月銷售量 - 折線圖",
                  align: "center"
                }
              },
              series: [{
                name: "數量",
                data: []
              }]
            },
            {
              title: "圓環圖",
              options: {
                chart: {
                  type: "donut",
                  id: "chart-3",
                  toolbar: {
                    show: false
                  }
                },
                labels: [],
                title: {
                  text: "本月銷售量 - 圓環圖",
                  align: "center"
                }
              },
              series: []
            }
          ]
        };
      },
      created() {
        const vm = this;

        function getCookie(cname) {
          let name = cname + "=";
          let decodedCookie = decodeURIComponent(document.cookie);
          let ca = decodedCookie.split(";");
          for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === " ") c = c.substring(1);
            if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
          }
          return "";
        }

        function setCookie(cname, cvalue, exdays) {
          const d = new Date();
          d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
          let expires = "expires=" + d.toUTCString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        $(function() {
          $("#logout_btn").click(function() {
            setCookie("Uid01", "", -1);
            window.location.href = "{{ route('home') }}";
          });
        });

        $.ajax({
          type: "POST",
          url: "{{ route('checkuid') }}",
          data: JSON.stringify({
            "uid01": getCookie("Uid01")
          }),
          contentType: "application/json",
          dataType: "json",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
            if (data.state) {
              vm.user = data.data;
              $("#s02_username_text").text(vm.user.username);
              $("#s02_background_btn").removeClass("d-none");
              if (vm.user.vip_level < 1000) {
                Swal.fire({
                  title: "權限不足",
                  allowOutsideClick:false,
                  icon: "error"
                }).then(() => {
                  window.location.href = "{{ route('home') }}";
                });
              } else {
                vm.searchmenu();
                vm.searchAdmin();
                vm.loadCity();
              }
            } else {
              window.location.href = "{{ route('home') }}";
            }
          },
          error: function() {
            Swal.fire({
              title: "API 請求失敗",
              text: "無法驗證用戶",
              icon: "error"
            });
          }
        });
      },
      methods: {
        loadModule(module) {
          this.currentcontent = module;
          if (module === "bg-menu") {
            $(document).ready(() => {
              $("#select-menuName").select2({
                theme: "bootstrap-5",
                dropdownParent: $("#editModal")
              }).on("change", (e) => {
                this.selectedMenuid = $(e.target).val();
                this.editMenu(this.selectedMenuid);
              });
            });
          }
        },
        searchmenu() {
          $.ajax({
            type: "GET",
            url: "{{ route('allmenu') }}",
            dataType: "json",
            success: data => this.menu = data.data,
            error: () => Swal.fire({
              title: "API 請求失敗",
              text: "無法獲取菜單資料",
              icon: "error"
            })
          });
        },
        createMenu() {
          if (this.createMenuNameCheck && this.createMenuPriceCheck && this.createMenuCategoryCheck && this.createMenuCustomCategoryCheck && this.createMenuStatusCheck) {
            const data = {
              name: this.menuName,
              price: this.menuPrice,
              category: this.menuCategory !== "其他" ? this.menuCategory : this.createCustomCategory,
              status: this.menuStatus
            };
            $.ajax({
              type: "POST",
              url: "{{ route('createmenu') }}",
              data: JSON.stringify(data),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                Swal.fire({
                  title: data.message,
                  icon: "success"
                }).then(() => {
                  this.menuName = "";
                  this.menuPrice = 0;
                  this.menuCategory = "";
                  this.createCustomCategory = "";
                  this.menuStatus = "";
                  this.searchmenu();
                });
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法新增菜單",
                icon: "error"
              })
            });
          } else {
            Swal.fire({
              title: "資料錯誤",
              icon: "error"
            });
          }
        },
        editMenu(menuId) {
          this.selectedMenu = this.menu.find(item => item.menu_id === Number(menuId));
        },
        updateMenu() {
          if (this.editMenuPriceCheck && this.editMenuCategoryCheck && this.editMenuCustomCategoryCheck && this.editMenuStatusCheck) {
            const data = {
              menu_id: this.selectedMenu.menu_id,
              name: this.selectedMenu.name,
              price: this.selectedMenu.price,
              category: this.selectedMenu.category !== "其他" ? this.selectedMenu.category : this.editCustomCategory,
              status: this.selectedMenu.status
            };
            Swal.fire({
              title: "確定修改嗎?",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              cancelButtonText: "取消",
              confirmButtonText: "確定修改",
              icon: "question"
            }).then(result => {
              if (result.isConfirmed) {
                $.ajax({
                  type: "POST",
                  url: "{{ route('editmenu') }}",
                  data: JSON.stringify(data),
                  contentType: "application/json",
                  dataType: "json",
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: data => {
                    Swal.fire({
                      title: data.message,
                      icon: "success"
                    }).then(() => this.searchmenu());
                  },
                  error: () => Swal.fire({
                    title: "API 請求失敗",
                    text: "無法修改菜單",
                    icon: "error"
                  })
                });
              }
            });
          } else {
            Swal.fire({
              title: "資料錯誤",
              icon: "error"
            });
          }
        },
        statusedit(order_id, status) {
          $.ajax({
            type: "POST",
            url: "{{ route('editorderstatus') }}",
            data: JSON.stringify({
              order_id,
              status
            }),
            contentType: "application/json",
            dataType: "json",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: data => {
              if (data.state) {
                this.orderdatainput();
                this.selectstatus = status;
              } else {
                Swal.fire({
                  title: "更新失敗",
                  text: data.message,
                  icon: "error"
                });
              }
            },
            error: () => Swal.fire({
              title: "API 請求失敗",
              text: "無法更新訂單狀態",
              icon: "error"
            })
          });
        },
        orderdatainput() {
          if (!this.selectstore) return;
          $.ajax({
            type: "POST",
            url: "{{ route('getstoreorders') }}",
            data: JSON.stringify({
              store_id: this.selectstore
            }),
            contentType: "application/json",
            dataType: "json",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: data => {
              if (data.state) {
                this.order = data.data;
                this.filterStatusData = data.data;
              } else {
                this.order = [];
                this.filterStatusData = [];
              }
            },
            error: () => Swal.fire({
              title: "API 請求失敗",
              text: "無法獲取訂單資料",
              icon: "error"
            })
          });
        },
        sortTable(column) {
          if (this.sortBy === column) this.sortAsc = !this.sortAsc;
          else {
            this.sortBy = column;
            this.sortAsc = true;
          }
        },
        filterOrders(month) {
          if (!month) {
            this.filteredOrders = [];
            return;
          }
          const filtered = this.order.filter(order => order.status === "已支付" && order.order_date.startsWith(month));
          const processedOrderIds = new Set();
          const grouped = filtered.reduce((acc, order) => {
            if (!acc[order.name]) {
              acc[order.name] = {
                name: order.name,
                totalQuantity: 0,
                totalPrice: 0
              };
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
        searchAdmin() {
          $.ajax({
            type: "GET",
            url: "{{ route('getalluser') }}",
            dataType: "json",
            success: data => this.adminuser = data.data,
            error: () => Swal.fire({
              title: "API 請求失敗",
              text: "無法獲取店長資料",
              icon: "error"
            })
          });
        },
        createAdmin() {
          if (this.createAdminStoreCheck && this.createAdminNameCheck && this.createAdminPasswordCheck && this.createAdminEmailCheck) {
            const data = {
              store_id: this.adminStore,
              username: this.adminName,
              password: this.adminPassword,
              email: this.adminEmail
            };
            $.ajax({
              type: "POST",
              url: "{{ route('createadmindata') }}",
              data: JSON.stringify(data),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                Swal.fire({
                  title: data.message,
                  icon: "success"
                }).then(() => {
                  this.adminStore = "";
                  this.adminName = "";
                  this.adminPassword = "";
                  this.adminEmail = "";
                  this.searchAdmin();
                });
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法新增店長",
                icon: "error"
              })
            });
          } else {
            Swal.fire({
              title: "資料錯誤",
              icon: "error"
            });
          }
        },
        editAdmin() {
          this.selectAdminName = this.adminuser.find(item => item.username === this.editAdminName) || null;
        },
        updateAdmin() {
          if (this.editAdminStoreCheck && this.selectAdminName) {
            const data = {
              store_id: this.selectAdminName.store_id,
              username: this.selectAdminName.username
            };
            Swal.fire({
              title: "確定修改嗎?",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              cancelButtonText: "取消",
              confirmButtonText: "確定修改",
              icon: "question"
            }).then(result => {
              if (result.isConfirmed) {
                $.ajax({
                  type: "POST",
                  url: "{{ route('editadmindata') }}",
                  data: JSON.stringify(data),
                  contentType: "application/json",
                  dataType: "json",
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: data => {
                    Swal.fire({
                      title: data.message,
                      icon: "success"
                    }).then(() => {
                      this.editAdminName = "";
                      this.selectAdminName = null;
                      this.searchAdmin();
                    });
                  },
                  error: () => Swal.fire({
                    title: "API 請求失敗",
                    text: "無法修改店長",
                    icon: "error"
                  })
                });
              }
            });
          } else {
            Swal.fire({
              title: "資料錯誤",
              icon: "error"
            });
          }
        },
        deleteAdmin() {
          if (!this.selectAdminName) return;
          Swal.fire({
            title: "確定註銷此帳號?",
            showCancelButton: true,
            confirmButtonColor: "#F00",
            cancelButtonColor: "#d33",
            cancelButtonText: "取消",
            confirmButtonText: "註銷",
            icon: "warning"
          }).then(result => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "最終確認",
                showCancelButton: true,
                confirmButtonColor: "#F00",
                cancelButtonColor: "#d33",
                cancelButtonText: "取消",
                confirmButtonText: "註銷",
                icon: "warning"
              }).then(result => {
                if (result.isConfirmed) {
                  $.ajax({
                    type: "POST",
                    url: "{{ route('deleteadmindata') }}",
                    data: JSON.stringify({
                      username: this.selectAdminName.username
                    }),
                    contentType: "application/json",
                    dataType: "json",
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: data => {
                      Swal.fire({
                        title: data.message,
                        icon: "success"
                      }).then(() => this.searchAdmin());
                    },
                    error: () => Swal.fire({
                      title: "API 請求失敗",
                      text: "無法註銷店長",
                      icon: "error"
                    })
                  });
                }
              });
            }
          });
        },
        loadCity() {
          $.ajax({
            type: "GET",
            url: "{{ route('selectcity') }}",
            dataType: "json",
            success: data => this.city = data.data,
            error: () => Swal.fire({
              title: "API 請求失敗",
              text: "無法獲取城市資料",
              icon: "error"
            })
          });
        }
      },
      computed: {
        uniqueCategories() {
          return [...new Set(this.menu.map(item => item.category))];
        },
        sortedOrders() {
          return this.filterStatusData.slice().sort((a, b) => {
            return this.sortAsc ?
              new Date(a[this.sortBy]) - new Date(b[this.sortBy]) :
              new Date(b[this.sortBy]) - new Date(a[this.sortBy]);
          });
        },
        filterStatusDataforPage() {
          const perPage = 10;
          const pages = [];
          this.sortedOrders.forEach((item, index) => {
            const page = Math.floor(index / perPage);
            if (!pages[page]) pages[page] = [];
            pages[page].push(item);
          });
          return pages;
        },
        uniquedate() {
          if (this.order.length === 0) return [];
          const paidOrders = this.order.filter(order => order.status === "已支付");
          return [...new Set(
            paidOrders.map(item => {
              const date = new Date(item.order_date);
              return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
            })
          )].sort((a, b) => new Date(b) - new Date(a));
        },
        totalPrice() {
          return this.filteredOrders.reduce((sum, item) => sum + item.totalPrice, 0);
        }
      },
      watch: {
        menuName(newValue) {
          if (newValue.length > 0 && newValue.length <= 8) {
            $.ajax({
              type: "POST",
              url: "{{ route('checkmenuuni') }}",
              data: JSON.stringify({
                name: newValue
              }),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                this.createMenuNameValue = data.message;
                this.createMenuNameCheck = data.state;
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法檢查菜單名稱",
                icon: "error"
              })
            });
          } else {
            this.createMenuNameValue = "字數不符";
            this.createMenuNameCheck = false;
          }
        },
        menuPrice(newValue) {
          this.createMenuPriceCheck = newValue > 0 && newValue <= 200;
        },
        menuCategory(newValue) {
          if (newValue === "") {
            this.createMenuCategoryCheck = false;
            this.createMenuCustomCategoryCheck = false;
          } else if (newValue !== "其他") {
            this.createCustomCategory = "";
            this.createMenuCategoryCheck = true;
            this.createMenuCustomCategoryCheck = true;
          } else {
            this.createMenuCategoryCheck = true;
            this.createMenuCustomCategoryCheck = this.createCustomCategory.length > 0 && this.createCustomCategory.length <= 5;
          }
        },
        createCustomCategory(newValue) {
          this.createMenuCustomCategoryCheck = this.menuCategory === "其他" && newValue.length > 0 && newValue.length <= 5;
        },
        menuStatus(newValue) {
          this.createMenuStatusCheck = newValue !== "";
        },
        "selectedMenu.price": function(newValue) {
          this.editMenuPriceCheck = newValue > 0 && newValue <= 200;
        },
        "selectedMenu.category": function(newValue) {
          if (newValue === "") {
            this.editMenuCategoryCheck = false;
            this.editMenuCustomCategoryCheck = false;
          } else if (newValue !== "其他") {
            this.editCustomCategory = "";
            this.editMenuCategoryCheck = true;
            this.editMenuCustomCategoryCheck = true;
          } else {
            this.editMenuCategoryCheck = true;
            this.editMenuCustomCategoryCheck = this.editCustomCategory.length > 0 && this.editCustomCategory.length <= 5;
          }
        },
        editCustomCategory(newValue) {
          this.editMenuCustomCategoryCheck = this.selectedMenu?.category === "其他" && newValue.length > 0 && newValue.length <= 5;
        },
        "selectedMenu.status": function(newValue) {
          this.editMenuStatusCheck = newValue !== "";
        },
        selectcity(newValue) {
          if (newValue) {
            $.ajax({
              type: "POST",
              url: "{{ route('selectarea') }}",
              data: JSON.stringify({
                city: newValue
              }),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                this.area = data.data;
                this.selectarea = "";
                this.selectstore = "";
                this.selectstatus = "全部";
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法獲取區域資料",
                icon: "error"
              })
            });
          }
        },
        selectarea(newValue) {
          if (newValue) {
            $.ajax({
              type: "POST",
              url: "{{ route('selectstore') }}",
              data: JSON.stringify({
                city: this.selectcity,
                area: newValue
              }),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                this.store = data.data;
                this.selectstore = "";
                this.selectstatus = "全部";
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法獲取門市資料",
                icon: "error"
              })
            });
          }
        },
        selectstore(newValue) {
          if (newValue) {
            this.selectstatus = "全部";
            this.orderdatainput();
          }
        },
        selectstatus(newValue) {
          if (newValue === "全部") {
            this.filterStatusData = this.order;
          } else {
            this.filterStatusData = this.order.filter(item => item.status === newValue);
          }
          this.currentPage = 0;
        },
        selectdate(newValue) {
          this.filterOrders(newValue);
          this.chartDataList.forEach(chartData => {
            const newOption = {
              ...chartData.options
            };
            let newSeries = chartData.options.chart.type === "donut" ? [] : [{
              ...chartData.series[0],
              data: []
            }];
            if (newOption.xaxis) newOption.xaxis.categories = [];
            if (newOption.labels) newOption.labels = [];
            this.filteredOrders.forEach(item => {
              if (newOption.xaxis) newOption.xaxis.categories.push(item.name);
              if (newOption.labels) newOption.labels.push(item.name);
              if (chartData.options.chart.type === "donut") newSeries.push(item.totalQuantity);
              else newSeries[0].data.push(item.totalQuantity);
            });
            chartData.options = newOption;
            chartData.series = newSeries;
          });
        },
        adminStore(newValue) {
          if (newValue) {
            $.ajax({
              type: "POST",
              url: "{{ route('store.check') }}",
              data: JSON.stringify({
                store_id: newValue
              }),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                this.createAdminStoreValue = data.state ? `${data.message} ${data.data.name}` : data.message;
                this.createAdminStoreCheck = data.state;
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法檢查門市編號",
                icon: "error"
              })
            });
          }
        },
        adminName(newValue) {
          if (newValue.length <= 12) {
            if ($.trim(newValue) != null && newValue.length != 0) {
              $.ajax({
                type: "POST",
                url: "{{ route('checkadminuni') }}",
                data: JSON.stringify({
                  username: newValue
                }),
                contentType: "application/json",
                dataType: "json",
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: data => {
                  this.createAdminNameValue = data.message;
                  this.createAdminNameCheck = data.state;
                },
                error: () => Swal.fire({
                  title: "API 請求失敗",
                  text: "無法檢查用戶名",
                  icon: "error"
                })
              });
            } else {
              this.createAdminNameCheck = false;
              this.createAdminNameValue = "請輸入";
            }

          } else {
            this.createAdminNameCheck = false;
            this.createAdminNameValue = "字數超出限制";
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
        "selectAdminName.store_id": function(newValue) {
          if (newValue && this.selectAdminName) {
            $.ajax({
              type: "POST",
              url: "{{ route('store.check') }}",
              data: JSON.stringify({
                store_id: newValue
              }),
              contentType: "application/json",
              dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: data => {
                this.editAdminStoreValue = data.state ? `${data.message} ${data.data.name}` : data.message;
                this.editAdminStoreCheck = data.state;
              },
              error: () => Swal.fire({
                title: "API 請求失敗",
                text: "無法檢查門市編號",
                icon: "error"
              })
            });
          }
        }
      }
    };
    Vue.createApp(app).mount("#app");
  </script>
</body>

</html>