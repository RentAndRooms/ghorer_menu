<template>
    <div>
      <div ref="mapContainer" class="w-full h-[500px] rounded-lg"></div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, watch, onUnmounted } from 'vue';
  import { Loader } from '@googlemaps/js-api-loader';

  const props = defineProps({
    center: {
      type: Object,
      required: true,
      default: () => ({ lat: 0, lng: 0 })
    },
    markers: {
      type: Array,
      default: () => []
    },
    userLocation: {
      type: Object,
      default: null
    },
    zoom: {
      type: Number,
      default: 13
    }
  });

  const emit = defineEmits(['marker-click']);

  const mapContainer = ref(null);
  const map = ref(null);
  const mapMarkers = ref([]);
  const userMarker = ref(null);

  const mapOptions = {
    zoom: props.zoom,
    center: props.center,
    mapTypeControl: false,
    streetViewControl: false,
    fullscreenControl: false,
    styles: [
      {
        featureType: 'poi',
        elementType: 'labels',
        stylers: [{ visibility: 'off' }]
      }
    ]
  };

  // Initialize map
  const initializeMap = async () => {
    try {
      const loader = new Loader({
        apiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
        version: 'weekly'
      });

      const { Map } = await loader.load();
      map.value = new Map(mapContainer.value, mapOptions);

      // Add markers
      updateMarkers();

      // Add user location if available
      if (props.userLocation) {
        addUserMarker();
      }
    } catch (error) {
      console.error('Error loading Google Maps:', error);
    }
  };

  // Update markers
  const updateMarkers = () => {
    // Clear existing markers
    mapMarkers.value.forEach(marker => marker.setMap(null));
    mapMarkers.value = [];

    // Add new markers
    props.markers.forEach(markerData => {
      const marker = new google.maps.Marker({
        position: {
          lat: parseFloat(markerData.latitude),
          lng: parseFloat(markerData.longitude)
        },
        map: map.value,
        title: markerData.name,
        icon: {
          url: '/images/marker.png', // Add your custom marker image
          scaledSize: new google.maps.Size(40, 40)
        }
      });

      // Create info window
      const infoWindow = new google.maps.InfoWindow({
        content: `
          <div class="p-3 min-w-[200px]">
            <h3 class="font-semibold text-gray-900">${markerData.name}</h3>
            <p class="text-sm text-gray-600 mt-1">${markerData.address}</p>
            ${markerData.distance ? `
              <p class="text-sm text-gray-500 mt-1">
                <i class="fas fa-location-arrow mr-1"></i>
                ${markerData.distance.toFixed(1)} km away
              </p>
            ` : ''}
            <div class="mt-2">
              <span class="text-sm ${markerData.is_open ? 'text-green-600' : 'text-red-600'}">
                ${markerData.is_open ? 'Open Now' : 'Closed'}
              </span>
            </div>
          </div>
        `
      });

      // Add click listener
      marker.addListener('click', () => {
        // Close other info windows if open
        mapMarkers.value.forEach(m => m.infoWindow?.close());

        infoWindow.open(map.value, marker);
        emit('marker-click', markerData);
      });

      // Store info window reference
      marker.infoWindow = infoWindow;
      mapMarkers.value.push(marker);
    });

    // Fit bounds to show all markers if there are any
    if (mapMarkers.value.length > 0) {
      const bounds = new google.maps.LatLngBounds();
      mapMarkers.value.forEach(marker => bounds.extend(marker.getPosition()));
      if (userMarker.value) {
        bounds.extend(userMarker.value.getPosition());
      }
      map.value.fitBounds(bounds);
    }
  };

  // Add user location marker
  const addUserMarker = () => {
    if (userMarker.value) {
      userMarker.value.setMap(null);
    }

    userMarker.value = new google.maps.Marker({
      position: {
        lat: parseFloat(props.userLocation.latitude),
        lng: parseFloat(props.userLocation.longitude)
      },
      map: map.value,
      title: 'Your Location',
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 10,
        fillColor: '#4F46E5',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 2
      },
      zIndex: 999
    });
  };

  // Watch for changes
  watch(() => props.markers, updateMarkers, { deep: true });
  watch(() => props.userLocation, () => {
    if (props.userLocation && map.value) {
      addUserMarker();
    }
  }, { deep: true });

  watch(() => props.center, (newCenter) => {
    if (map.value && newCenter) {
      map.value.setCenter(newCenter);
    }
  }, { deep: true });

  // Lifecycle hooks
  onMounted(() => {
    initializeMap();
  });

  onUnmounted(() => {
    if (map.value) {
      mapMarkers.value.forEach(marker => marker.setMap(null));
      if (userMarker.value) {
        userMarker.value.setMap(null);
      }
    }
  });
  </script>

  <style scoped>
  .map-container {
    @apply rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700;
  }
  </style>
