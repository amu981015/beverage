<section id="s01" class="bg-dark">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/">
          <i class="fa-brands fa-envira fa-2x" style="color: green"></i>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="/">首頁</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="/menu">菜單</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="/faq">常見問題</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="/about">關於我們</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="/map">門市據點</a>
            </li>
          </ul>
          <div>
            <li class="nav-item dropdown d-none" id="s02_background_btn">
              <a
                style="display: inline"
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <span
                  class="h4"
                  id="s02_username_text"
                  style="color: var(--white)">XXX</span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a id="background" href="/admin/background" class="dropdown-item d-none" \>總管理後臺</a>
                </li>
                <li>
                  <a id="storebackground" href="/storebackground" class="dropdown-item d-none" \>管理後臺</a>
                </li>
                <li>
                  <a id="order_btn" href="/admin" class="dropdown-item d-none" \>點餐去</a>
                </li>
                <li>
                  <a id="s02_logout_btn" class="dropdown-item">登出</a>
                </li>
              </ul>
            </li>
            <a
              id="s02_reg_btn"
              class="btn"
              href="/register"
              style="background-color: var(--tropics); color: var(--white)">註冊</a>
            <a
              id="s02_login_btn"
              class="btn"
              href="/login"
              style="background-color: var(--endeavor); color: var(--white)">登入</a>
          </div>
        </div>
      </div>
    </nav>
  </div>
</section>
<script>
  $(function() {
    $("#s02_logout_btn").click(function() {
      setCookie("Uid01", ",7");
      location.href = "/";
    });
  });
</script>