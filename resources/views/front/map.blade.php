@extends('front.index')

@section('content')
<div class="container-fluid">
  <div class="row vh-100">
    <div class="col-md-4">
      <select name="mycity" id="mycity" class="form-select form-select-lg mt-4">
        <option value="" selected disabled>---選擇縣市名稱---</option>
        @foreach ($cities as $city)
        <option value="{{ $city }}">{{ $city }}</option>
        @endforeach
      </select>
      <select name="myarea" id="myarea" class="form-select form-select-lg mt-3">
        <option value="" selected disabled>---選擇鄉鎮區名稱---</option>
      </select>
      <ul class="list-group mt-3" id="mylist" style="height: 80vh; overflow: scroll">
        <!-- 動態生成店鋪列表 -->
      </ul>
    </div>
    <div class="col-md-8">
      <div id="map" class="vh-100"></div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/js/leaflet.markercluster.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="/css/leaflet.markercluster.css" />

<script>
  var hotelpoints = [];
  var map;
  var markers;
  var mycity = '';
  var myarea = '';
  var markerMap = new Map();
  $(document).ready(function() {
    map = L.map('map').setView([24.171642, 120.609483], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    markers = new L.markerClusterGroup().addTo(map);

    $.ajax({
      type: 'GET',
      url: '{{ route("stores.all") }}',
      dataType: 'json',
      success: function(response) {
        if (response.state) {
          hotelpoints = response.data;
        } else {
          Swal.fire({
            title: '無法獲取店鋪資料',
            text: response.message,
            icon: 'error'
          });
        }
      },
      error: function(xhr) {
        Swal.fire({
          title: 'API 請求失敗',
          text: xhr.statusText,
          icon: 'error'
        });
      }
    });

    $('#mycity').change(function() {
      mycity = $(this).val();
      $('#myarea').empty();
      $('#myarea').append('<option value="" selected disabled>---選擇鄉鎮區名稱---</option>');

      $.ajax({
        type: 'POST',
        url: '{{ route("stores.areas") }}',
        data: {
          city: mycity
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(response) {

          if (response.state) {
            response.data.forEach(function(area) {
              $('#myarea').append(`<option value="${area.area}">${area.area}</option>`);
            });
          } else {
            Swal.fire({
              title: '無法獲取區域資料',
              text: response.message,
              icon: 'error'
            });
          }
        }
      });
    });

    $('#myarea').change(function() {
      myarea = $(this).val();
      removeMarker();
      $('#mylist').empty();
      markerMap.clear();
      var counter = 0;
      hotelpoints.forEach(function(item) {
        if (item.city === mycity && item.area === myarea) {
          counter++;
          if (counter === 1) {
            map.panTo([item.latitude, item.longitude]);
          }

          var img = item.photo || 'https://fakeimg.pl/958x485/BB0?text=working...';
          var strHTML = `
            <li class="list-group-item" data-store-id="${item.store_id}" data-lat="${item.latitude}" data-lng="${item.longitude}">
              <div class="row d-flex">
                <div class="col-md-4 text-center">
                  <img src="${img}" class="img-thumbnail" style="max-height: 125px;" alt="" />
                </div>
                <div class="col-md-8">
                  <p class="h3 fw-900 text-danger">${item.name}</p>
                  <p class="h5 fw-900">地址: ${item.address}</p>
                  <p class="h5 fw-900">電話: ${item.tel}</p>
                </div>
              </div>
            </li>
          `;
          $('#mylist').append(strHTML);

          var popupHTML = `
            <div class="card" style="width: 18rem;">
              <img src="${img}" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title">${item.name}</h5>
                <p class="card-text h5 fw-900">地址: ${item.address}</p>
                <p class="card-text h5 fw-900">電話: ${item.tel}</p>
              </div>
            </div>
          `;
          var marker = L.marker([item.latitude, item.longitude]).bindPopup(popupHTML);
          markers.addLayer(marker);
          markerMap.set(item.store_id, marker);
        }
      });

      $('#mylist .list-group-item').off('click').on('click', function() {
        var storeId = $(this).data('store-id');
        var lat = $(this).data('lat');
        var lng = $(this).data('lng');

        var marker = markerMap.get(storeId);
        if (marker) {
          map.panTo([lat, lng]);
          marker.openPopup();
        }
      });
    });
  });

  function removeMarker() {
    markers.clearLayers();
    markerMap.clear();
  }
</script>
@endsection