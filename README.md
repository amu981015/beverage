# 🧩 Laravel 餐飲點餐系統（分支：laravel）

本分支使用 Laravel 架構開發，前後台皆整合 Blade 模板、Vue（CDN）與 Bootstrap，並搭配 MVC 架構撰寫控制器與模型，具備前台訂餐與多層後台管理功能，展現完整網站開發能力。

---

## 📌 系統架構說明

### 🖥️ 前台功能（使用 Blade 模板引擎）

- 使用 `index.blade.php` 作為主版型。
  - 透過 `@include` 匯入 `banner`、header、footer 等共用元件。
  - 透過 `@yield` 匯入對應內容頁（如 `menu`, `map`, `about` 等）。
- **地圖查詢功能：**
  - 在 map 頁面整合 `leaflet.markercluster`，使用者可互動查詢所有分店位置。
- **動態菜單顯示：**
  - menu 頁面會根據後台資料庫狀態，動態顯示已上架餐點，實現即時同步。

---

### 🔧 後台功能（支援多層級管理）

- 採用 HTML + Bootstrap + jQuery + Vue（CDN）+ Blade 模板開發。
- 使用 Vue 的 `v-if` 控制頁面切換，無需重新載入頁面。

| 頁面                        | 使用者角色       | 功能簡述                                                   |
|-----------------------------|------------------|------------------------------------------------------------|
| `admin.blade.php`           | 一般用戶         | 點餐功能、查看個人訂單記錄                                 |
| `storebackground.blade.php` | 分店店長         | 查看分店訂單、報表統計                                     |
| `background.blade.php`      | 總公司管理者     | 菜單管理、店長管理、全店訂單與報表檢視                     |

---

## 🧠 MVC 架構實作

| 架構層級  | 說明                                                                 |
|-----------|----------------------------------------------------------------------|
| Model     | 定義 `Menu`、`Store`、`Order` 等資料模型，對應資料表並封裝邏輯         |
| View      | 使用 Blade 模板語法撰寫各前後台畫面，搭配 Vue 進行動態畫面控制         |
| Controller| 控制器負責接收請求、處理資料與商業邏輯，並回傳對應 View 或 JSON 結果 |

例如：

- `MenuController` 負責處理菜單資料的顯示、上下架。
- `OrderController` 管理訂單新增、查詢。
- `UserController` 處理登入註冊、身分認證邏輯。

---

## 🔗 API 說明

- 所有資料互動由 Laravel Route 設定對應控制器處理，並使用內建 Request 驗證。
- 回傳格式包含 JSON 結構，用於 Vue 與 AJAX 呼叫。

---

## 🧰 使用技術

| 分類        | 技術 / 工具                                                                 |
|-------------|------------------------------------------------------------------------------|
| 🔧 前端      | HTML5、CSS3、Bootstrap、JavaScript、jQuery、Vue（CDN）、Blade、Leaflet.markercluster |
| 🖥️ 後端     | Laravel、PHP                                                                 |
| 💾 資料庫    | MySQL                                                                       |
| 🛠️ 其他工具 | Git / GitHub、Figma、Composer、Artisan CLI                                  |

---
