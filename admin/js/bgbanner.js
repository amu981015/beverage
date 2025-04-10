$(function () {
  if (getCookie("Uid01")) {
    var JSONdata = {};
    JSONdata["uid01"] = getCookie("Uid01");
    console.log(JSON.stringify(JSONdata));
    $.ajax({
      type: "POST",
      url: "http://192.168.10.73/peso/all/newproject/member_control_api_v1.php?action=checkuid",
      data: JSON.stringify(JSONdata),
      dataType: "json",
      success: showdata_checkuid,
      error: function () {
        Swal.fire({
          title: "API介接錯誤?",
          text: "http://192.168.10.73/peso/all/newproject/member_control_api_v1.php?action=checkuid",
          icon: "error",
        });
      },
    });
  }
});

function showdata_checkuid(data) {
  console.log(data);
  if (data.state) {
    $("#s02_welcome_user").removeClass("d-none");
    $("#s02_username_text").text(data.data.username);
    $("#s02_background_btn").removeClass("d-none");
  } else {
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
