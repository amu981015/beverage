# 📦 html-js-php 專案

本專案為使用 HTML + CSS (Bootstrap) + JavaScript (jQuery + Vue CDN) + PHP (MySQLi) 所開發的多角色點餐系統。依使用者身份不同提供不同的後台功能，並搭配地圖顯示功能以提供便利的商店查詢體驗。

---

## 🔸 專案架構說明

### 🖼️ 前台

- 以 `index.html` 為主頁，透過 jQuery 動態載入其他區塊頁面（如 header、footer、主內容）。
- `map.html` 結合 `leaflet.markercluster` 地圖群聚標記插件，實作商店地點查詢功能。

### 🛠️ 後台

後台根據使用者身份分為三種：

- **admin.html**：一般用戶後台，可用來點餐、查看個人訂單明細。
- **storebackground.html**：地區店長後台，可查看本店訂單與營收報表。
- **background.html**：總公司後台，可新增菜單、管理地區店長、查看所有分店訂單與營收報表。

後台頁面使用 Vue (CDN) 實作，搭配 `v-if` 控制畫面顯示。

---

## 🔗 後端 API

- 所有 API 集中撰寫於 `member_control_api_v1.php`。
- 使用 MySQLi 與原生 PHP 撰寫，支援以下功能：
  - 使用者登入與註冊
  - 取得菜單資料
  - 訂單相關操作

前端透過 AJAX 向 API 發送請求，實現資料動態互動。

---

## 🧰 使用技術

| 類別 | 技術 / 工具 |
|------|-------------|
| 前端 | HTML5、CSS3、Bootstrap、JavaScript、jQuery、Vue (CDN)、AJAX、SweetAlert、leaflet.markercluster、leaflet-color-markers、WOW.js、animate.css |
| 後端 | PHP (MySQLi) |
| 資料庫 | MySQL |
