<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style>
      html, body { margin: 0; padding: 0; }
      .bg_color_blue { background-color: #204E9D; }
      .bg_color_dark_blue { background-color: #f5f5f5; }
      .flexible {
        padding: 50px 60px;
        text-align: justify;
        color: #444444;
      }
      .title_menu {
        padding: 20px 30px;
        color: white;
      }
      .title_menu h1 {
        letter-spacing: 1px;
        font-weight: 300;
        margin: 30px 0px 0px;
        font-size: 22px;
        text-align: center;
      }
      .bottom_menu {
        padding: 10px 30px;
        text-align: left;
        color: #204E9D;
        font-size: 17px;
      }
      .brand {
        font-weight: bolder;
        text-align: right;
      }
      .copy {
        font-weight: 200;
        letter-spacing: 1px;
        color: #444444;
        text-align: center;
        font-size: 12px;
      }
    </style>
  </head>

  <body style="font-family: Arial, Helvetica, sans-serif;">
    <div style="width: 100%;">
      <div class="title_menu bg_color_blue">
        Onestic

        <h1>{{ $title }}</h1>
      </div>
  
      <div class="flexible">
        {!! $body !!}

        <br>
        <small><a href="{{ env('FRONT_URL') . $url }}">{{ env('FRONT_URL') . $url }}</a></small>
      </div>
      
      <footer class="bottom_menu bg_color_dark_blue">
        <p class="brand">Equipe PWA4All.</p>
        <p class="copy">onestic@pwa4all.com</p>
      </footer>
    </div>
  </body>
</html>