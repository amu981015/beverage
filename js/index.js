new WOW().init();

$(function () {
  //確認uid是否存在
  if (getCookie("Uid01")) {
    //將uid01傳遞至後端API進行驗證
    //input { "uid01" : "XXX" }
    var JSONdata = {};
    JSONdata["uid01"] = getCookie("Uid01");
    // console.log(JSON.stringify(JSONdata));
    $.ajax({
      type: "POST",
      url: "member_control_api_v1.php?action=checkuid",
      data: JSON.stringify(JSONdata),
      dataType: "json",
      success: showdata_checkuid,
      error: function () {
        Swal.fire({
          title: "API介接錯誤?",
          text: "member_control_api_v1.php?action=checkuid",
          icon: "error",
        });
      },
    });
  }
});

function showdata_checkuid(data) {
  console.log(data);
  if (data.state) {
    if (data.data.vip_level == 1000) {
      //按鈕消失
      $("#s02_reg_btn").hide();
      $("#s02_login_btn").hide();
      //顯示歡迎訊息
      // $("#s02_welcome_user").removeClass("d-none");
      $("#s02_username_text").text(data.data.username);
      //顯示登出按鈕
      $("#s02_logout_btn").removeClass("d-none");
      $("#s02_background_btn").removeClass("d-none");
      $("#background").removeClass("d-none");
    } else if (data.data.vip_level == 100) {
      //按鈕消失
      $("#s02_reg_btn").hide();
      $("#s02_login_btn").hide();
      //顯示歡迎訊息
      // $("#s02_welcome_user").removeClass("d-none");
      $("#s02_username_text").text(data.data.username);
      //顯示登出按鈕
      $("#s02_logout_btn").removeClass("d-none");
      $("#s02_background_btn").removeClass("d-none");
      $("#storebackground").removeClass("d-none");
    } else {
      //按鈕消失
      $("#s02_reg_btn").hide();
      $("#s02_login_btn").hide();
      //顯示歡迎訊息
      // $("#s02_welcome_user").removeClass("d-none");
      $("#s02_username_text").text(data.data.username);
      //顯示登出按鈕
      $("#s02_logout_btn").removeClass("d-none");
      $("#s02_background_btn").removeClass("d-none");
      $("#order_btn").removeClass("d-none");
    }
  }
}

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
