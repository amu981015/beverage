@extends('front.index')

@section('content')
<!-- login -->
<div class="container mt-5">
    <h1 class="text-center">會員登入</h1>
    <div class="mb-3">
        <label for="username_login" class="form-label">帳號</label>
        <input type="text" class="form-control is-invalid" id="username_login" name="username_login" />
        <div class="invalid-feedback">請輸入帳號</div>
    </div>
    <div class="mb-3">
        <label for="password_login" class="form-label">密碼</label>
        <input type="password" class="form-control is-invalid" id="password_login" name="password_login" />
        <div class="invalid-feedback">請輸入密碼</div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary" id="login_btn">登入</button>
    </div>
</div>

<script>
    var flag_username_login = false;
    var flag_password_login = false;

    $(function() {
        $("#username_login").on("input", function() {
            if ($(this).val().length > 0) {
                $(this).removeClass("is-invalid");
                flag_username_login = true;
            } else {
                $(this).addClass("is-invalid");
                flag_username_login = false;
            }
        });

        $("#password_login").on("input", function() {
            if ($(this).val().length > 0) {
                $(this).removeClass("is-invalid");
                flag_password_login = true;
            } else {
                $(this).addClass("is-invalid");
                flag_password_login = false;
            }
        });

        $("#login_btn").click(function(e) {
            e.preventDefault();
            if (flag_username_login && flag_password_login) {
                var JSONdata = {
                    "username": $("#username_login").val(),
                    "password": $("#password_login").val()
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('login') }}",
                    data: JSON.stringify(JSONdata),
                    contentType: "application/json",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: showdata_login,
                    error: function(xhr) {
                        Swal.fire({
                            title: "API 請求失敗",
                            text: xhr.statusText,
                            icon: "error"
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "輸入錯誤!",
                    text: "請輸入帳號和密碼!",
                    icon: "error"
                });
            }
        });
    });

    function showdata_login(data) {
        if (data.state) {
            Swal.fire({
                allowOutsideClick: false,
                title: data.message,
                icon: "success"
            }).then((result) => {
                if (result.isConfirmed) {
                    setCookie("Uid01", data.data.Uid01, 7);
                    window.location.href = "{{ route('home') }}";
                }
            });
        } else {
            Swal.fire({
                title: data.message,
                icon: "error"
            });
        }
    }

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
</script>
@endsection