<template>
  <div v-if="isVisible" class="floating-chatbox">
    <q-card class="bg-white text-black">
      <q-card-section>
        <div class="text-h6">Chat Support</div>
      </q-card-section>
      <q-separator />
      <q-card-section ref="messageList" class="message-list">
        <div v-if="messages.length > 0">
          <div v-for="(conversation, index) in messages" :key="index" class="conversation">
            <q-chat-message v-for="(message, idx) in conversation.messages" :key="idx"
              :name="message.sender_id === userId ? 'me' : conversation.chat_mate" :text="[message.content]"
              :text-color="message.sender_id === userId ? 'white' : 'black'"
              :bg-color="message.sender_id === userId ? 'secondary' : 'grey-4'" :sent="message.sender_id === userId"
              :class="{
                'my-message': message.sender_id === userId,
                'other-message': message.sender_id !== userId
              }" />
          </div>
        </div>
        <div v-else>
          <p>No messages.</p>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <q-input v-model="newMessage" placeholder="Type your message..." type="textarea" outlined dense auto-grow
          @keyup.enter="sendMessage">
          <template v-slot:append>
            <q-btn icon="send" color="primary" round dense flat @click="sendMessage" />
          </template>
        </q-input>
      </q-card-section>
      <q-btn icon="close" round dense flat color="primary" class="close-btn" @click="closeMessageBox" />
    </q-card>
  </div>
</template>

<script>
import { useModalStore } from 'src/stores/modules/modalStore';
import { useUserStore } from 'src/stores/modules/userStore';
import messageService from 'src/services/messageService';

export default {
  props: {
    unreadMessages: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      messages: [],
      newMessage: '',
      modalStore: useModalStore(),
      userStore: useUserStore(),
      userId: null,
    };
  },
  computed: {
    isVisible() {
      return this.modalStore.showChatBox;
    }
  },
  mounted() {
    this.userId = this.userStore.userData?.id;
    this.fetchMessages();
  },
  methods: {
    closeMessageBox() {
      this.modalStore.setShowChatBox(false);
    },
    async fetchMessages() {
      try {
        const response = await messageService.retrieveUser();
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
    async sendMessage() {
      if (!this.newMessage.trim()) return;

      try {
        const message = {
          sender_id: this.userId,
          receiver_id: this.getSelectedReceiverId(),
          content: this.newMessage.trim()
        };

        await messageService.send(message);

        this.messages[0].messages.push({
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
    },
    getSelectedReceiverId() {
      return this.messages[0]?.chat_mate_id || null;
    }
  },
};
</script>

<style scoped>
.floating-chatbox {
  position: fixed;
  bottom: 18px;
  right: 18px;
  z-index: 1000;
  width: 100%;
  max-width: 500px;
  box-sizing: border-box;
}

.q-card {
  min-width: 100%;
  position: relative;
  max-height: 700px;
  border: 2px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.q-card-actions {
  justify-content: flex-end;
}

.close-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  z-index: 2000;
}

.message-list {
  max-height: 400px;
  overflow-y: auto;
  display: flex;
  flex-direction: column-reverse;
}

.q-input {
  margin-top: 10px;
}

.conversation {
  margin-bottom: 10px;
}

.chat-message {
  max-width: 80%;
  margin: 5px 0;
  word-wrap: break-word;
}

.my-message {
  margin-left: 150px;
}

.other-message {
  margin-right: 150px;
}

/* Media Queries for Responsiveness */
@media (max-width: 600px) {
  .floating-chatbox {
    width: 90%;
    right: 10px;
    bottom: 10px;
  }

  .q-card {
    min-width: 100%;
  }
}

@media (max-width: 400px) {
  .my-message {
    margin-left: 100px;
  }

  .other-message {
    margin-right: 100px;
  }
}
</style>
