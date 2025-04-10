<template>
  <div class="container-fluid">
    <div class="row vh-100">
      <div class="col-md-4">
        <select v-model="mycity" class="form-select form-select-lg mt-4">
          <option value="" disabled>---選擇縣市名稱---</option>
          <option v-for="(item, index) in city" :key="index" :value="item.city">{{ item.city }}</option>
        </select>
        <select v-model="myarea" class="form-select form-select-lg mt-3">
          <option value="" disabled>---選擇鄉鎮區名稱---</option>
          <option v-for="(item, index) in area" :key="index" :value="item.area">{{ item.area }}</option>
        </select>
        <ul class="list-group mt-3" style="height: 80vh; overflow: scroll">
          <li v-for="(item, index) in store" :key="index" class="list-group-item"
            @click="openMarkerPopup(item.latitude, item.longitude)">
            <div class="row d-flex">
              <div class="col-md-4 text-center">
                <img :src="item.photo || 'https://fakeimg.pl/200x220/555?text=?????'" class="img-thumbnail"
                  style="max-height: 125px" />
              </div>
              <div class="col-md-8">
                <p class="h3 fw-900 text-danger">{{ item.name }}</p>
                <p class="h5 fw-900">地址: {{ item.address }}</p>
                <p class="h5 fw-900">電話: {{ item.tel }}</p>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="col-md-8">
        <div id="map" class="vh-100"></div>
      </div>
    </div>
  </div>
</template>

<script>
import L from "leaflet";
import "leaflet.markercluster";
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      city: [],
      area: [],
      mycity: "",
      myarea: "",
      store: [],
      map: null,
      markers: null,
      markerMap: {},
    };
  },
  mounted() {
    this.loadCityData();
    this.$nextTick(() => {
      this.initMap();
    });
  },
  watch: {
    mycity(newValue) {
      this.loadAreaData(newValue);
    },
    myarea(newValue) {
      this.loadStoreData(newValue);
    },
  },
  methods: {
    async loadCityData() {
      try {
        const response = await axios.get("http://laravel.local/api/selectcity");
        this.city = response.data.data;
      } catch (error) {
        Swal.fire({ title: "API介接錯誤", text: "selectcity", icon: "error" });
      }
    },
    async loadAreaData(city) {
      try {
        const response = await axios.post("http://laravel.local/api/selectarea", { city });
        this.area = response.data.data;
      } catch (error) {
        Swal.fire({ title: "API介接錯誤", text: "selectarea", icon: "error" });
      }
    },
    async loadStoreData(area) {
      try {
        const response = await axios.post("http://laravel.local/api/selectstore", { city: this.mycity, area });
        this.store = response.data.data;
        this.updateMarkers();
      } catch (error) {
        Swal.fire({ title: "API介接錯誤", text: "selectstore", icon: "error" });
      }
    },
    initMap() {
      this.map = L.map("map").setView([24.171642, 120.609483], 13);
      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(this.map);
      this.markers = new L.markerClusterGroup().addTo(this.map);
    },
    updateMarkers() {
      this.markers.clearLayers();
      this.markerMap = {};
      this.store.forEach((item, index) => {
        if (index === 0) this.map.panTo([item.latitude, item.longitude]);
        const marker = L.marker([item.latitude, item.longitude]).bindPopup(`
            <div class="card" style="width: 18rem;">
              <img src="${item.photo || 'https://fakeimg.pl/958x485/BB0?text=working...'}" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title">${item.name}</h5>
                <p class="card-text h5 fw-900">地址: ${item.address}</p>
                <p class="card-text h5 fw-900">電話: ${item.tel}</p>
              </div>
            </div>
          `);
        this.markers.addLayer(marker);
        this.markerMap[`${item.latitude},${item.longitude}`] = marker;
      });
    },
    openMarkerPopup(lat, lng) {
      const marker = this.markerMap[`${lat},${lng}`];
      if (marker) {
        marker.openPopup();
        this.map.panTo([lat, lng]);
      }
    },
  },
};
</script>