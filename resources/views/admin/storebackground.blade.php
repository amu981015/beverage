<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>點餐系統 - 店家後台</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/datatable.css') }}" />
</head>

<body>
  <div id="app">
    @verbatim
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('storebackground') }}">
          <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="#" @click="loadModule('bg-order')">訂單管理</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#" @click="loadModule('bg-chart')">報表</a>
            </li>
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
        <!-- 訂單管理 -->
        <div v-if="currentcontent === 'bg-order'">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="card mt-3">
                  <div class="card-header bg-info">
                    <div class="row">
                      <div class="col-lg-9 text-start h3 mt-3">訂單管理</div>
                      <div class="col-lg-3">
                        <select class="form-select form-select-lg mt-3" v-model="selectstatus">
                          <option value="全部">全部</option>
                          <option value="已支付">已支付</option>
                          <option value="待支付">待支付</option>
                          <option value="已取消">已取消</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <table class="table shadow-lg mt-5 table-rwd">
                      <thead class="table-warning">
                        <tr>
                          <th class="align-middle">訂單編號</th>
                          <th class="align-middle">餐點名稱</th>
                          <th class="align-middle">狀態</th>
                          <th class="align-middle">數量</th>
                          <th class="align-middle">總金額</th>
                          <th class="align-middle">
                            訂餐時間
                            <button class="btn" @click="sortTable('order_date')" v-if="!sortAsc">
                              <i class="fa-solid fa-arrow-up"></i>
                            </button>
                            <button class="btn" @click="sortTable('order_date')" v-if="sortAsc">
                              <i class="fa-solid fa-arrow-down"></i>
                            </button>
                          </th>
                          <th class="align-middle">功能</th>
                        </tr>
                      </thead>
                      <tbody>
                        <div class="h3 text-center" v-if="filterStatusData.length === 0">
                          請選擇資料
                        </div>
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
              </div>
              <div class="row my-4">
                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    <li class="page-item" v-if="currentPage !== 0">
                      <a class="page-link" @click="currentPage -= 1">&laquo;</a>
                    </li>
                    <li class="page-item" :class="{ 'active': currentPage === key }" v-for="(page, key) in filterStatusDataforPage" :key="key">
                      <span class="page-link" @click="currentPage = key">{{ key + 1 }}</span>
                    </li>
                    <li class="page-item" v-if="currentPage !== filterStatusDataforPage.length - 1">
                      <a class="page-link" @click="currentPage += 1">&raquo;</a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <!-- 報表 -->
        <div v-if="currentcontent === 'bg-chart'">
          <div class="container">
            <div class="row">
              <div class="col-lg-8">
                <div class="card mt-3">
                  <div class="card-header bg-info">
                    <div class="row">
                      <div class="col-lg-9 mt-3">
                        <h3 v-if="filteredOrders.length > 0">查詢結果：</h3>
                        <h3 v-else>沒有符合條件的資料</h3>
                      </div>
                      <div class="col-lg-3">
                        <select class="form-select form-select-lg mt-3" v-model="selectdate">
                          <option value="" selected disabled>---選擇月份---</option>
                          <option :value="item" v-for="item in uniquedate" :key="item">{{ item }}</option>
                        </select>
                      </div>
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
                      <div class="h3 text-end">本月訂單總金額： {{ totalPrice }}</div>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 mt-3">
                <div v-for="(chartData, index) in chartDataList" :key="index" class="chart-container">
                  <h2>{{ chartData.title }}</h2>
                  <apexchart :options="chartData.options" :series="chartData.series" height="250"></apexchart>
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
  <script src="{{ asset('js/datatable.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    const {
      createApp,
      defineComponent
    } = Vue;

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
      template: "<div :style=\"{height: height + 'px'}\"></div>",
    });

    const app = {
      components: {
        apexchart: ApexChart
      },
      data() {
        return {
          currentcontent: "bg-order",
          user: [],
          order: [],
          filterStatusData: [],
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
                    show: false,
                  },
                },
                xaxis: {
                  categories: ["1月", "2月", "3月", "4月", "5月", "6月"],
                  title: {
                    text: "月份",
                  },
                },
                yaxis: {
                  title: {
                    text: "銷售量",
                  },
                },
                title: {
                  text: "本月銷售量 - 柱狀圖",
                  align: "center",
                },
              },
              series: [{
                name: "銷售量",
                data: [30, 40, 35, 50, 49, 60],
              }, ],
            },
            {
              title: "折線圖",
              options: {
                chart: {
                  type: "line",
                  id: "chart-2",
                  toolbar: {
                    show: false,
                  },
                },
                xaxis: {
                  categories: ["1月", "2月", "3月", "4月", "5月", "6月"],
                  title: {
                    text: "月份",
                  },
                },
                yaxis: {
                  title: {
                    text: "銷售量",
                  },
                },
                title: {
                  text: "本月銷售量 - 折線圖",
                  align: "center",
                },
              },
              series: [{
                name: "銷售量",
                data: [30, 40, 35, 50, 49, 60],
              }, ],
            },
            {
              title: "圓環圖",
              options: {
                chart: {
                  type: "donut",
                  id: "chart-3",
                  toolbar: {
                    show: false,
                  },
                },
                labels: ["1月", "2月", "3月", "4月", "5月", "6月"],
                title: {
                  text: "每月銷售量 - 圓環圖",
                  align: "center",
                },
              },
              series: [30, 40, 35, 50, 49, 60],
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
              if (vm.user.vip_level < 100) {
                Swal.fire({
                  title: "權限不足",
                  allowOutsideClick: false,
                  icon: "error"
                }).then(() => {
                  window.location.href = "{{ route('home') }}";
                });
              } else {
                vm.orderdatainput();
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
        },
        statusedit(order_id, status) {
          const vm = this;
          $.ajax({
            type: "POST",
            url: "{{ route('editorderstatus') }}",
            data: JSON.stringify({
              "order_id": order_id,
              "status": status
            }),
            contentType: "application/json",
            dataType: "json",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
              if (data.state) {
                vm.orderdatainput();
                vm.selectstatus = status;
              } else {
                Swal.fire({
                  title: "更新失敗",
                  text: data.message,
                  icon: "error"
                });
              }
            },
            error: function() {
              Swal.fire({
                title: "API 請求失敗",
                text: "無法更新訂單狀態",
                icon: "error"
              });
            }
          });
        },
        orderdatainput() {
          const vm = this;
          $.ajax({
            type: "POST",
            url: "{{ route('getstoreorders') }}",
            data: JSON.stringify({
              "store_id": vm.user.store_id
            }),
            contentType: "application/json",
            dataType: "json",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
              if (data.state) {
                vm.order = data.data;
                vm.filterStatusData = vm.order;
              } else {
                vm.order = [];
                vm.filterStatusData = [];
              }
            },
            error: function() {
              Swal.fire({
                title: "API 請求失敗",
                text: "無法獲取訂單資料",
                icon: "error"
              });
            }
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
        }
      },
      computed: {
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
        order: function() {
          const vm = this;
          if (vm.filterStatusData != "") {
            if (vm.selectstatus == "全部") {
              vm.filterStatusData = vm.order;
            } else {
              vm.filterStatusData = [];
              vm.order.forEach((item) => {
                if (item.status == vm.selectstatus) {
                  vm.filterStatusData.push(item);
                }
              });
            }
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
        selectdate(event) {
          const vm = this;
          vm.filterOrders(event);

          vm.chartDataList.forEach(function(chartData) {
            const newOption = {
              ...chartData.options
            };
            let newSeries = [...chartData.series];
            if (newOption.xaxis) {
              newOption.xaxis.categories = [];
            }
            if (newSeries[0].data) {
              newSeries[0].data = [];
            }

            vm.filteredOrders.forEach(function(item) {
              if (newOption.xaxis) {
                newOption.xaxis.categories.push(item.name);
              }
              if (newSeries[0].data) {
                newSeries[0].data.push(item.totalQuantity);
              }
            });

            if (chartData.options.chart.type === "donut") {
              newOption.labels = vm.filteredOrders.map(function(item) {
                return item.name;
              });
              newSeries = vm.filteredOrders.map(function(item) {
                return item.totalQuantity;
              });
            }

            chartData.options = newOption;
            chartData.series = newSeries;
          });
        }
      }
    };
    Vue.createApp(app).mount("#app");
  </script>
</body>

</html>