<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <style>
    .leaflet-container a,
    .leaflet-container path {
      outline: none;
    }

  </style>
</head>
<body class="h-screen">
  <x-header>{{ $title }}</x-header>
  <main>
    <div class="map-section">
      <div class="map-nav-button">
        <button id="province-button" class="nav-button tab-button">Provinsi</button>
        <button id="visitors-button" class="nav-button tab-button">Pengunjung</button>
        <button id="labor-button" class="nav-button tab-button">Upah Buruh</button>
        <button id="school-button" class="nav-button tab-button">Sekolah</button>
      </div>
      <div class="map-container">
        <div id="map"></div>
      </div>
    </div>

    <div class="info">
      <div id="info">

      </div>
    </div>
  </main>
  {{ $slot }}
</body>
</html>
