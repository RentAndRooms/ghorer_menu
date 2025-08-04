<template>
  <CustomerLayout>
    <!-- Hero Section -->
    <div
      class="relative flex items-center justify-center bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 overflow-visible"
    >
      <!-- Background Pattern -->
      <div class="absolute inset-0 z-0">
        <div
          class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-purple-500/10 dark:from-orange-900/20 dark:to-purple-900/20"
        ></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.07] dark:opacity-[0.03]"></div>
      </div>

      <!-- Floating Elements -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div
          v-for="(item, index) in floatingItems"
          :key="index"
          :class="item.class"
          class="absolute opacity-[0.15] dark:opacity-[0.08]"
        >
          <i :class="item.icon + ' text-5xl md:text-7xl text-orange-600 dark:text-orange-400'"></i>
        </div>
      </div>

      <!-- Main Content -->
      <div class="relative z-10 w-full max-w-6xl mx-auto m-0 p-5">
        <div class="text-center space-y-8">
          <!-- Main Heading -->
          <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold tracking-tight">
            <span
              class="block text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600 dark:from-orange-500 dark:to-red-500 animate-gradient pb-2"
            >Select Your Favourit Food</span>
          </h1>

          <!-- Subheading -->
          <p
            class="max-w-2xl mx-auto text-xl md:text-2xl text-gray-600 dark:text-gray-300 leading-relaxed"
          >Within Our Best Restaurants And Catering Servics</p>
          <!-- Search Field -->
          <div class="relative max-w-xl mx-auto">
            <input
              type="text"
              v-model="searchQuery"
              @input="check"
              placeholder="type your local area"
              class="text-center w-full rounded-full border-2 border-orange-500 dark:border-orange-400 px-6 py-4 text-lg text-gray-900 dark:text-white bg-white dark:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-orange-500/50 dark:focus:ring-orange-400/30 transition duration-300"
            />
            <ul
              v-if="searchQuery.length > 0 && showDropDown"
              class="mt-2 absolute w-full text-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md z-50"
            >
              <li
                v-for="(local, index) in filterLocal"
                :key="index"
                @click="selectedArea(local)"
                class="text-gray-700 cursor-pointer dark:text-gray-300 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded z-50"
              >{{ local.name }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <TransitionGroup
      name="list"
      tag="div"
      class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-6 px-4 md:px-8"
    >
      <div
        v-for="branch in filteredBranches"
        :key="branch.id"
        class="relative bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-800 overflow-hidden"
        data-aos="fade-up"
        style="background-color: rgb(249 115 22);"
      >
        <!-- Gradient Header Overlay -->
        <div
          class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-purple-600"
        ></div>

        <!-- Status Badge -->
        <div
          class="p-5 border-b border-gray-200 dark:border-gray-800 bg-gradient-to-b from-gray-50 dark:from-gray-900 to-transparent"
        >
          <div class="flex items-center justify-center">
            <!-- Combined Image + Info -->
            <div class="flex flex-col items-center text-center">
              <!-- Branch Image -->
              <img
                :src="branch.image ? 'http://localhost:8000/storage/' + branch.image : 'http://localhost:8000/storage/uploads/logo.png'"
                alt="restaurant_logo"
                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-100 dark:border-indigo-900 mb-2"
              />

              <!-- Branch Info -->
              <h3
                class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight"
              >{{ branch.name }}</h3>
              <address
                class="mt-1 text-sm dark:text-gray-400 not-italic line-clamp-2"
                style="font-size: 15px;"
              >{{ branch.address }}</address>
              <div
                class="mt-1"
                style="color: rgb(17 24 39); font-size: 15px;"
              >{{ branch.contact_number }}</div>
            </div>
          </div>
        </div>

        <div class="p-5 space-y-5">
          <div
            class="flex flex-wrap items-center justify-center gap-4 text-sm text-gray-600 dark:text-gray-300"
          >
            <!-- Order Now Button -->
            <div>
              <Link
                :href="route('customer.branch.menu', { branch: branch.id, type: 'collection' })"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
              >
                <i class="fas fa-shopping-bag mr-2"></i> Order Now
              </Link>
            </div>

            <!-- Day Select Dropdown -->
            <div>
              <select
                v-model="selectedDay[branch.id]"
                class="w-48 p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
              >
                <option value>All Days</option>
                <option
                  v-for="day in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']"
                  :key="day"
                  :value="day"
                >{{ day }}</option>
              </select>
            </div>

            <!-- Directions Button -->
            <div>
              <a
                :href="getDirectionsUrl(branch)"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-all duration-200"
              >
                <i class="fas fa-directions mr-2"></i> Directions
              </a>
            </div>
          </div>

          <!-- Opening Hours Table -->
          <div class="space-y-3">
            <div
              class="max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-500 scrollbar-track-gray-100 dark:scrollbar-track-gray-800"
            >
              <table class="w-full text-sm text-left">
                <thead
                  class="text-gray-700 dark:text-gray-300 border-b border-gray-300 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-900"
                >
                  <tr>
                    <th class="py-3 px-4 text-center font-semibold">
                      <i class="fas fa-coffee mr-1 text-yellow-500"></i> Breakfast
                    </th>
                    <th class="py-3 px-4 text-center font-semibold">
                      <i class="fas fa-utensils mr-1 text-blue-600"></i> Lunch
                    </th>
                    <th class="py-3 px-4 text-center font-semibold">
                      <i class="fas fa-moon mr-1 text-purple-500"></i> Dinner
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(hours, index) in branch.opening_hours.filter(
                    (h) => !selectedDay[branch.id] || h.day === selectedDay[branch.id]
                  )"
                    :key="index"
                    class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                  >
                    <td
                      class="py-3 px-4 text-center"
                    >{{ formatTime(hours.breakfast.start) }} - {{ formatTime(hours.breakfast.end) }}</td>
                    <td
                      class="py-3 px-4 text-center"
                    >{{ formatTime(hours.lunch.start) }} - {{ formatTime(hours.lunch.end) }}</td>
                    <td
                      class="py-3 px-4 text-center"
                    >{{ formatTime(hours.dinner.start) }} - {{ formatTime(hours.dinner.end) }}</td>
                  </tr>
                  <tr
                    v-if="
                    branch.opening_hours.filter(
                      (h) => !selectedDay[branch.id] || h.day === selectedDay[branch.id]
                    ).length === 0
                  "
                  >
                    <td
                      colspan="4"
                      class="py-4 text-center text-gray-500 dark:text-gray-400 italic"
                    >Closed</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </TransitionGroup>
  </CustomerLayout>
</template>

<script>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Link } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";
import "aos/dist/aos.css";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

