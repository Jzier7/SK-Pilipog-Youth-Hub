<template>
  <div v-if="isVisible" class="floating-chatbox" :class="{ 'single-conversation-width': !selectedConversation }">
    <q-card class="bg-white text-black" :class="{ 'single-conversation-card': !selectedConversation }">
      <q-card-section class="m-4">
        <div class="text-h6">Chat Support</div>
      </q-card-section>
      <q-separator />

      <div :class="{ 'chat-layout': selectedConversation, 'single-column-layout': !selectedConversation }">
        <q-card-section class="conversation-list">
          <q-list class="p-2">
            <q-item v-for="conversation in sortedConversations" :key="conversation.chat_mate_id" clickable
              :active="conversation.selected" @click="selectConversation(conversation.chat_mate_id)"
              :class="{ 'bg-grey-4 rounded-lg': conversation.selected }">
              <q-item-section>
                <div class="text-subtitle1 text-bold text-primary">{{ conversation.chat_mate }}</div>
                <div class="text-caption text-grey-7">{{ timeAgo(conversation.recent_message_date) }}</div>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>

        <q-card-section v-if="selectedConversation" ref="messageList">
          <div class="message-list">
            <div v-if="selectedConversation.messages.length > 0" class="p-6">
              <q-chat-message v-for="(message, idx) in selectedConversation.messages" :key="idx"
                :name="message.sender_id === userId ? userName : (message.sender_role === 'super-admin' || message.sender_role === 'admin' ? message.sender_name : selectedConversation.chat_mate)"
                :text="[message.content]"
                :text-color="message.sender_id === userId || message.sender_role === 'super-admin' || message.sender_role === 'admin' ? 'white' : 'black'"
                :bg-color="message.sender_id === userId || message.sender_role === 'super-admin' || message.sender_role === 'admin' ? 'secondary' : 'grey-4'"
                :sent="message.sender_id === userId || message.sender_role === 'super-admin' || message.sender_role === 'admin'"
                :class="{
                  'my-message': message.sender_id === userId || message.sender_role === 'super-admin' || message.sender_role === 'admin',
                  'other-message': message.sender_id !== userId && message.sender_role !== 'super-admin' && message.sender_role !== 'admin'
                }" />
            </div>
            <div v-else>
              <p>No messages.</p>
            </div>
          </div>
          <div class="sticky-input-container">
            <q-input v-model="newMessage" placeholder="Type your message..." type="textarea" outlined dense auto-grow
              @keyup.enter="sendMessage">
              <template v-slot:append>
                <q-btn icon="send" color="primary" round dense flat @click="sendMessage" />
              </template>
            </q-input>
          </div>
        </q-card-section>
      </div>

      <q-btn icon="close" round dense flat color="primary" class="close-btn" @click="closeMessageBox" />
    </q-card>
  </div>
</template>

