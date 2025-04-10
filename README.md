# 面試專案總覽

本專案為面試準備的綜合展示，根據不同的技術棧與實作方式，分為數個分支進行開發。  
每個分支代表一種架構或開發方式，展示我對於不同前後端技術的掌握與整合能力。

---

## 🔀 分支說明

### `html-js-php`

使用傳統開發方式進行建構：

- **Frontend**: HTML, CSS (Bootstrap), JavaScript (jQuery + Vue CDN)
- **Backend**: PHP (使用 `mysqli`)
- **Database**: MySQL

此分支適合展示無框架環境下的純手寫開發能力，適用於中小型網站。

---

### `html-vue-php`

- 前端使用 Vue (透過 CDN) 重構部分 UI
- 後端仍為 PHP（`mysqli`）
- 強調前端互動性與 SPA 思維的初步結合

---

### `vue`

- 純前端專案，使用 Vue CLI 建構 SPA
- 無後端整合
- 展示組件化開發與 Vue 生態系操作（Vue Router、Vuex）

---

### `laravel`

- 完整的 Laravel MVC 架構
- Blade 模板引擎呈現頁面
- 適合展示 Laravel 的基本用法與傳統全端流程

---

### `laravelapi`

- 使用 Laravel 建構 RESTful API
- 適合作為前後端分離架構中的後端 API 提供者
- 通常搭配 `vue` 分支前端使用

---

## 🗂 主分支用途

本 `main` 分支僅作為各個功能分支的導覽與說明，不包含實作程式碼。  
請根據需求切換至相對應的分支查看詳細內容與程式碼實作。

---

## 📌 分支切換方式

```bash
git checkout 分支名稱

```
