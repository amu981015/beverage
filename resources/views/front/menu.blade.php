@extends('front.index')

@section('content')
<style>
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

<div class="container">
  <div class="row m-3" id="menu-container">
    @foreach ($menus as $category => $items)
      <div class="col-md-6 col-lg-4">
        <div>
          <div class="h3">{{ $category }}</div>
          <div>
            <ul>
              @foreach ($items as $item)
                <li class="row">
                  <div class="h4">{{ $item->name }} <span>{{ $item->price }}</span></div>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

@endsection