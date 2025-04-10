## 🔌 Laravel API 服務端專案（分支：laravelapi）

本分支為整個點餐系統的後端核心，採用 Laravel 框架撰寫，提供 RESTful API 給前端（vue 分支）進行串接。涵蓋使用者驗證、菜單管理、訂單處理、店家查詢等功能，資料庫設計遵循第三正規化（3NF）。

📌 **專案簡介**

所有 API 路由定義於 `routes/api.php`，統一管理並使用中介層進行權限保護。

API 主要由以下控制器提供對應功能：

* `UserController.php`：處理使用者註冊、登入與驗證。
* `MenuController.php`：提供菜單查詢、上架、下架等功能。
* `OrderController.php`：處理新增訂單、查詢訂單、報表統計。
* `StoreController.php`：提供地圖相關資料查詢（如店鋪位置、分店清單）。
* `AdminController.php`：管理店長帳號與權限相關功能（新增、編輯、查詢等）。

🧠 **控制器職責說明**

| 控制器          | 功能說明                                   |
| ------------- | ------------------------------------------ |
| `UserController` | 註冊 / 登入 / Uid 驗證                    |
| `MenuController` | 菜單取得 / 上架 / 下架 / 編輯 / 刪除（使用軟刪除） |
| `OrderController` | 新增訂單 / 查詢訂單 / 產生報表                |
| `StoreController` | 取得所有分店資料 / 提供地圖座標 / 店鋪分類      |
| `AdminController` | 建立或管理店長帳號 /  瀏覽後台資料    |

🗃️ **資料庫設計**

資料表設計符合 **3NF 第三正規化**，提升資料一致性與查詢效率。

使用 Laravel 原生 Migration 與 Eloquent ORM 操作資料表。

關鍵資料表包含：

* `users`：使用者帳號（含角色識別）
* `menus`：餐點資料
* `orders`：訂單資料
* `stores`：分店資訊

🧹 **軟刪除實作**

使用 Laravel `SoftDeletes` 機制，保留被刪除資料以利資料追蹤與還原。

避免直接移除重要資料，提升系統安全性與可管理性。

🔗 **與前端串接**

此分支為 [vue 分支] 的後端 API 來源，所有前端請求均串接此服務。

資料互動格式統一採用 **JSON**，支援 `axios` 等前端框架呼叫。

使用 cors 與 API 驗證。

🧰 **使用技術**

| 分類   | 技術 / 工具                       |
| ------ | --------------------------------- |
| 🖥️ 後端 | Laravel、PHP                     |
| 🔗 API  | RESTful、Laravel Route、Controller 架構 |
| 🔐 認證 | Laravel Sanctum（Token 驗證）       |
| 💾 資料庫 | MySQL、Laravel Eloquent ORM、3NF |
| 🛠️ 工具 | Git / GitHub、Postman（API 測試）、Figma |