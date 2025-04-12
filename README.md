# 🍽️ 點餐系統整合專案（多分支版本）

歡迎來到本專案！本倉庫整合多種前後端技術組合，模擬一個完整的「點餐系統」，涵蓋前台展示、用戶後台、店長管理、總公司操作，以及 API 串接與資料庫設計。

本專案共分為多個分支，各自代表一種實作技術與架構，方便面試展示與技術驗證。

---

## 📂 分支總覽與技術說明

| 分支名稱           | 技術組合                                              | 說明 |
|--------------------|-------------------------------------------------------|------|
| `html-js-php`      | HTML + CSS(Bootstrap) + JS(jQuery) + PHP(MySQLi)     | 傳統網站架構，前後端分離程度低，使用 index.html 搭配 jQuery 整合子頁面。 |
| `html-vue-php`     | HTML + CSS(Bootstrap) + JS(Vue CDN) + PHP(MySQLi)    | 以 Vue (CDN) 管理畫面切換，前台與後台皆採 v-if 控制頁面元件切換。 |
| `laravel`          | Laravel + Blade + Vue + jQuery + Bootstrap           | 使用 Laravel Blade 範本系統與 @include / @yield 建構頁面，實作前後台畫面與邏輯。 |
| `vue`              | Vue CLI + Vue Router + Bootstrap + Axios             | 完整 Vue CLI 專案，使用 component 與 vue-router 架構前後台模組，前端透過 API 串接。 |
| `laravelapi`       | Laravel + RESTful API + MySQL                        | 負責後端 API 開發，提供登入、菜單、訂單、報表等功能，供 `vue` 分支串接使用。 |

---

## 🧠 分支細節（點擊可前往各分支查看更多）

### [`html-js-php`](https://github.com/amu981015/beverage/tree/html-js-php)
- 前台使用 index.html 為基底，透過 jQuery 載入子頁面（menu、map 等）。
- map 使用 `leaflet.markercluster` 呈現店家地圖。
- menu 頁面可動態反映後台上架菜單變化。
- 後台使用 Vue (CDN) + jQuery 切換頁面，分別為：
  - `admin.html`：用戶後台
  - `storebackground.html`：店長後台
  - `background.html`：總公司後台
- 所有 API 集中在 `member_control_api_v1.php`，使用 MySQLi 撰寫。

### [`html-vue-php`](https://github.com/amu981015/beverage/tree/html-vue-php)
- 與 html-js-php 架構類似，但前台與後台全面使用 Vue v-if 控制頁面切換。
- 地圖與菜單功能相同，後台畫面更模組化。
- API 亦為 `member_control_api_v1.php`，支援登入註冊與菜單功能。

### [`laravel`](https://github.com/amu981015/beverage/tree/laravel)
- 使用 Laravel Blade 結合 Bootstrap 與 jQuery/Vue 建構前後台。
- 前台透過 `index.blade.php` + `@include` + `@yield` 管理畫面。
- 地圖與菜單功能同樣完整。
- 後台為：
  - `admin.blade.php`：用戶後台
  - `storebackground.blade.php`：店長後台
  - `background.blade.php`：總公司後台
- 使用 MVC 架構處理後端邏輯與資料操作。

### [`vue`](https://github.com/amu981015/beverage/tree/vue)
- 使用 Vue CLI 建構 SPA，前後台分離。
- 路由透過 Vue Router 控制，元件模組化清晰。
- 後台包含 admin、storebackground、background 三大模組。
- 前台功能如地圖查詢與菜單動態調整。
- 所有資料皆透過 `laravelapi` 分支的 API 進行串接。
- 資料庫設計符合第三正規化（3NF）。

### [`laravelapi`](https://github.com/amu981015/beverage/tree/laravelapi)
- 提供 RESTful API 給 `vue` 前端串接。
- 使用 Laravel 控制器與路由進行功能分層管理：
  - `UserController`：註冊與登入
  - `MenuController`：菜單管理
  - `OrderController`：訂單處理與報表
  - `StoreController`：店鋪查詢
  - `AdminController`：店長帳號管理
- 軟刪除（SoftDeletes）保留歷史資料紀錄。
- 資料庫設計符合 3NF，提升系統穩定與一致性。

---

## 🔧 使用技術總覽

| 類別        | 技術/工具                                                       |
|-------------|------------------------------------------------------------------|
| 🔍 前端     | HTML5, CSS3, Bootstrap, JavaScript, jQuery, Vue (CDN / CLI), AJAX, Leaflet, ApexCharts, SweetAlert, wow.js, animate.css |
| 🖥️ 後端     | PHP (MySQLi), Laravel (Blade + API), MVC 架構                    |
| 📡 串接     | RESTful API, Vue Router, Axios                                   |
| 💾 資料庫   | MySQL、符合第三正規化（3NF）                                     |
| 🛠️ 工具     | Git / GitHub, Figma, Postman, Ubuntu (開發環境)                  |
| 🛠️ AI     | ChatGPT, Grok, Claude                  |

---


## 📬 聯絡與合作

歡迎與我聯繫 🙌  
