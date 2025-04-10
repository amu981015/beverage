<template>
  <div id="app">
    <router-view name="navbar"></router-view>
    <div class="d-flex flex-column min-vh-100">
      <div id="content" class="flex-grow-1">
        <router-view></router-view>
      </div>
      <router-view name="footer"></router-view>
    </div>
  </div>
</template>

<script>
import WOW from "wow.js/dist/wow.js";

export default {
  name: "IndexPage",
  mounted() {
    new WOW().init();
  },
};
</script>

<style scoped>
/* 將原 index.html 中的 <style> 部分移至此 */
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

.line {
  display: inline-block;
  width: 100%;
  text-align: left;
}

.line::before {
  content: "--------";
  font-weight: bold;
}

.line span {
  display: inline-block;
}

@media (max-width: 576px) {
  .line::before {
    content: "----";
  }
}

@media (max-width: 768px) {
  .line::before {
    content: "---";
  }
}

@media (max-width: 992px) {
  .line::before {
    content: "--";
  }
}
</style>