@extends("front.index")
@section("content")
<!-- 3. 特色手錶系列 (Featured Watch Collections)
    展示熱門或特色手錶系列，提供每個系列的簡短介紹和圖片。 -->
<section id="s02">
  <div
    id="carouselExampleCaptions"
    class="carousel slide wow animate__animated animate__fadeIn"
    data-wow-duration="1s"
    data-wow-delay="0.5s"
    data-wow-iteration="1"
    data-bs-ride="false"
  >
    <div class="carousel-indicators">
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="0"
        class="active"
        aria-current="true"
        aria-label="Slide 1"
      ></button>
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="1"
        aria-label="Slide 2"
      ></button>
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="2"
        aria-label="Slide 3"
      ></button>
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="3"
        aria-label="Slide 4"
      ></button>
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="4"
        aria-label="Slide 5"
      ></button>
    </div>
    <div class="carousel-inner">
      <div
        class="carousel-item bg-cover active"
        style="
          height: 100vh;
          background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        "
      >
        <div
          class="carousel-caption d-md-block p-3 rounded-4 shadow-lg"
          style="background-color: rgba(0, 0, 0, 0.2)"
        >
          <h3 class="fw-900">經典系列 - "永恆之選" (Timeless Collection)</h3>
          <p class="fs-5" style="color: var(--white)">
            "永恆之選"系列是我們品牌的經典之作，融合了傳統瑞士製表工藝與現代設計。每一款手錶都搭載精密的機械機芯，並擁有簡約卻不失高雅的外觀，無論是正式場合還是日常佩戴，都能完美匹配。
          </p>
        </div>
      </div>
      <div
        class="carousel-item bg-cover"
        style="
          height: 100vh;
          background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        "
      >
        <div
          class="carousel-caption d-md-block p-3 rounded-4 shadow-lg"
          style="background-color: rgba(0, 0, 0, 0.2)"
        >
          <h3 class="fw-900">
            運動系列 - "極限挑戰" (Extreme Challenge Collection)
          </h3>
          <p class="fs-5" style="color: var(--white)">
            專為運動愛好者打造，"極限挑戰"系列融合了耐用性和高效能，具備防水、防震和耐高溫的特性。配備精準的計時功能，無論是登山、游泳或其他極限運動，這款手錶都是您最可靠的夥伴。
          </p>
        </div>
      </div>
      <div
        class="carousel-item bg-cover"
        style="
          height: 100vh;
          background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        "
      >
        <div
          class="carousel-caption d-md-block p-3 rounded-4 shadow-lg"
          style="background-color: rgba(0, 0, 0, 0.2)"
        >
          <h3 class="fw-900">智能系列 - "未來時光" (Future Time Collection)</h3>
          <p class="fs-5" style="color: var(--white)">
            "未來時光"系列是我們的智能手錶系列，結合了先進的技術與時尚設計。具有健康追蹤、藍牙連接、觸控螢幕等多項功能，讓您不僅能精確掌握時間，還能輕鬆管理日常生活與運動健康。
          </p>
        </div>
      </div>
      <div
        class="carousel-item bg-cover"
        style="
          height: 100vh;
          background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        "
      >
        <div
          class="carousel-caption d-md-block p-3 rounded-4 shadow-lg"
          style="background-color: rgba(0, 0, 0, 0.2)"
        >
          <h3 class="fw-900">
            奢華系列 - "極致奢華" (Ultimate Luxury Collection)
          </h3>
          <p class="fs-5" style="color: var(--white)">
            為追求極致奢華的消費者設計，"極致奢華"系列手錶以頂級材料如18K黃金、鉑金及鑽石打造。每一款手錶都是精緻工藝的結晶，擁有超凡的設計和極致的時間準確性，彰顯佩戴者的尊貴身份。
          </p>
        </div>
      </div>
      <div
        class="carousel-item bg-cover"
        style="
          height: 100vh;
          background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        "
      >
        <div
          class="carousel-caption d-md-block p-3 rounded-4 shadow-lg"
          style="background-color: rgba(0, 0, 0, 0.2)"
        >
          <h3 class="fw-900">
            限量系列 - "珍藏時刻" (Collector’s Time Collection)
          </h3>
          <p class="fs-5" style="color: var(--white)">
            "珍藏時刻"系列是我們為真正的手錶愛好者精心打造的限量版手錶。每一款手錶都只有極少數的製作數量，並且每款都擁有獨特的設計和編號，這些限量款手錶將成為您珍藏的瑰寶，並在未來增值。
          </p>
        </div>
      </div>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#carouselExampleCaptions"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#carouselExampleCaptions"
      data-bs-slide="next"
    >
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>

<!-- 9. 用戶評論與評價 (Customer Reviews)
      顯示來自其他用戶的手錶評價，增加社會證明效應。 -->
