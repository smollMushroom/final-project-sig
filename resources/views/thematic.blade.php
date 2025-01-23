<x-layout>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const geojson = @json($geojson);
      const map = L.map('map').setView([-2, 117], 5);
      const tileLayerUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
      const attribution = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
      const infoContainer = document.getElementById('info');

      let selectedLayer = null;
      let maxValue = 0;
      let minValue = 0;

      L.tileLayer(tileLayerUrl, {
        maxZoom: 19
        , attribution
      }).addTo(map);

      const dataConfig = {
        labor: {
          property: 'labor_wages_avg'
          , title: 'Peta Rata-rata Upah Buruh Tahun 2024'
          , label: 'Upah Buruh'
          , unit: 'IDR'
          , colorScale: value => generateColorScale(value, 0, maxValue, 'rgb(255,255,255)', 'rgb(0,0,255)')
          , maxColor: 'rgb(0,0,255)'
          , minColor: 'white'
        }
        , visitors: {
          property: 'total_visitors'
          , title: 'Peta Total Pengunjung Tahun 2024'
          , label: 'Total Pengunjung'
          , unit: 'orang'
          , colorScale: value => generateColorScale(value, 0, 12000000, 'rgb(255,0,0)', 'rgb(255,255,0)')
          , maxColor: 'rgb(255,255,0)'
          , minColor: 'rgb(255,0,0)'
        }
        , sd: {
          property: 'total_SD'
          , title: 'Peta Jumlah Sekolah Dasar Tahun 2024'
          , label: 'Total SD'
          , unit: 'sekolah'
          , colorScale: value => generateColorScale(value, 0, maxValue, 'rgb(0,255,0)', 'rgb(255,0,0)')
          , maxColor: 'rgb(255,0,0)'
          , minColor: 'rgb(0,255,0)'
        }
        , smp: {
          property: 'total_SMP'
          , title: 'Peta Jumlah Sekolah Menengah Pertama Tahun 2024'
          , label: 'Total SMP'
          , unit: 'sekolah'
          , colorScale: value => generateColorScale(value, 0, maxValue, 'rgb(218,165,32)', 'rgb(255,0,0)')
          , maxColor: 'rgb(255,0,0)'
          , minColor: 'rgb(218,165,32)'
        }
        , sma: {
          property: 'total_SMA'
          , title: 'Peta Jumlah Sekolah Menengah Atas Tahun 2024'
          , label: 'Total SMA'
          , unit: 'sekolah'
          , colorScale: value => generateColorScale(value, 0, maxValue, 'rgb(195,55,100)', 'rgb(29,38,113)')
          , maxColor: 'rgb(29,38,113)'
          , minColor: 'rgb(195,55,100)'
        }
        , smk: {
          property: 'total_SMK'
          , title: 'Peta Jumlah Sekolah Menengah Kejuruan Tahun 2024'
          , label: 'Total SMK'
          , unit: 'sekolah'
          , colorScale: value => generateColorScale(value, 0, maxValue, 'rgb(52,232,158)', 'rgb(15,52,67)')
          , maxColor: 'rgb(15,52,67)'
          , minColor: 'rgb(52,232,158)'
        }
      };

      let currentPage = 'visitors';
      setActiveNavButton(currentPage);
      setTitleHeader(currentPage)
      maxValue = getMaxValue(currentPage);
      minValue = getMinValue(currentPage);
      loadGeoJson();

      document.querySelectorAll('.nav-button').forEach(button => {
        button.addEventListener('click', () => {
          const page = button.id.replace('-button', '');
          if (page !== currentPage) {
            currentPage = page;
            infoContainer.innerHTML = ""
            setActiveNavButton(currentPage);
            setTitleHeader(currentPage);
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
          , fillOpacity: 1
          , zIndex: 9999
        });

        layer.bringToFront();
        selectedLayer = layer;
      }

      function resetLayerStyle(layer) {
        layer.setStyle(styleFeature(layer.feature));
      }

      function setActiveNavButton(page) {
        document.querySelectorAll('.nav-button').forEach(button => {
          button.classList.remove('active-button');
          button.classList.add('inactive-button');
        });

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

      function setTitleHeader(page) {
        const title = document.getElementById('title');
        title.innerHTML = dataConfig[page].title
      }

      function generateColorScale(value, min, max, minColor, maxColor) {
        const normalize = Math.min(Math.max(value, min), max);
        const factor = (normalize - min) / (max - min);

        const [r1, g1, b1] = minColor.match(/\d+/g).map(Number);
        const [r2, g2, b2] = maxColor.match(/\d+/g).map(Number);

        const r = Math.round(r1 + factor * (r2 - r1));
        const g = Math.round(g1 + factor * (g2 - g1));
        const b = Math.round(b1 + factor * (b2 - b1));

        return `rgb(${r},${g},${b})`;
      };
    });

  </script>
</x-layout>