export default {
  name: "Home",
  components: {
    CustomerLayout,
    Link,
  },
  props: {
    branches: {
      type: Array,
      required: true,
    },
    locals: {
      type: Array,
      required: true,
    },
  },
  mounted() {
    AOS.init({
      duration: 1000,
      once: true,
      offset: 50,
      delay: 50,
      easing: "ease-in-out",
    });
    this.filterLocal = this.locals;
    this.filteredBranches = this.branches;
    this.branches.forEach((branch) => {
      if (branch.opening_hours?.length > 0) {
        this.selectedDay[branch.id] = branch.opening_hours[0].day;
      }
    });
  },
  data() {
    return {
      searchQuery: "",
      showDropDown: false,
      filteredBranches: [],
      filterLocal: [],
      selectedDay: {},
      floatingItems: [
        {
          icon: "fas fa-pizza-slice",
          class: "top-1/4 left-1/4 animate-float-slow",
        },
        {
          icon: "fas fa-hamburger",
          class: "top-1/3 right-1/4 animate-float-slower",
        },
        {
          icon: "fas fa-ice-cream",
          class: "bottom-1/4 left-1/3 animate-float",
        },
        {
          icon: "fas fa-coffee",
          class: "top-1/2 right-1/3 animate-float-slow",
        },
        {
          icon: "fas fa-utensils",
          class: "bottom-1/3 right-1/4 animate-float-slower",
        },
      ],
    };
  },
  methods: {
    check() {
      this.showDropDown = true;
      this.filteredBranches = this.branches;
      const query = this.searchQuery.toLowerCase();
      this.filterLocal = this.locals.filter((local) =>
        local.name.toLowerCase().includes(query)
      );
    },
    getDirectionsUrl(branch) {
      const destination = `${branch.latitude},${branch.longitude}`;
      return `https://www.google.com/maps/dir/?api=1&destination=${destination}`;
    },
    isOpen(hours) {
      if (!hours) return false;
      const now = new Date();
      const day = now.toLocaleDateString("en-US", { weekday: "long" });
      const time = now.toLocaleTimeString("en-US", { hour12: false });
      const todayHours = hours.find((h) => h.day === day);
      return todayHours
        ? time >= todayHours.open && time <= todayHours.close
        : false;
    },
    formatDistance(distance) {
      if (!distance) return "Distance unknown";
      return `${distance.toFixed(1)} km away`;
    },
    formatTime(time) {
      return dayjs(`1970-01-01 ${time}`).tz("Asia/Dhaka").format("hh:mm A");
    },
    selectedArea(value) {
      this.searchQuery = value.name;
      this.showDropDown = false;
      console.log(value.division_id);
      if (value.division_id <= 0) {
        console.log(value);
        this.filteredBranches = this.branches.filter(
          (branch) => branch.local_id === value.own_id
        );
      } else {
        this.filteredBranches = this.branches.filter(
          (branch) => branch.thana_id === value.id
        );
      }
    },
  },
};
</script>


