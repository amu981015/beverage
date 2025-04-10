# 🧾 Vue CLI 餐飲點餐系統（分支：vue）

本分支為 Vue CLI 架構版本，採用元件化開發，透過 Vue Router 實現多頁導覽功能，並結合 Bootstrap、jQuery、地圖模組與後端 API 溝通實作完整點餐平台。

---

## 📌 系統架構說明

### 🖥️ 前台功能

- 使用 Vue CLI 建構 SPA（Single Page Application）架構。
- 透過 `Vue Router` 搭配元件（`component`）動態切換頁面。
- 頁面總覽：
  - `map`：整合 `leaflet.markercluster`，讓使用者查詢所有商店據點。
  - `menu`：依據後台上架／下架資料動態顯示菜單內容。
  - `admin`：一般用戶後台，可查看訂單明細並進行線上點餐。
  - `storebackground`：店長後台介面，可查看本分店訂單與報表。
  - `background`：總公司後台介面，可管理菜單、店長帳號與全店訂單報表。

---

## 🧩 技術實作細節

### 🔁 路由與元件切換

- 以 Vue Router 建構 `/menu`、`/map`、`/admin`、`/storebackground`、`/background` 等路由。
- 每個路由對應獨立 `.vue` 元件，利於維護與擴展。
- 透過 `axios` 串接 API 提供資料來源。

---

## 🔗 後端 API 串接

- 本分支前端與後端溝通皆串接 `laravelapi` 分支所建立的 Laravel RESTful API。
- 所有資料請求皆透過 `axios` 進行呼叫，例如：
  - 登入 / 註冊驗證
  - 餐點上架 / 下架查詢
  - 訂單建立與查詢
  - 店鋪資料與報表資訊

---

## 🗃️ 資料庫設計

- 資料庫設計遵循 **第三正規化（3NF）** 原則，確保：
  - 無資料重複與異常
  - 關聯清晰可維護
  - 避免更新時發生不一致

---

## 🧰 使用技術

| 分類        | 技術 / 工具                                                               |
|-------------|----------------------------------------------------------------------------|
| 🔧 前端      | Vue CLI、Vue Router、Bootstrap、jQuery、Leaflet.markercluster、Axios       |
| 🖥️ 後端     | Laravel（請參考 `laravelapi` 分支）                                        |
| 💾 資料庫    | MySQL，符合 3NF 正規化設計                                                |
| 🛠️ 其他工具 | Git / GitHub、Figma、Node.js、npm                                          |

---

