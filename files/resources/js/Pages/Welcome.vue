<template>
    <div class="min-h-screen dark:bg-gray-900">
      <!-- Hero Section -->
      <div class="relative min-h-screen">
        <div
          class="absolute inset-0 bg-black/50 dark:bg-black/90"
          :style="{
            backgroundImage: `url('/food1.jpg')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }"
        >
          <div class="absolute inset-0 bg-black opacity-70"></div>
        </div>

        <div class="relative z-10">
          <!-- Navigation -->
          <nav class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
              <h1 class="text-3xl font-bold text-white dark:text-gray-100">Ghorer Menu</h1>
              <div class="flex items-center gap-4">
                <div class="hidden md:flex space-x-8 text-white dark:text-gray-300 ml-4">
                  <a href="#about" class="hover:text-orange-400 transition-colors dark:hover:text-orange-300">About</a>
                  <a href="#features" class="hover:text-orange-400 transition-colors dark:hover:text-orange-300">Features</a>
                  <a href="#contact" class="hover:text-orange-400 transition-colors dark:hover:text-orange-300">Contact</a>
                </div>
                <div class="fixed top-4 right-4 z-50">
                    <button @click="switchTheme" class="text-orange-400 dark:text-gray-300 transition-colors hover:text-orange-400 dark:hover:text-orange-300 p-2 rounded-full focus:outline-none">
                        <i class="fa-solid fa-circle-half-stroke text-lg"></i>
                    </button>
                </div>

              </div>
            </div>
          </nav>

          <!-- Hero Content -->
          <div class="container mx-auto px-6 py-24 text-center" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl lg:text-7xl font-bold text-white mb-8 dark:text-gray-100">
              Elevate Your
              <br />
              <span class="bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent">
                Dining Experience
              </span>
            </h2>
            <p class="text-lg md:text-xl lg:text-2xl text-gray-200 mb-12 max-w-3xl mx-auto dark:text-gray-400">
              Experience culinary excellence delivered to your doorstep with our premium food delivery service.
            </p>
            <button
              @click="startOrder"
              class="group bg-gradient-to-r from-orange-500 to-red-500 text-white text-lg md:text-xl lg:text-xl px-6 md:px-8 lg:px-12 py-3 rounded-full
              hover:from-orange-600 hover:to-red-600 transform hover:scale-105 transition-all duration-300
              shadow-lg hover:shadow-2xl hover:shadow-orange-500/20 dark:hover:shadow-orange-300/20"
              data-aos="zoom-in"
            >
              Order Now
              <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 md:p-8 max-w-md w-full text-center shadow-lg transition-transform transform hover:scale-105">
          <h3 class="text-2xl font-semibold mb-4 dark:text-gray-100">We Are Open for Orders!</h3>
          <p class="text-gray-700 dark:text-gray-300 mb-6">
            Call us at <span class="font-bold text-orange-500">(123) 456-7890</span> to place your order.
            Our web app will be available soon for online orders.
          </p>
          <a
            href="tel:1234567890"
            class="bg-gradient-to-r from-orange-500 to-red-500 text-white text-lg md:text-xl px-6 md:px-8 py-2 rounded-full
            hover:bg-orange-600 transition-all duration-300 transform hover:scale-105"
          >
            Call Now
          </a>
          <button
            @click="closeModal"
            class="mt-4 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-full
            hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300"
          >
            Close
          </button>
        </div>
      </div>

      <!-- Features Section -->
      <section id="features" class="py-24 bg-orange-50 dark:bg-gray-800" data-aos="fade-up">
        <div class="container mx-auto px-6">
          <h2 class="text-4xl font-bold text-center mb-16 dark:text-gray-100">Why Choose Ghorer Menu</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div
              v-for="(feature, index) in features"
              :key="index"
              class="bg-white rounded-2xl p-6 md:p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 dark:bg-gray-700 dark:text-gray-100"
              data-aos="fade-up"
              data-aos-delay="100 * index"
            >
              <i :class="feature.icon" class="text-4xl text-orange-500 mb-6"></i>
              <h3 class="text-lg md:text-xl font-semibold mb-4">{{ feature.title }}</h3>
              <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">{{ feature.desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- How It Works -->
      <section class="py-24 bg-white dark:bg-gray-900" data-aos="fade-up">
        <div class="container mx-auto px-6">
          <h2 class="text-4xl font-bold text-center mb-16 dark:text-gray-100">How It Works</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div v-for="(step, index) in steps" :key="index" class="relative" data-aos="fade-up" data-aos-delay="100 * index">
              <div class="text-8xl font-bold text-orange-100 absolute -top-10 -left-6 z-0 dark:text-gray-700">
                {{ step.number }}
              </div>
              <div class="relative z-10">
                <h3 class="text-lg md:text-xl font-semibold mb-4 dark:text-gray-100">{{ step.title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">{{ step.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Statistics -->
      <section class="py-24 bg-gradient-to-r from-orange-500 to-red-500 text-white dark:from-gray-800 dark:to-gray-900" data-aos="fade-up">
        <div class="container mx-auto px-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-12 text-center">
            <div v-for="(stat, index) in statistics" :key="index" data-aos="fade-up" data-aos-delay="100 * index">
              <div class="text-4xl font-bold mb-2">{{ stat.number }}</div>
              <div class="text-lg">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Call to Action -->
      <section class="py-24 bg-gray-900 text-white dark:bg-gray-700 dark:text-gray-100" data-aos="fade-up">
        <div class="container mx-auto px-6 text-center">
          <h2 class="text-4xl font-bold mb-8">Ready to Experience Premium Food Delivery?</h2>
          <p class="text-lg md:text-xl text-gray-400 mb-12 max-w-2xl mx-auto dark:text-gray-300">
            Join thousands of satisfied customers who have made Ghorer Menu their preferred choice for premium food delivery.
          </p>
          <button
            @click="startOrder"
            class="group bg-gradient-to-r from-orange-500 to-red-500 text-white text-lg md:text-xl px-6 md:px-8 lg:px-12 py-3 rounded-full
                hover:from-orange-600 hover:to-red-600 transform hover:scale-105 transition-all duration-300"
            data-aos="zoom-in"
          >
            Start Your Order
            <i class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </button>
        </div>
      </section>

      <!-- Contact Section -->
      <section id="contact" class="py-24 bg-white dark:bg-gray-900" data-aos="fade-up">
        <div class="container mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          <div>
            <h2 class="text-4xl font-bold mb-8 dark:text-gray-100">Get in Touch</h2>
            <p class="text-gray-600 mb-8 dark:text-gray-400">
              Have questions or feedback? We're here to help make your experience exceptional.
            </p>
            <div class="space-y-6">
              <div v-for="(contact, index) in contactInfo" :key="index" class="flex items-center dark:text-gray-300" data-aos="fade-up" data-aos-delay="100 * index">
                <i :class="contact.icon" class="text-orange-500 mr-4 w-6"></i>
                <span class="text-sm md:text-base">{{ contact.text }}</span>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 md:p-8" data-aos="fade-up">
            <form @submit.prevent="submitForm" class="space-y-6">
              <input
                v-model="form.name"
                type="text"
                placeholder="Your Name"
                class="w-full px-4 py-2 md:py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-gray-100"
              />
              <input
                v-model="form.email"
                type="email"
                placeholder="Your Email"
                class="w-full px-4 py-2 md:py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-gray-100"
              />
              <textarea
                v-model="form.message"
                placeholder="Your Message"
                rows="4"
                class="w-full px-4 py-2 md:py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-gray-100"
              ></textarea>
              <button
                type="submit"
                class="bg-gradient-to-r from-orange-500 to-red-500 text-white text-lg md:text-xl px-6 md:px-8 py-2 rounded-lg
                    hover:from-orange-600 hover:to-red-600 transform hover:scale-105 transition-all duration-300"
                data-aos="zoom-in"
              >
                Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

  </div>

  <!-- Footer Section -->
<footer class="py-12 bg-gray-900 text-white dark:bg-gray-800">
  <div class="container mx-auto px-6 text-center">
    <p class="text-sm mb-4 dark:text-gray-400">
      &copy; {{ new Date().getFullYear() }} Ghorer Menu. All rights reserved.
    </p>
    <div class="flex justify-center space-x-6">
      <a href="#about" class="hover:text-orange-400 transition-colors dark:hover:text-orange-300">About Us</a>
      <a href="#features" class="hover:text-orange-400 transition-colors dark:hover:text-orange-300">Features</a>
      <a href="#contact" class="hover:text-orange-400 transition-colors dark:hover:text-orange-300">Contact</a>
    </div>
    <div class="mt-4">
      <a href="https://www.facebook.com" target="_blank" class="text-orange-400 hover:text-orange-300">
        <i class="fab fa-facebook"></i>
      </a>
      <a href="https://www.twitter.com" target="_blank" class="text-orange-400 hover:text-orange-300 mx-4">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://www.instagram.com" target="_blank" class="text-orange-400 hover:text-orange-300">
        <i class="fab fa-instagram"></i>
      </a>
    </div>
  </div>
</footer>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { switchTheme } from '@/theme';


const features = ref([
  { icon: 'fas fa-utensils', title: 'Quality Ingredients', desc: 'We source the freshest ingredients for every dish.' },
  { icon: 'fas fa-clock', title: 'Fast Delivery', desc: 'Get your favorite meals delivered quickly and safely.' },
  { icon: 'fas fa-headset', title: '24/7 Support', desc: 'Our customer support is here to help you anytime.' },
]);

const steps = ref([
  { number: '1', title: 'Order', desc: 'Choose your favorite meals from our menu.' },
  { number: '2', title: 'Prepare', desc: 'Our chefs prepare your order with care.' },
  { number: '3', title: 'Deliver', desc: 'We deliver your food hot and fresh to your door.' },
]);

const statistics = ref([
  { number: '1000+', label: 'Happy Customers' },
  { number: '500+', label: 'Delivered Meals' },
  { number: '50+', label: 'Restaurants Partnered' },
  { number: '24/7', label: 'Support Hours' },
]);

const contactInfo = ref([
  { icon: 'fas fa-phone-alt', text: 'Call us: (123) 456-7890' },
  { icon: 'fas fa-envelope', text: 'Email: info@tripletaste.com' },
  { icon: 'fas fa-map-marker-alt', text: 'Address: 123 Food St, Gourmet City' },
]);

const form = ref({ name: '', email: '', message: '' });
const showModal = ref(false);

const startOrder = () => {
  showModal.value = true;
};

const submitForm = () => {
  alert(`Message sent: ${form.value.message}`);
  form.value = { name: '', email: '', message: '' }; // Reset form
};

const closeModal = () => {
  showModal.value = false;
};


onMounted(() => {
  AOS.init({ duration: 1000 });
});
</script>

<style>
/* Add any additional styles here */
</style>
