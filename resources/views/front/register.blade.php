@extends('front.index')

@section('content')
<div class="container mt-5">
  <h1 class="text-center">會員註冊</h1>

  <!-- 註冊表單 -->
  <div class="row">
    <div class="col-12 col-md-6">
      <div class="d-flex flex-column h-100">
        <div class="display-6 fw-900 my-3">會員守則</div>
        <p>1.會員資格：僅限年滿18歲的個人可註冊為會員，並需提供真實、準確的個人資料，確保帳戶安全。</p>
        <p>2.帳戶安全：會員應妥善保管自己的帳號和密碼，對帳號下的所有活動負責。若發現任何異常或未經授權的使用，應立即通知我們。</p>
        <p>3.訂單與付款：會員需按照網站提供的流程進行訂單確認與付款，並確保支付資訊的正確性及有效性。</p>
        <p>4.產品資訊：網站提供的產品資訊均以最真實的資料為準，但由於顯示設備差異，實際商品顏色、尺寸及規格可能與圖片有所不同，敬請理解。</p>
        <p>5.退換貨政策：會員如需退換貨，需遵循網站的退換貨政策，並提供有效的購買證明，具體條件請參閱我們的退換貨規定。</p>
        <p>6.隱私保護：我們將對會員的個人資料進行保密，並僅用於提供服務或促銷活動。詳情可參見我們的隱私政策。</p>
        <p>7.網站使用：會員應遵守本網站的使用條款，不得發布任何不當、非法或有害的內容。違規行為將導致帳號暫停或取消。</p>

        <!-- 註冊同意勾選框 -->
        <div class="form-check form-switch mt-auto">
          <input type="checkbox" class="form-check-input is-invalid" id="chk01_reg" name="chk01_reg" />
          <label class="form-check-label" for="chk01_reg">不同意</label>
          <div class="valid-feedback">已同意始能註冊!</div>
          <div class="invalid-feedback">須同意始能註冊!</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6">
      <!-- 表單字段 -->
      <div class="mb-3">
        <label for="username_reg" class="form-label">帳號</label>
        <input type="text" class="form-control is-invalid" id="username_reg" name="username_reg" placeholder="字數3~7" />
        <div class="valid-feedback" id="username_reg_vfb">符合規則</div>
        <div class="invalid-feedback" id="username_reg_ivfb">不符合規則</div>
      </div>

      <div class="mb-3">
        <label for="password_reg" class="form-label">密碼</label>
        <input type="password" class="form-control is-invalid" id="password_reg" name="password_reg" placeholder="字數3~6" />
        <div class="valid-feedback">符合規則</div>
        <div class="invalid-feedback">不符合規則</div>
      </div>

      <div class="mb-3">
        <label for="re_password_reg" class="form-label">確認密碼</label>
        <input type="password" class="form-control is-invalid" id="re_password_reg" name="re_password_reg" />
        <div class="valid-feedback">符合規則</div>
        <div class="invalid-feedback">不符合規則</div>
      </div>

      <div class="mb-3">
        <label for="email_reg" class="form-label">Email</label>
        <input type="email" class="form-control is-invalid" id="email_reg" name="email_reg" placeholder="字數3~20" />
        <div class="valid-feedback">符合規則</div>
        <div class="invalid-feedback">不符合規則</div>
      </div>
    </div>
  </div>

  <!-- 表單提交按鈕 -->
  <div class="text-center">
    <button type="submit" class="btn btn-primary" id="reg_btn">註冊</button>
  </div>
</div>

<script>
  var flag_username_reg = false;
  var flag_password_reg = false;
  var flag_re_password_reg = false;
  var flag_email_reg = false;
  var flag_chk01 = false;

  $(function() {
    $("#username_reg").on("input", function() {
      if ($(this).val().length > 2 && $(this).val().length < 8) {
        var JSONdata = {
          "username": $(this).val()
        };
        $.ajax({
          type: "POST",
          url: "{{ route('checkuni') }}",
          data: JSON.stringify(JSONdata),
          contentType: "application/json",
          dataType: "json",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: showdata_check_uni,
          error: function(xhr) {
            Swal.fire({
              title: "API 請求失敗",
              text: xhr.statusText,
              icon: "error",
            });
          }
        });
      } else {
        $("#username_reg_ivfb").text("不符合規則");
        $("#username_reg").removeClass("is-valid").addClass("is-invalid");
        flag_username_reg = false;
      }
    });

    $("#password_reg").on("input", function() {
      $("#re_password_reg").removeClass("is-valid").addClass("is-invalid");
      flag_re_password_reg = false;

      if ($(this).val().length > 2 && $(this).val().length < 7) {
        $(this).removeClass("is-invalid").addClass("is-valid");
        flag_password_reg = true;
      } else {
        $(this).removeClass("is-valid").addClass("is-invalid");
        flag_password_reg = false;
      }
    });

    $("#re_password_reg").on("input", function() {
      if (flag_password_reg && $(this).val() === $("#password_reg").val()) {
        $(this).removeClass("is-invalid").addClass("is-valid");
        flag_re_password_reg = true;
      } else {
        $(this).removeClass("is-valid").addClass("is-invalid");
        flag_re_password_reg = false;
      }
    });

    $("#email_reg").on("input", function() {
      if ($(this).val().length > 2 && $(this).val().length < 21) {
        $(this).removeClass("is-invalid").addClass("is-valid");
        flag_email_reg = true;
      } else {
        $(this).removeClass("is-valid").addClass("is-invalid");
        flag_email_reg = false;
      }
    });

    $("#chk01_reg").change(function() {
      if ($(this).is(":checked")) {
        $(this).next().text("同意");
        $(this).removeClass("is-invalid").addClass("is-valid");
        flag_chk01 = true;
      } else {
        $(this).next().text("不同意");
        $(this).removeClass("is-valid").addClass("is-invalid");
        flag_chk01 = false;
      }
    });

    $("#reg_btn").click(function(e) {
      e.preventDefault();
      if (flag_username_reg && flag_password_reg && flag_re_password_reg && flag_email_reg && flag_chk01) {
        var JSONdata = {
          "username": $("#username_reg").val(),
          "password": $("#password_reg").val(),
          "email": $("#email_reg").val()
        };
        $.ajax({
          type: "POST",
          url: "{{ route('register') }}",
          data: JSON.stringify(JSONdata),
          contentType: "application/json",
          dataType: "json",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: showdata_reg,
          error: function(xhr) {
            Swal.fire({
              title: "API 請求失敗",
              text: xhr.statusText,
              icon: "error",
            });
          }
        });
      } else {
        Swal.fire({
          title: "欄位有錯誤，請修正",
          icon: "warning",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "確認",
        });
      }
    });
  });

  function showdata_reg(data) {
    if (data.state) {
      Swal.fire({
        title: data.message,
        icon: "success",
      }).then((result) => {
        if (result.isConfirmed) {
          setCookie("Uid01", data.data.Uid01, 7);
          window.location.href = "{{ route('home') }}";
        }
      });
    } else {
      Swal.fire({
        title: data.message,
        icon: "error",
      });
    }
  }

  function showdata_check_uni(data) {
    if (data.state) {
      $("#username_reg").removeClass("is-invalid").addClass("is-valid");
      $("#username_reg_vfb").text(data.message);
      flag_username_reg = true;
    } else {
      $("#username_reg").removeClass("is-valid").addClass("is-invalid");
      $("#username_reg_ivfb").text(data.message);
      flag_username_reg = false;
    }
  }

  function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
</script>
@endsection