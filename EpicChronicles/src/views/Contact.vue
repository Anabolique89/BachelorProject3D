<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-white mb-4">Contact Us</h1>
        <p class="text-lg text-gray-400">
          Have questions? We're here to help you on your Viking journey!
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
          <h2 class="text-xl font-bold text-white mb-6">Send us a message</h2>

          <!-- Success Message -->
          <div v-if="showSuccess" class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <p class="text-green-400 font-medium">Message sent successfully! We'll get back to you soon.</p>
            </div>
          </div>

          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-300 mb-1">
                Your Name *
              </label>
              <input
                v-model="form.name"
                type="text"
                id="name"
                required
                class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                placeholder="Ragnar Lothbrok"
              />
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-300 mb-1">
                Email Address *
              </label>
              <input
                v-model="form.email"
                type="email"
                id="email"
                required
                class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                placeholder="ragnar@vikings.com"
              />
            </div>

            <!-- Subject -->
            <div>
              <label for="subject" class="block text-sm font-medium text-gray-300 mb-1">
                Subject *
              </label>
              <select
                v-model="form.subject"
                id="subject"
                required
                class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
              >
                <option value="">Select a subject</option>
                <option value="Order Inquiry">Order Inquiry</option>
                <option value="Product Question">Product Question</option>
                <option value="Shipping">Shipping</option>
                <option value="Returns & Refunds">Returns & Refunds</option>
                <option value="Technical Support">Technical Support</option>
                <option value="Partnership">Partnership Opportunity</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <!-- Order Number (Optional) -->
            <div>
              <label for="order_number" class="block text-sm font-medium text-gray-300 mb-1">
                Order Number (Optional)
              </label>
              <input
                v-model="form.order_number"
                type="text"
                id="order_number"
                class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                placeholder="EC-ABC123"
              />
            </div>

            <!-- Message -->
            <div>
              <label for="message" class="block text-sm font-medium text-gray-300 mb-1">
                Message *
              </label>
              <textarea
                v-model="form.message"
                id="message"
                rows="5"
                required
                class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                placeholder="Tell us how we can help you..."
              ></textarea>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="submitting"
              class="w-full px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              {{ submitting ? 'Sending...' : 'Send Message' }}
            </button>
          </form>
        </div>

        <!-- Contact Information -->
        <div class="space-y-6">
          <!-- Business Hours -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center mb-4">
              <div class="h-10 w-10 rounded-full bg-yellow-400/20 flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-white">Business Hours</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-400">
              <p><span class="text-white font-medium">Monday - Friday:</span> 9:00 AM - 6:00 PM</p>
              <p><span class="text-white font-medium">Saturday:</span> 10:00 AM - 4:00 PM</p>
              <p><span class="text-white font-medium">Sunday:</span> Closed</p>
              <p class="text-xs mt-2 text-gray-500">Copenhagen Time (CET/CEST)</p>
            </div>
          </div>

          <!-- Email -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center mb-4">
              <div class="h-10 w-10 rounded-full bg-blue-400/20 flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-white">Email</h3>
            </div>
            <a href="mailto:support@epicchronicles.com" class="text-yellow-400 hover:text-yellow-300 transition-colors">
              support@epicchronicles.com
            </a>
            <p class="text-sm text-gray-400 mt-2">
              We typically respond within 24 hours
            </p>
          </div>

          <!-- Phone -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center mb-4">
              <div class="h-10 w-10 rounded-full bg-green-400/20 flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-white">Phone</h3>
            </div>
            <a href="tel:+4512345678" class="text-yellow-400 hover:text-yellow-300 transition-colors text-lg">
              +45 12 34 56 78
            </a>
            <p class="text-sm text-gray-400 mt-2">
              Available during business hours
            </p>
          </div>

          <!-- Social Media -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center mb-4">
              <div class="h-10 w-10 rounded-full bg-purple-400/20 flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-white">Follow Us</h3>
            </div>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
            </div>
          </div>

          <!-- FAQ Link -->
          <div class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 backdrop-blur-lg rounded-lg border border-yellow-400/30 p-6">
            <h3 class="text-lg font-bold text-white mb-2">Need Quick Answers?</h3>
            <p class="text-sm text-gray-300 mb-4">
              Check out our FAQ section for instant answers to common questions.
            </p>
            <a href="#" class="inline-flex items-center text-yellow-400 hover:text-yellow-300 font-medium">
              Visit FAQ
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axiosClient from '../axios'

const form = ref({
  name: '',
  email: '',
  subject: '',
  order_number: '',
  message: '',
})

const submitting = ref(false)
const showSuccess = ref(false)

async function submitForm() {
  submitting.value = true
  showSuccess.value = false

  try {
    // TODO: Create contact form endpoint when ready
    await axiosClient.post('/contact', form.value)
    
    showSuccess.value = true
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      subject: '',
      order_number: '',
      message: '',
    }

    // Hide success message after 5 seconds
    setTimeout(() => {
      showSuccess.value = false
    }, 5000)

  } catch (error) {
    console.error('Contact form error:', error)
    alert('❌ Failed to send message. Please try again or email us directly.')
  } finally {
    submitting.value = false
  }
}
</script>