<script>
import { useModalStore } from 'src/stores/modules/modalStore';
import { useUserStore } from 'src/stores/modules/userStore';
import messageService from 'src/services/messageService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  props: {
    unreadMessages: {
      type: Number,
      required: true
    }
  },
  mixins: [dateMixin],
  data() {
    return {
      messages: [],
      newMessage: '',
      selectedConversation: null,
      modalStore: useModalStore(),
      userStore: useUserStore(),
      userRole: null,
      userId: null,
      userName: null
    };
  },
  computed: {
    isVisible() {
      return this.modalStore.showChatBox;
    },
    sortedConversations() {
      return [...this.messages].sort((a, b) => {
        if (a.chat_mate === 'User') return -1;
        if (b.chat_mate === 'User') return 1;
        return new Date(b.recent_message_date) - new Date(a.recent_message_date);
      });
    }
  },
  mounted() {
    this.userRole = this.userStore.userData?.role.slug;
    this.userId = this.userStore.userData?.id;
    this.userName = this.userStore.userData?.first_name;
    this.fetchMessages();
  },
  methods: {
    closeMessageBox() {
      this.modalStore.setShowChatBox(false);
    },
    async fetchMessages() {
      try {
        const response = await messageService.retrieveAdmin();
        const conversations = Object.values(response.data.body || {});

        this.messages = conversations.map(conversation => ({
          ...conversation,
          selected: false
        }));

        this.$nextTick(() => {
          this.scrollToBottom();
        });
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    },
    selectConversation(chatMateId) {
      this.messages.forEach((conversation) => {
        conversation.selected = false;
      });

      const selectedConversations = this.messages.filter(conversation =>
        conversation.chat_mate_id === chatMateId || conversation.sender_id === chatMateId || conversation.receiver_id === chatMateId
      );

      if (selectedConversations.length > 0) {
        selectedConversations.forEach(conversation => {
          conversation.selected = true;
        });

        this.selectedConversation = selectedConversations[0];
        this.scrollToBottom();
      }
    },
    async sendMessage() {
      if (!this.newMessage.trim()) return;

      try {
        const message = {
          sender_id: this.userId,
          receiver_id: this.selectedConversation.chat_mate_id,
          content: this.newMessage.trim()
        };

        await messageService.send(message);

        this.selectedConversation.messages.push({
          ...message,
          created_at: new Date().toISOString(),
          read: false
        });

        this.newMessage = '';
        this.$nextTick(() => {
          this.scrollToBottom();
        });
      } catch (error) {
        console.error('Error sending message:', error);
      }
    },
    scrollToBottom() {
      const messageList = this.$refs.messageList;
      if (messageList) {
        messageList.scrollTop = messageList.scrollHeight;
      }
    }
  }
};
</script>

<style scoped lang="scss">
.floating-chatbox {
  position: fixed;
  bottom: 18px;
  right: 18px;
  z-index: 1000;
  width: 800px;

  &.single-conversation-width {
    width: 300px;
  }

  @media (max-width: 1024px) {
    width: 100%;
    max-width: 600px;

    &.single-conversation-width {
      width: 100%;
    }
  }

  @media (max-width: 768px) {
    width: 100%;
    bottom: 0;
    right: 0;

    &.single-conversation-width {
      width: 100%;
    }
  }
}

.q-card {
  min-width: 800px;
  position: relative;
  max-height: 700px;
  border: 2px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

  &.single-conversation-card {
    min-width: 300px;
  }

  @media (max-width: 1024px) {
    min-width: 100%;
  }

  @media (max-width: 768px) {
    min-width: 100%;
    border-radius: 0;
  }
}

.chat-layout {
  display: flex;
  flex-direction: row;

  .conversation-list {
    width: 100%;
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
    border-right: 2px solid #ccc;
    margin-bottom: 10px;
  }

  .message-list {
    max-height: 400px;
    overflow-y: auto;
    display: flex;
    flex-direction: column-reverse;
    padding-bottom: 60px;
  }

  @media (max-width: 768px) {
    flex-direction: column;

    .conversation-list {
      width: 100%;
      max-height: 200px;
      border-right: none;
      border-bottom: 2px solid #ccc;
    }

    .message-list {
      width: 100%;
    }
  }
}

.single-column-layout {
  display: flex;
  flex-direction: column;

  .conversation-list {
    width: 100%;
    max-height: 200px;
    border-right: none;
    border-bottom: 2px solid #ccc;
  }

  .message-list {
    width: 100%;
  }
}

.q-input {
  margin-top: 10px;
  width: 100%;
}

.q-separator {
  margin: 10px 0;
}

.my-message {
  margin-left: 150px;

  @media (max-width: 768px) {
    margin-left: 0;
  }
}

.other-message {
  margin-right: 150px;

  @media (max-width: 768px) {
    margin-right: 0;
  }
}

.sticky-input-container {
  position: sticky;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 10;
  background: white;
  padding: 10px;
}

.close-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  z-index: 11;
}
</style>