<style scoped>
/* Background Grid Pattern */
.bg-grid-pattern {
  background-image: radial-gradient(circle, #ea580c 0.5px, transparent 0.5px);
  background-size: 24px 24px;
}

/* Enhanced Floating Animations */
@keyframes float {
  0%,
  100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.15;
  }

  50% {
    transform: translateY(-20px) rotate(2deg);
    opacity: 0.2;
  }
}

@keyframes float-slow {
  0%,
  100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.15;
  }

  50% {
    transform: translateY(-30px) rotate(-2deg);
    opacity: 0.2;
  }
}

@keyframes float-slower {
  0%,
  100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.15;
  }

  50% {
    transform: translateY(-40px) rotate(3deg);
    opacity: 0.2;
  }
}

.animate-float {
  animation: float 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.animate-float-slow {
  animation: float-slow 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.animate-float-slower {
  animation: float-slower 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

/* Enhanced Gradient Animation */
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

.animate-gradient {
  background-size: 200% auto;
  animation: gradient 6s linear infinite;
}

/* Card Hover Effects */
.feature-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.feature-card:hover {
  transform: translateY(-4px);
}

/* Category Card Hover Effects */
.category-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.category-card:hover {
  transform: scale(1.05);
}

/* Button Hover Effects */
.btn-hover-effect {
  position: relative;
  overflow: hidden;
}

.btn-hover-effect::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 120%;
  height: 120%;
  background: radial-gradient(
    circle,
    rgba(255, 255, 255, 0.2) 0%,
    transparent 70%
  );
  transform: translate(-50%, -50%) scale(0);
  transition: transform 0.5s;
}

.btn-hover-effect:hover::after {
  transform: translate(-50%, -50%) scale(1);
}

/* Smooth Scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: #ea580c;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #c2410c;
}

/* Dark Mode Scrollbar */
.dark ::-webkit-scrollbar-thumb {
  background: #f97316;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #ea580c;
}

/* Image Loading States */
.image-loading {
  position: relative;
  overflow: hidden;
}

.image-loading::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.1),
    transparent
  );
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(100%);
  }
}

/* Section Transitions */
.section-fade-up {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.section-fade-up.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Responsive Adjustments */
@media (max-width: 640px) {
  .hero-content {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }

  .floating-items {
    display: none;
  }
}

@media (max-width: 768px) {
  .category-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1024px) {
  .hero-content {
    padding-top: 4rem;
    padding-bottom: 4rem;
  }
}
</style>
