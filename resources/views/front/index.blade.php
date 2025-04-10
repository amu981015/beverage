<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>飲料店官網</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/timeline.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/about.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <!-- 地圖css -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <link rel="stylesheet" href="{{ asset('css/MarkerCluster.css') }}" />
  <style>
    .content {
      text-align: left;
    }

    h1,
    h2 {
      font-weight: bold;
    }

    p {
      margin-bottom: 20px;
    }

    #project {
      background-color: #f9f9f9;
      padding: 60px 0;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      padding: 20px;
      text-align: center;
      height: 100%;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .card-text {
      font-size: 1rem;
      color: #555;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    .card hr {
      border-top: 2px solid #ddd;
      margin-bottom: 15px;
      width: 40%;
      margin-left: auto;
      margin-right: auto;
    }

    @media (max-width: 768px) {
      .card {
        margin-bottom: 20px;
      }
    }

    .image-container {
      position: relative;
    }

    .overlay-text {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      color: white;
      font-size: 24px;
      font-weight: bold;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
    }

    @media screen and (max-width: 768px) {

      .s05-text,
      .s08-text,
      .s13-text {
        z-index: 10;
        background-color: rgba(0, 0, 0, 0.2);
        color: var(--white);
        margin: 15px;
        box-shadow: 2px 2px 4px 2px #faf9f9;
        border-radius: 15px;
        padding: 15px;
      }
    }

    /* 地圖CSS */
    .marker-cluster-medium div {
      background-color: rgba(228, 115, 134, 0.8);
    }

    .marker-cluster-small div {
      background-color: rgb(175, 165, 29);
    }

    .marker-cluster div {
      width: 30px;
      height: 30px;
      margin-left: 5px;
      margin-top: 5px;
      font-size: 16px;
      font-weight: 900;
      text-align: center;
      border-radius: 50%;
    }

    .marker-cluster span {
      line-height: 30px;
      color: white;
    }
  </style>
  <!-- Scripts -->
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/counterup2@2.0.2/dist/index.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="{{ asset('js/leaflet.markercluster.js') }}"></script>
  <script src="{{ asset('js/leaflet-color-markers.js') }}"></script>
  <script src="{{ asset('js/wow.min.js') }}"></script>

  <script>
    new WOW().init();

    $(function() {
      if (getCookie("Uid01")) {
        var JSONdata = {
          "uid01": getCookie("Uid01")
        };
        $.ajax({
          type: "POST",
          url: "{{ route('checkuid') }}",
          data: JSON.stringify(JSONdata),
          contentType: "application/json",
          dataType: "json",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
            if (data.state) {
              if (data.data.vip_level == 1000) {
                $("#s02_reg_btn").hide();
                $("#s02_login_btn").hide();
                $("#s02_username_text").text(data.data.username);
                $("#s02_logout_btn").removeClass("d-none");
                $("#s02_background_btn").removeClass("d-none");
                $("#background").removeClass("d-none");
              } else if (data.data.vip_level == 100) {
                $("#s02_reg_btn").hide();
                $("#s02_login_btn").hide();
                $("#s02_username_text").text(data.data.username);
                $("#s02_logout_btn").removeClass("d-none");
                $("#s02_background_btn").removeClass("d-none");
                $("#storebackground").removeClass("d-none");
              } else {
                $("#s02_reg_btn").hide();
                $("#s02_login_btn").hide();
                $("#s02_username_text").text(data.data.username);
                $("#s02_logout_btn").removeClass("d-none");
                $("#s02_background_btn").removeClass("d-none");
                $("#order_btn").removeClass("d-none");
              }
            }
          },
          error: function(xhr) {
            Swal.fire({
              title: "API 請求失敗",
              text: xhr.statusText,
              icon: "error",
            });
          }
        });
      }
    });

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
  </script>
</head>

<body>
  <!-- section 01 -->
  <div id="banner">
    @include("front.banner")
  </div>

  <div class="d-flex flex-column min-vh-100">
    <!-- content -->
    <div id="content" class="flex-grow-1">
      @yield("content")
    </div>

    <!-- 页脚 -->
    <footer>
      <section id="s9" style="background-color: green">
        <div class="container">
          <div class="row">
            <div class="col-md-2 col-12 d-inline mt-3 mb-3">
              <div style="background-image: url(https://fakeimg.pl/150x150/555?text=?????); height: 100px; width: 200px;" class="bg-cover"></div>
            </div>
            <div class="col-md-8 col-12">
              <div class="row d-inline">
                <div class="col-12 mb-3 d-flex justify-content-center align-items-center">
                  <ul class="nav">
                    <li class="nav-item pe-3">
                      <a class="nav-link text-white" href="#">XXX</a>
                    </li>
                    <li class="nav-item pe-3">
                      <a class="nav-link text-white" href="#">XXX</a>
                    </li>
                    <li class="nav-item pe-3">
                      <a class="nav-link text-white" href="#">XXX</a>
                    </li>
                  </ul>
                </div>
                <div class="col-12 text-center">
                  <a class="nav-link disabled text-white">PowerBy 飲料店</a>
                </div>
              </div>
            </div>
            <div class="col-md-2 text-end mt-4">
              <h3 class="text-center text-white">追蹤我們</h3>
              <div class="row">
                <div class="col-3 wow animate__animated animate__fadeInTopLeft" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-iteration="1">
                  <a href="#" target="_blank" title="Instagram"><i class="fa-brands fa-instagram fa-2x" style="color: rgb(255, 104, 149)"></i></a>
                </div>
                <div class="col-3 wow animate__animated animate__fadeInBottomLeft" data-wow-duration="1s" data-wow-delay="1s" data-wow-iteration="1">
                  <a href="#" target="_blank" title="Facebook"><i class="fa-brands fa-square-facebook fa-2x" style="color: var(--endeavor)"></i></a>
                </div>
                <div class="col-3 wow animate__animated animate__fadeInTopLeft" data-wow-duration="1s" data-wow-delay="1.5s" data-wow-iteration="1">
                  <a href="#" target="_blank" title="Twitter"><i class="fa-brands fa-square-x-twitter fa-2x" style="color: var(--black)"></i></a>
                </div>
                <div class="col-3 wow animate__animated animate__fadeInBottomLeft" data-wow-duration="1s" data-wow-delay="2s" data-wow-iteration="1">
                  <a href="#" target="_blank" title="youtube"><i class="fa-brands fa-youtube text-danger fa-2x"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </footer>
  </div>


</body>

</html>