<section id="s03">
  <div class="container">
    <h2 class="text-center m-4">評論</h2>
    <div class="row text-center align-self-center">
      <div class="col-md-4 py-3">
        <div
          class="card wow animate__animated animate__backInLeft"
          data-wow-duration="1s"
          data-wow-delay="0s"
          data-wow-iteration="1"
        >
          <h2 class="card-title">John M.</h2>
          <hr />
          <p class="card-text">
            "自從我戴上Vortex
            5000手錶，我對手錶的認識徹底改變了！這款手錶的自動時間倒流功能真的很酷，讓我感覺像是在未來。無論是精確度還是設計，它都遠超我的預期。每天戴著它，我都會收到很多讚美，完全值得這個價格！"
          </p>
        </div>
      </div>
      <div class="col-md-4 py-3">
        <div
          class="card wow animate__animated animate__backInUp"
          data-wow-duration="1s"
          data-wow-delay="0s"
          data-wow-iteration="1"
        >
          <h2 class="card-title">Lisa T.</h2>
          <hr />
          <p class="card-text">
            "Titan
            X1手錶的設計真的很吸引人，外觀非常現代，並且搭載了無線充電和自動調整時區的功能，真是太方便了！但有時候它的智能功能連接會稍微延遲，不過總體來說，這款手錶還是很值得擁有，特別是對於喜歡科技感的我來說！"
          </p>
        </div>
      </div>
      <div class="col-md-4 py-3">
        <div
          class="card wow animate__animated animate__backInRight"
          data-wow-duration="1s"
          data-wow-delay="0s"
          data-wow-iteration="1"
        >
          <h2 class="card-title">Michael K.</h2>
          <hr />
          <p class="card-text">
            "這款SolarWave
            1000手錶的太陽能驅動技術挺不錯的，基本上不需要充電，光線就可以保持運行。不過，手錶的設計比我想像的要厚重一些，而且表面稍微有些容易刮花。作為一款日常佩戴的手錶，還算是可以，但如果你追求極致輕薄與奢華，可能會有些失望。"
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3. 首頁 (Hero Section)
      大圖背景，展示當前的熱賣手錶。
      引人入勝的標題和簡短描述，像是「探索精緻時光」。 -->
<section id="s04">
  <div
    class="bg-cover carousel"
    style="
      background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
      height: 100vh;
    "
  >
    <div
      class="carousel-caption d-none d-md-block p-3 rounded-4 shadow-lg"
      style="background-color: rgba(0, 0, 0, 0.2)"
    >
      <h3 class="fw-900">探索精緻時光</h3>
      <p class="fs-5" style="color: var(--white)">
        每一分每一秒，都是無價的瞬間。這款手錶以精湛工藝與現代設計，為你帶來精準與優雅。無論是日常佩戴還是特殊場合，它都是你時間的最佳見證。掌握時間，掌控生活。
      </p>
    </div>
  </div>
</section>

<!-- 門市據點 -->
<section id="05">
  <div class="row">
    <div
      class="col-md-6 bg-cover"
      style="
        background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        height: 50vh;
      "
    ></div>
    <div
      class="col-md-6 bg-cover d-flex align-items-center"
      style="background-color: yellowgreen"
    >
      <div class="content text-white m-5">
        <h1>OUR STORES</h1>
        <h2>門市據點</h2>
        <p>一杯飲料，滿足一整天！</p>
        <p>
          不論是清晨的提神還是午後的放鬆，我們的飲料陪伴你度過每一個忙碌的時刻。
        </p>
        <a href="/map" class="btn btn-primary">READ MORE</a>
      </div>
    </div>
  </div>
</section>

<!-- 4. 品牌介紹 (Brand Introduction)
      介紹品牌故事和歷史背景，為消費者建立信任。
      使用圖片、影片或簡短的時間軸。 -->
<section id="s06">
  <div class="row">
    <div
      class="col-md-7 bg-cover d-flex align-items-center"
      style="background-color: yellowgreen"
    >
      <div class="content text-white m-5">
        <h1>about us</h1>
        <h2>關於我們</h2>

        <div class="h5">
          <p>每一杯飲料，都是一段故事的開始。</p>
          <p>
            我們相信每一口都承載著生活的美好與溫暖，並帶給您與眾不同的感動。
          </p>
          <p>
            「心飲」一直致力於創新與品質的平衡，每季都會推出全新口味。<br />我們珍視每一位顧客的支持與回饋，將每一份鼓勵化為前進的力量，<br />並以虛心接受每一條建議，努力改進不完美的地方，<br />我們的承諾是：每一杯飲品都以最真誠的心準備，為您呈現最美好的味覺體驗。<br />
          </p>
        </div>

        <a href="/about" class="btn btn-primary">READ MORE</a>
      </div>
    </div>
    <div
      class="col-md-5 bg-cover"
      style="
        background-image: url('https://fakeimg.pl/1920x1080/555?text=?????');
        height: 75vh;
      "
    ></div>
  </div>
</section>
@endsection