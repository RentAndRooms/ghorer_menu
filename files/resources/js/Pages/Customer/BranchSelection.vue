<template>
  <CustomerLayout>
    <div class="w-full px-0 py-8">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 px-4">
        <div class="mt-4 md:mt-0 flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <select
              v-model="sortOrder"
              class="form-select rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
              <option value="distance">Sort by Distance</option>
              <option value="name">Sort by Name</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Search Section -->
      <div class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="w-1/2 mx-auto">
          <div class="relative">
            <input
              type="text"
              v-model="searchQuery"
              @input="check"
              placeholder="Type Your Place"
              class="w-full py-3 px-4 text-center rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500"
            />
            <ul
              v-if="searchQuery.length > 0 && showDropDown"
              class="mt-2 absolute w-full text-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md z-10"
            >
              <li
                v-for="(local, index) in filterLocal"
                :key="index"
                @click="selectedArea(local)"
                class="text-gray-700 cursor-pointer dark:text-gray-300 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
              >{{ local.name }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Branches Grid -->
      <TransitionGroup
        name="list"
        tag="div"
        class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-6 px-4"
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

      <!-- No Results -->
      <div
        v-if="branches.length === 0"
        class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-sm"
      >
        <i class="fas fa-store-slash text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No branches found</h3>
        <p class="mt-2 text-gray-500 dark:text-gray-400">Try adjusting your search criteria</p>
      </div>
    </div>
  </CustomerLayout>
</template>

<script>
import { debounce } from "lodash/debounce";
import { Link } from "@inertiajs/vue3";
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import AOS from "aos";
import "aos/dist/aos.css";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

export default {
  name: "BranchLocator",

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

  data() {
    return {
      searchQuery: "",
      showDropDown: false,
      showOpenOnly: false,
      deliveryOnly: false,
      filteredBranches: [],
      selectedDay: {},
      filterLocal: [],
      sortOrder: "distance",
    };
  },
  mounted() {
    // Initialize AOS
    AOS.init({ duration: 700 });
    this.filterLocal = this.locals;
    this.filteredBranches = this.branches;
    this.branches.forEach((branch) => {
      if (branch.opening_hours?.length > 0) {
        this.selectedDay[branch.id] = branch.opening_hours[0].day;
      }
    });
  },

  methods: {
    formatTime(time) {
      return dayjs(`1970-01-01 ${time}`).tz("Asia/Dhaka").format("hh:mm A");
    },
    check() {
      this.showDropDown = true;
      this.filteredBranches = this.branches;
      const query = this.searchQuery.toLowerCase();
      this.filterLocal = this.locals.filter((local) =>
        local.name.toLowerCase().includes(query)
      );
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

    isDeliveryAvailable(branch) {
      if (!branch.distance || !branch.delivery_radius) return true;
      return branch.distance <= branch.delivery_radius;
    },

    formatDistance(distance) {
      if (!distance) return "Distance unknown";
      return `${distance.toFixed(1)} km away`;
    },

    getDirectionsUrl(branch) {
      const destination = `${branch.latitude},${branch.longitude}`;
      return `https://www.google.com/maps/dir/?api=1&destination=${destination}`;
    },
  },
};
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

/* Scrollbar styling */
.scrollbar-thin {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}
.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}
</style>