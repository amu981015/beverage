<template>
  <footer>
    <section style="background-color: green">
      <div class="container">
        <div class="row">
          <div class="col-md-2 col-12 mt-3 mb-3">
            <div style="background-image: url(img/phone-booth-203492_1920.jpg); height: 100px; width: 200px;"
              class="bg-cover"></div>
          </div>
          <div class="col-md-8 col-12">
            <ul class="nav justify-content-center">
              <li class="nav-item pe-3"><a class="nav-link text-white" href="#">XXX</a></li>
              <li class="nav-item pe-3"><a class="nav-link text-white" href="#">XXX</a></li>
              <li class="nav-item pe-3"><a class="nav-link text-white" href="#">XXX</a></li>
            </ul>
            <div class="text-center">
              <a class="nav-link disabled text-white">PowerBy 飲料店</a>
            </div>
          </div>
          <div class="col-md-2 text-end mt-4">
            <h3 class="text-center text-white">追蹤我們</h3>
            <div class="row">
              <div class="col-3 wow animate__animated animate__fadeInTopLeft">
                <a href="#" target="_blank"><i class="fa-brands fa-instagram fa-2x"
                    style="color: rgb(255, 104, 149)"></i></a>
              </div>
              <div class="col-3 wow animate__animated animate__fadeInBottomLeft">
                <a href="#" target="_blank"><i class="fa-brands fa-square-facebook fa-2x"
                    style="color: var(--endeavor)"></i></a>
              </div>
              <div class="col-3 wow animate__animated animate__fadeInTopLeft">
                <a href="#" target="_blank"><i class="fa-brands fa-square-x-twitter fa-2x"
                    style="color: var(--black)"></i></a>
              </div>
              <div class="col-3 wow animate__animated animate__fadeInBottomLeft">
                <a href="#" target="_blank"><i class="fa-brands fa-youtube text-danger fa-2x"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </footer>
</template>

<script>
import WOW from "wow.js/dist/wow.js";

export default {
  mounted() {
    new WOW().init();
  },
};
</script>