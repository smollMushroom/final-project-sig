<x-layout :title="'Peta Gaji Rata-rata buruh Tahun 2024'">
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const geojson = @json($geojson);
      const map = L.map('map').setView([-2, 117], 5);
      const tileLayerUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
      const attribution = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';

      let selectedLayer = null;
      let maxValue = 0;
      let minValue = 0;

      L.tileLayer(tileLayerUrl, {
        maxZoom: 19
        , attribution
      }).addTo(map);

      // Configuration for each type of data
      const dataConfig = {
        labor: {
          property: 'labor_wages_avg'
          , label: 'Upah Buruh'
          , unit: 'IDR'
          , colorScale: value => {
            const normalized = Math.min(Math.max(value, 0), maxValue);
            const red = 255 - Math.round((normalized / maxValue) * 255);
            const green = 255 - Math.round((normalized / maxValue) * 255);
            const blue = 255 - Math.round((normalized / maxValue) * 0);
            return `rgb(${red},${green},${blue})`;
          }
          , maxColor: 'rgb(0,0,255)'
          , minColor: 'white'
        }
        , visitors: {
          property: 'total_visitors'
          , label: 'Total Pengunjung'
          , unit: 'orang'
          , colorScale: value => {
            const normalized = Math.min(Math.max(value, 0), 12000000);
            const red = 255 - Math.round((normalized / 12000000) * 0);
            const green = Math.round((normalized / 12000000) * 255);
            const blue = 0;
            return `rgb(${red},${green},${blue})`;
          }
          , maxColor: 'rgb(255,255,0)'
          , minColor: 'rgb(255,0,0)'
        }
        , school: {
          property: 'total_SD', // Default to SD for now
          label: 'Total Sekolah Dasar'
          , unit: 'sekolah'
          , colorScale: value => {
            const normalized = Math.min(Math.max(value, 0), maxValue);
            const red = Math.round((normalized / maxValue) * 255);
            const green = 255 - Math.round((normalized / maxValue) * 255);
            const blue = 0;
            return `rgb(${red},${green},${blue})`;
          }
          , maxColor: 'rgb(255,0,0)'
          , minColor: 'rgb(0,255,0)'
        }
      };

      let currentPage = 'labor';
      setActiveNavButton(currentPage);
      maxValue = getMaxValue(currentPage);
      minValue = getMinValue(currentPage);

      loadGeoJson();

      // Event listeners for tab buttons
      document.querySelectorAll('.nav-button').forEach(button => {
        button.addEventListener('click', () => {
          const page = button.id.replace('-button', '');
          if (page !== currentPage) {
            currentPage = page;
            setActiveNavButton(currentPage);
            maxValue = getMaxValue(currentPage);
            minValue = getMinValue(currentPage);
            loadGeoJson();
          }
        });
      });

      function loadGeoJson() {
        L.geoJSON(geojson, {
          style: feature => styleFeature(feature)
          , onEachFeature: (feature, layer) => attachFeatureEvents(feature, layer)
        }).addTo(map);
      }

      function styleFeature(feature) {
        const value = feature.properties[dataConfig[currentPage].property];
        return {
          weight: 1
          , dashArray: 2
          , color: 'white'
          , fillColor: dataConfig[currentPage].colorScale(value)
          , fillOpacity: 1
          , zIndex: 1
        };
      }

      function attachFeatureEvents(feature, layer) {
        layer.on('click', () => {
          displayInfo(feature);
          highlightLayer(layer);
        });
      }

      function displayInfo(feature) {
        const {
          name
        } = feature.properties;
        const value = feature.properties[dataConfig[currentPage].property];
        const infoContainer = document.getElementById('info');

        // Warna maksimum dan minimum
        const maxColor = dataConfig[currentPage].maxColor;
        const minColor = dataConfig[currentPage].minColor;

        infoContainer.innerHTML = `
    <h2>Informasi</h2>
    <div class="tematik-info">
      <table>
        <tr>
          <td>Nama Provinsi</td>
          <td>: ${name}</td>
        </tr>
        <tr>
          <td>${dataConfig[currentPage].label}</td>
          <td>: ${formatValue(value, dataConfig[currentPage].unit)}</td>
        </tr>
        <tr>
          <td>Sumber Data</td>
          <td>: Badan Pusat Statistik</td>
        </tr>
      </table>
    </div>
    <div class="legend">
      <div class="gradient-legend" style="background: linear-gradient(to bottom, ${maxColor}, ${minColor}); width: 15px; height: 120px;"></div>
      <ul>
        <li>${formatValue(maxValue, dataConfig[currentPage].unit)}</li>
        <li>${formatValue(maxValue / 2, dataConfig[currentPage].unit)}</li>
        <li>0</li>
      </ul>
    </div>
  `;
      }

      function highlightLayer(layer) {
        if (selectedLayer) {
          resetLayerStyle(selectedLayer);
        }

        layer.setStyle({
          weight: 2
          , color: 'yellow'
          , fillOpacity: 0.9
          , zIndex: 9999
        });

        layer.bringToFront();
        selectedLayer = layer;
      }

      function resetLayerStyle(layer) {
        layer.setStyle(styleFeature(layer.feature));
      }

      function setActiveNavButton(page) {
        // Hapus kelas aktif dari semua tombol
        document.querySelectorAll('.nav-button').forEach(button => {
          button.classList.remove('active-button');
          button.classList.add('inactive-button');
        });

        // Tambahkan kelas aktif ke tombol yang dipilih
        const activeButton = document.getElementById(`${page}-button`);
        activeButton.classList.add('active-button');
        activeButton.classList.remove('inactive-button');
      }

      function getMaxValue(page) {
        const property = dataConfig[page].property;
        return Math.max(...geojson.features.map(feature => feature.properties[property] || 0));
      }

      function getMinValue(page) {
        const property = dataConfig[page].property;
        return Math.min(...geojson.features.map(feature => feature.properties[property] || 0));
      }

      function formatValue(value, unit) {
        return `${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value)} ${unit}`;
      }
    });

  </script>
</x-layout>
