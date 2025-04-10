<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>點餐系統</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}" />
</head>

<body>
    <div id="app">
        @verbatim
        <div id="banner">
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#" @click="loadModule('order-menu')">
                        <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" @click="loadModule('order-menu')">點餐</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#" @click="loadModule('order-list')">菜單</a>
                            </li>
                        </ul>
                        <button type="button" class="btn me-3" id="cartbtn" data-bs-toggle="modal" data-bs-target="#cardPage" :class="{ ' d-none': currentcontent== 'order-list'}">
                            <i class="fa-solid text-white fa-cart-shopping fa-2x"></i>
                        </button>
                        <div>
                            <li class="nav-item dropdown d-none" id="s02_background_btn">
                                <a style="display: inline" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        </div>

        <div class="d-flex flex-column min-vh-100">
            <div id="content" class="flex-grow-1">
                <div v-if="currentcontent === 'order-menu'">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <select name="mycity" id="mycity" class="form-select form-select-lg mt-4" v-model="selectcity">
                                    <option value="" selected disabled>---選擇縣市名稱---</option>
                                    <option :value="city[key].city" v-for="(item, key) in city">{{ city[key].city }}</option>
                                </select>
                                <select name="myarea" id="myarea" class="form-select form-select-lg mt-3" v-model="selectarea">
                                    <option value="" selected disabled>---選擇鄉鎮區名稱---</option>
                                    <option :value="area[key].area" v-for="(item, key) in area">{{ area[key].area }}</option>
                                </select>
                                <select name="mylist" id="mylist" class="form-select form-select-lg mt-3" v-model="selectstore">
                                    <option value="" selected disabled>---選擇門市---</option>
                                    <template v-for="item in store">
                                        <option :value="item.store_id">店名: {{ item.name }} 地址: {{ item.address }}</option>
                                    </template>
                                </select>
                            </div>
                            <div class="col-lg-8 col-12 mt-3" :class="{ 'd-none': selectstore == '' }">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item" v-for="item in categorydata" @click="mylink = item.category; categorySelect()">
                                        <a class="nav-link" href="#" :class="{ 'active': mylink == item.category }">{{ item.category }}</a>
                                    </li>
                                </ul>
                                <ul class="list-group" v-if="mylink != null">
                                    <template v-for="item in menudata">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-inline">
                                                {{ item.name }} - {{ item.price }} 元
                                            </div>

                                            <button class="btn btn-primary" @click="addToCart(item)">加入購物車</button>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <!-- 購物車 Modal -->
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
                                                    {{ cartItem.name }} - ${{ cartItem.price }} x {{ cartItem.quantity }}
                                                    <button class="btn btn-danger btn-sm float-end" @click="removeFromCart(cartItem)">刪除</button>
                                                </li>
                                            </ul>
                                            <div class="mt-3"><strong>總計: ${{ total }}</strong></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">我還要選</button>
                                            <button type="button" class="btn btn-primary" @click="orderSuccess()">前往結帳</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="currentcontent === 'order-list'">
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
                                        <tr v-for="item in orders">
                                            <td>{{ item.order_id }}</td>
                                            <td>{{ item.name }} {{ item.address }}</td>
                                            <td>{{ item.total_price }}</td>
                                            <td>{{ item.status }}</td>
                                            <td>{{ item.order_date }}</td>
                                            <td>
                                                <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#detailModal" @click="detailOrder(item.order_id)">詳細資料</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 訂單明細 Modal -->
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
                                                <tr v-for="item in orderdetail">
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
    <script>
        const App = {
            data() {
                return {
                    currentcontent: "order-menu",
                    mylink: "",
                    menudata: [],
                    categorydata: [],
                    cart: [],
                    selectcity: "",
                    selectarea: "",
                    selectstore: "",
                    city: [],
                    area: [],
                    store: [],
                    user: [],
                    orders: [],
                    orderdetail: [],
                };
            },
            created() {
                function setCookie(cname, cvalue, exdays) {
                    const d = new Date();
                    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                }

                function getCookie(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(";");
                    for (let i = 0; i < ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == " ") {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                }

                $(function() {
                    $("#logout_btn").click(function() {
                        setCookie("Uid01", "", -1);
                        window.location.href = "{{ route('home') }}";
                    });
                });

                $(document).ready(function() {
                    $("#cartbtn").removeClass("d-none");
                });

                const vm = this;

                var JSONdata = {
                    "uid01": getCookie("Uid01")
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('checkuid') }}",
                    data: JSON.stringify(JSONdata),
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

                            if (vm.user.vip_level == 30) {
                                Swal.fire({
                                    title: "優惠資訊",
                                    icon: "info",
                                    text: "尊貴的黃金會員每杯飲料都有7折優惠!!!"
                                });
                            } else if (vm.user.vip_level == 20) {
                                Swal.fire({
                                    title: "優惠資訊",
                                    icon: "info",
                                    text: "高級的白銀會員每杯飲料都有8折優惠!!!"
                                });
                            } else if (vm.user.vip_level == 10) {
                                Swal.fire({
                                    title: "優惠資訊",
                                    icon: "info",
                                    text: "新進的銅牌會員每杯飲料都有9折優惠!!!"
                                });
                            } else if (vm.user.vip_level == 0) {
                                Swal.fire({
                                    title: "優惠資訊",
                                    icon: "info",
                                    text: "普通的普通會員，提升到銅牌會員有9折優惠!!"
                                });
                            }

                            $.ajax({
                                type: "POST",
                                url: "{{ route('usergetorder') }}",
                                data: JSON.stringify({
                                    "user_id": vm.user.user_id
                                }),
                                contentType: "application/json",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    vm.orders = data.data || [];
                                    if (!data.state) {
                                        Swal.fire({
                                            title: "沒有訂購紀錄!",
                                            text: "快來點餐吧",
                                            icon: "info",
                                        }).then((result) => {
                                            if (result.isConfirmed) vm.currentcontent = "order-menu";
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: "API 請求失敗",
                                        text: "無法獲取訂單",
                                        icon: "error"
                                    });
                                }
                            });
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

                $.ajax({
                    type: "GET",
                    url: "{{ route('upcategories') }}",
                    dataType: "json",
                    success: function(data) {
                        vm.categorydata = data.data;
                    },
                    error: function() {
                        Swal.fire({
                            title: "API 請求失敗",
                            text: "無法獲取分類",
                            icon: "error"
                        });
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "{{ route('selectcity') }}",
                    dataType: "json",
                    success: function(data) {
                        vm.city = data.data;
                    },
                    error: function() {
                        Swal.fire({
                            title: "API 請求失敗",
                            text: "無法獲取城市",
                            icon: "error"
                        });
                    }
                });
            },
            watch: {
                selectcity(newValue) {
                    const vm = this;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('selectarea') }}",
                        data: JSON.stringify({
                            "city": vm.selectcity
                        }),
                        contentType: "application/json",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            vm.area = data.data;
                        },
                        error: function() {
                            Swal.fire({
                                title: "API 請求失敗",
                                text: "無法獲取區域",
                                icon: "error"
                            });
                        }
                    });
                },
                selectarea(newValue) {
                    const vm = this;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('selectstore') }}",
                        data: JSON.stringify({
                            "city": vm.selectcity,
                            "area": vm.selectarea
                        }),
                        contentType: "application/json",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            vm.store = data.data;
                            vm.selectstore = "";
                        },
                        error: function() {
                            Swal.fire({
                                title: "API 請求失敗",
                                text: "無法獲取門市",
                                icon: "error"
                            });
                        }
                    });
                }
            },
            methods: {
                loadModule(module) {
                    this.currentcontent = module;
                    setTimeout(() => {
                        if (this.currentcontent === "order-list") {
                            new DataTable("#orderdetailinfo", {
                                order: [
                                    [4, "desc"]
                                ]
                            });
                        }
                    }, 100);
                },
                categorySelect() {
                    const vm = this;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('menudata') }}",
                        data: JSON.stringify({
                            "category": vm.mylink
                        }),
                        contentType: "application/json",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            vm.menudata = data.data;
                        },
                        error: function() {
                            Swal.fire({
                                title: "API 請求失敗",
                                text: "無法獲取菜單",
                                icon: "error"
                            });
                        }
                    });
                },
                orderSuccess() {
                    const vm = this;
                    var JSONdata = {
                        "user_id": vm.user.user_id,
                        "store_id": vm.selectstore,
                        "total_price": vm.total,
                        "order_details": vm.cart.map(item => ({
                            menu_id: item.menu_id,
                            quantity: item.quantity
                        }))
                    };
                    $.ajax({
                        type: "POST",
                        url: "{{ route('createorder') }}",
                        data: JSON.stringify(JSONdata),
                        contentType: "application/json",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire({
                                title: "訂單已送出",
                                icon: "success",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('admin') }}";
                                }
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "訂單未能送出",
                                text: "無法創建訂單",
                                icon: "error"
                            });
                        }
                    });
                },
                addToCart(item) {
                    let found = this.cart.find(cartItem => cartItem.menu_id === item.menu_id);
                    if (found) {
                        found.quantity++;
                    } else {
                        this.cart.push({
                            ...item,
                            quantity: 1
                        });
                    }
                    Swal.fire({
                        title: "加入成功~",
                        icon: "success"
                    });
                },
                removeFromCart(cartItem) {
                    this.cart = this.cart.filter(item => item.menu_id !== cartItem.menu_id);
                },
                detailOrder(order) {
                    const vm = this;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('usergetdetailorder') }}",
                        data: JSON.stringify({
                            "order_id": order
                        }),
                        contentType: "application/json",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            vm.orderdetail = data.data;
                        },
                        error: function() {
                            Swal.fire({
                                title: "API 請求失敗",
                                text: "無法獲取訂單明細",
                                icon: "error"
                            });
                        }
                    });
                }
            },
            computed: {
                total() {
                    const vm = this;
                    if (vm.user.vip_level == 30) return vm.cart.reduce((sum, item) => sum + item.price * 0.7 * item.quantity, 0);
                    if (vm.user.vip_level == 20) return vm.cart.reduce((sum, item) => sum + item.price * 0.8 * item.quantity, 0);
                    if (vm.user.vip_level == 10) return vm.cart.reduce((sum, item) => sum + item.price * 0.9 * item.quantity, 0);
                    return vm.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                }
            }
        };
        Vue.createApp(App).mount("#app");
    </script>
</body>

</html>