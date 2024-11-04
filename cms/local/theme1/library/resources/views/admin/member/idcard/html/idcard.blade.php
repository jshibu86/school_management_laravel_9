<link rel="stylesheet" href="{{asset('assets/backend/css/idcard.css')}}">


{{-- <div class="id-card-tag"></div> --}}
  {{-- <div class="id-card-tag-strip"></div> --}}
  {{-- <div class="id-card-hook"></div> --}}
  <div class="id-card-holder">
    <div class="id-card">
      <div class="header">
        @if(isset(Configurations::getConfig('site')->imagec))
        <img src="{{ Configurations::getConfig('site')->imagec }} " class=""/>
        @endif
      
      </div>
      <div class="photo">
        <img src="{{ @$user->images ?  @$user->images : "/assets/images/default.jpg" }}" alt="user_image"/>
      
      </div>
     
      <h2>{{ @$data->member_username }}</h2>
    
        @php
          $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        @endphp
      <div class="qr-code">
        <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode( @$data->member_username, $generator::TYPE_CODE_128)) }}">
        {{-- <img src="https://www.shopify.com/growth-tools-assets/qr-code/shopify-faae7065b7b351d28495b345ed76096c03de28bac346deb1e85db632862fd0e4.png"> --}}
      </div>
      <h3>www.website.com</h3>
      <hr>
      <p><strong>"{{ Configurations::getConfig('site')->school_name }}"</strong><p>
        <p>{{ Configurations::getConfig('site')->place }},{{ Configurations::getConfig('site')->city }} ,{{ Configurations::getConfig('site')->post }}</p>
      <p>{{ Configurations::getConfig('site')->country }} <strong>{{ Configurations::getConfig('site')->pin_code }}</strong></p>
      <p>Ph:{{ Configurations::getConfig('site')->school_landline }} | E-mail: {{ Configurations::getConfig('site')->school_email }}</p>

    </div>
  </div>

  <script>
    window.addEventListener('load', (event) => {
 window.print();
});
  </script>