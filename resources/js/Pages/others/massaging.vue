<template>
    <AuthenticatedLayout>
        <div class="h-screen flex mx-5 mt-5 mb-5">
    <!-- Contacts Sidebar -->
    <div class="w-80 bg-white border-r border-emerald-200 flex flex-col rounded-l-sm shadow-lg">
      <!-- Search Header -->
      <div class="p-4 border-b border-emerald-100">
        <h2 class="text-emerald-800 text-xl mb-4 font-arabic">رسائل المدرسة</h2>
        <div class="relative">
          <input
            type="text"
            v-model="searchQuery"
            class="w-full pl-10 pr-4 py-2 rounded-lg border border-emerald-200 focus:ring-2 focus:ring-emerald-400 focus:border-transparent"
            placeholder="بحث عن الرسائل..."
          >
          <i class="fas fa-search absolute left-3 top-3 text-emerald-400"></i>
        </div>
      </div>

      <!-- Contacts List -->
      <div class="flex-1 overflow-y-auto">
        <div
          v-for="contact in filteredContacts"
          :key="contact.id"
          @click="selectContact(contact)"
          class="p-4 hover:bg-emerald-50 cursor-pointer transition-colors duration-150"
          :class="{'bg-emerald-50': selectedContact?.id === contact.id}"
        >
          <div class="flex items-center gap-3">
            <div class="relative">
              <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                <i class="fas fa-user text-emerald-600"></i>
              </div>
              <div
                class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white"
                :class="contact.online ? 'bg-emerald-500' : 'bg-gray-400'"
              ></div>
            </div>
            <div class="flex-1">
              <div class="flex justify-between items-start">
                <h4 class="font-medium text-emerald-900">{{ contact.name }}</h4>
                <span class="text-xs text-emerald-600">{{ contact.lastMessageTime }}</span>
              </div>
              <p class="text-sm text-emerald-600 truncate">{{ contact.lastMessage }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col bg-white rounded-r-sm shadow-lg">
      <!-- Chat Header -->
      <div class="h-16 border-b border-emerald-200 px-6 flex items-center justify-between bg-emerald-50 rounded-tr-sm">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
            <i class="fas fa-user text-emerald-600"></i>
          </div>
          <div>
            <h3 class="font-medium text-emerald-900">{{ selectedContact?.name || 'اختر جهة اتصال' }}</h3>
            <p v-if="selectedContact?.online" class="text-xs text-emerald-500">متصل</p>
          </div>
        </div>
        <div class="flex gap-4">
          <button class="text-emerald-600 hover:text-emerald-700">
            <i class="fas fa-phone"></i>
          </button>
          <button class="text-emerald-600 hover:text-emerald-700">
            <i class="fas fa-video"></i>
          </button>
          <button class="text-emerald-600 hover:text-emerald-700">
            <i class="fas fa-info-circle"></i>
          </button>
        </div>
      </div>

      <!-- Messages Area -->
      <div class="flex-1 overflow-y-auto p-6 bg-gray-50 bg-repeat opacity-95" ref="messagesContainer">
        <div
          v-for="message in currentMessages"
          :key="message.id"
          class="mb-4 flex"
          :class="message.sender === 'me' ? 'justify-end' : 'justify-start'"
        >
          <div
            class="max-w-[70%] rounded-lg px-4 py-2"
            :class="message.sender === 'me' ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-900 shadow-md'"
          >
            <p>{{ message.content }}</p>
            <span class="text-xs mt-1 block" :class="message.sender === 'me' ? 'text-emerald-100' : 'text-emerald-500'">
              {{ message.time }}
            </span>
          </div>
        </div>
      </div>

      <!-- Message Input -->
      <div class="p-4 border-t border-emerald-200 bg-emerald-50 rounded-br-2xl">
        <div class="flex items-center gap-3">
          <button class="text-emerald-600 hover:text-emerald-700">
            <i class="fas fa-paperclip"></i>
          </button>
          <div class="flex-1 relative">
            <input
              v-model="newMessage"
              @keyup.enter="sendMessage"
              type="text"
              class="w-full pl-4 pr-10 py-2 rounded-sm border border-emerald-200 focus:ring-2 focus:ring-emerald-400 focus:border-transparent"
              placeholder="اكتب رسالة..."
            >
            <button
              @click="sendMessage"
              class="absolute right-2 top-2 text-emerald-600 hover:text-emerald-700"
            >
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, computed, watch, nextTick } from 'vue'

  const contacts = ref([
    {
      id: 1,
      name: 'John Doe',
      avatar: 'path/to/avatar1.jpg',
      online: true,
      lastMessage: 'Hey, how are you?',
      lastMessageTime: '10:30 AM'
    },
    // Add more contacts
  ])

  const messages = ref([
    {
      id: 1,
      sender: 'me',
      content: 'Hi there!',
      time: '10:30 AM'
    },
    // Add more messages
  ])

  const selectedContact = ref(null)
  const searchQuery = ref('')
  const newMessage = ref('')
  const messagesContainer = ref(null)

  const filteredContacts = computed(() => {
    return contacts.value.filter(contact =>
      contact.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })

  const currentMessages = computed(() => {
    return messages.value.filter(message =>
      message.contactId === selectedContact.value?.id
    )
  })

  const selectContact = (contact) => {
    selectedContact.value = contact
  }

  const sendMessage = async () => {
    if (!newMessage.value.trim()) return

    messages.value.push({
      id: Date.now(),
      sender: 'me',
      content: newMessage.value,
      time: new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric' }),
      contactId: selectedContact.value?.id
    })

    newMessage.value = ''

    await nextTick()
    scrollToBottom()
  }

  const scrollToBottom = () => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  }

  watch(currentMessages, () => {
    nextTick(() => scrollToBottom())
  })
  </script>
