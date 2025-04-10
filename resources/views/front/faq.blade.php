@extends("front.index")
@section("content")
<!-- 10. 常見問題 (FAQ)
    針對網站或手錶產品常見問題的解答，例如保養、維修、保固等。 -->
<section id="s7">
  <div class="container">
    <h2 class="text-center m-4">常見問題</h2>
    <div class="accordion" id="accordionExample">
      <div
        class="accordion-item wow animate__animated animate__fadeInLeft"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="heading01">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse01"
            aria-expanded="true"
            aria-controls="collapse01"
          >
            1. 如何保養我的手錶？
          </button>
        </h2>
        <div
          id="collapse01"
          class="accordion-collapse collapse"
          aria-labelledby="heading01"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            保持清潔：定期用柔軟的布清潔手錶，尤其是表盤和表帶部分。對於金屬表帶，可以使用微濕的布擦拭，以去除污漬和汗水。<br />
            防水保護：即使手錶具備防水功能，也應避免長時間浸泡在水中，尤其是熱水中。維護好防水性能，定期檢查防水密封。<br />
            避免劇烈衝擊：手錶不應遭受劇烈的撞擊或震動，這樣有助於保護機芯和表殼。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInRight"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="heading02">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse02"
            aria-expanded="false"
            aria-controls="collapse02"
          >
            2. 手錶應該如何存放？
          </button>
        </h2>
        <div
          id="collapse02"
          class="accordion-collapse collapse"
          aria-labelledby="heading02"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            避免陽光直射：長時間暴露在強烈陽光下會損壞手錶的機芯和表帶，特別是皮革表帶容易變形。<br />
            避免極端溫度：極高或極低的溫度會影響手錶的運行，尤其是機械手錶的精度。<br />
            使用錶盒：將手錶存放在專用的錶盒內，有助於防止灰塵、刮傷和其他外部損傷。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInLeft"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse03"
            aria-expanded="false"
            aria-controls="collapse03"
          >
            3. 如何延長手錶的電池壽命？
          </button>
        </h2>
        <div
          id="collapse03"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            關閉不必要的功能：如果是智能手錶，關閉不常使用的功能（如GPS或藍牙）可以延長電池壽命。<br />
            定期充電：避免將手錶的電池完全耗盡，保持在20%-80%之間的電量有助於延長電池的使用壽命。<br />
            使用節能模式：許多智能手錶提供節能模式，適合長時間使用而不需要頻繁充電。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInRight"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse04"
            aria-expanded="false"
            aria-controls="collapse04"
          >
            4. 手錶的機械運行需要維護嗎？
          </button>
        </h2>
        <div
          id="collapse04"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            定期檢查機芯：機械手錶需要定期保養，尤其是超過3-5年後，應將其送到專業維修點進行清潔、潤滑和檢查。<br />
            避免長期靜止：如果長時間不佩戴機械手錶，應使用手錶上鏈器或定期上鏈，以保持機芯的運行。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInLeft"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse05"
            aria-expanded="false"
            aria-controls="collapse05"
          >
            5. 如何處理手錶的水損問題？
          </button>
        </h2>
        <div
          id="collapse05"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            立即停止使用：如果手錶進水，應立即停止使用，並將其送往專業維修中心進行檢查與修理。<br />
            檢查防水密封：定期檢查手錶的防水密封，尤其是在手錶保固期結束後。防水性能會隨著時間和使用環境逐漸減弱。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInRight"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse06"
            aria-expanded="false"
            aria-controls="collapse06"
          >
            6. 我的手錶是否有保固？
          </button>
        </h2>
        <div
          id="collapse06"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            保固範圍：大多數手錶品牌提供2年至5年的保固期，涵蓋了製造上的缺陷和材料問題。機械或智能功能不正常，通常可在保固期內免費維修或更換。<br />
            保固不涵蓋：一般來說，手錶的外觀損傷、意外摔落、過度磨損、外部液體損壞和非授權維修會導致保固失效。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInLeft"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse07"
            aria-expanded="false"
            aria-controls="collapse07"
          >
            7. 如何處理手錶的維修問題？
          </button>
        </h2>
        <div
          id="collapse07"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            聯繫授權維修點：如果手錶需要維修，請將手錶送至品牌授權的維修中心進行專業檢查和維修。<br />
            避免自行修理：為了保護您的手錶並保持保固，請避免自行拆解或修理。非專業維修可能會導致進一步損壞。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInRight"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse08"
            aria-expanded="false"
            aria-controls="collapse08"
          >
            8. 手錶的保固期可以延長嗎？
          </button>
        </h2>
        <div
          id="collapse08"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            延長保固：某些品牌提供額外的保固延長服務，通常需要額外付費購買延長保固方案。詳細信息可以詢問品牌客服或零售商。<br />
            註冊產品：購買手錶後，請務必註冊產品以確保能享受品牌提供的所有保固服務和優惠。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInLeft"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse09"
            aria-expanded="false"
            aria-controls="collapse09"
          >
            9. 如何保養手錶的皮革表帶？
          </button>
        </h2>
        <div
          id="collapse09"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            避免潮濕：皮革表帶不宜長時間接觸水或汗水，這會影響皮革的質感和耐用性。<br />
            定期保養：使用專門的皮革護理油輕輕擦拭表帶，保持其柔軟和光澤。避免暴露在陽光下，避免乾裂。<br />
            更換表帶：隨著使用時間的推移，皮革表帶可能會磨損或變形。建議定期檢查並更換表帶，以保持手錶的最佳狀態。
          </div>
        </div>
      </div>
      <div
        class="accordion-item wow animate__animated animate__fadeInRight"
        data-wow-duration="1s"
        data-wow-delay="0s"
        data-wow-iteration="1"
      >
        <h2 class="accordion-header" id="headingThree">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse10"
            aria-expanded="false"
            aria-controls="collapse10"
          >
            10. 智能手錶的操作系統更新如何處理？
          </button>
        </h2>
        <div
          id="collapse10"
          class="accordion-collapse collapse"
          aria-labelledby="headingThree"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
            定期更新：智能手錶應該定期檢查並更新操作系統，這樣可以確保功能正常並享有最新的安全性和性能優化。<br />
            自動更新：許多智能手錶支持自動更新，確保您的設備始終運行最新版本的系統和應用程式。
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection