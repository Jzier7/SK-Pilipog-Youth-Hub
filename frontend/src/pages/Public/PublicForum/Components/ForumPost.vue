<template>
  <q-card flat bordered>
    <q-card-section>
      <div class="q-mb-sm">
        <div class="row justify-between items-center no-wrap">
          <div>
            <h4 class="text-primary"><strong>{{ postData.author.first_name }} {{ postData.author.last_name }}</strong></h4>
            <span class="text-gray-500">{{ postData.author.username }}</span>
            <div class="text-caption text-secondary">{{ timeAgo(postData.created_at) }}</div>
          </div>

          <q-btn
            v-if="isAuthor"
            flat
            dense
            round
            icon="more_vert"
            class="q-ml-sm"
            style="align-self: flex-start"
          >
            <q-menu v-model="menu">
              <q-list>
                <q-item clickable @click="editPost">
                  <q-item-section>Edit</q-item-section>
                </q-item>
                <q-item clickable @click="deletePost(postData.id)">
                  <q-item-section class="text-negative">Delete</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </div>

      <div v-if="!isEditing">
        <q-separator />
        <p class="q-pt-md">{{ postData.post }}</p>
      </div>
      <div v-else>
        <q-input
          v-model="editedPost"
          type="textarea"
          autogrow
          dense
          filled
        />
        <q-btn
          label="Save"
          color="primary"
          @click="saveEdit(postData)"
          class="q-mt-sm"
        />
      </div>
    </q-card-section>

    <q-card-actions align="right">
      <q-btn
        flat
        icon="chat_bubble_outline"
        class="q-ml-sm"
        @click="toggleComments"
      >
        <q-badge color="primary" floating>{{ postData.comments.length }}</q-badge>
      </q-btn>
    </q-card-actions>

    <q-slide-transition>
      <div v-if="showComments">
        <Comment :post="postData.id" />
      </div>
    </q-slide-transition>
  </q-card>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import forumPostService from 'src/services/forumPostService';
import dateMixin from 'src/utils/mixins/dateMixin'
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  components: {
    Comment: defineAsyncComponent(() => import('./PostComment.vue')),
  },
  mixins: [dateMixin],
  props: {
    fetchPosts: {
      type: Function,
      required: true
    },
    post: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      postData: { ...this.post },
      showComments: false,
      menu: false,
      isEditing: false,
      editedPost: this.post.post,
    };
  },
  watch: {
    post: {
      immediate: true,
      handler(newPost) {
        this.postData = { ...newPost };
        this.editedPost = newPost.post;
      }
    }
  },
  computed: {
    isAuthor() {
      const userStore = useUserStore();
      return this.post.author.id === userStore.userData?.id;
    },
  },
  methods: {
    toggleComments() {
      this.showComments = !this.showComments;
    },
    editPost() {
      this.isEditing = true;
      this.menu = false;
    },
    async saveEdit(data) {
      try {
        const response = await forumPostService.updatePost({
          id: data.id,
          post: this.editedPost
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.isEditing = false;
        await this.fetchPosts();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error editing post.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async deletePost(id) {
      try {
        const response = await forumPostService.deletePost({
          id: id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        await this.fetchPosts();
        this.menu = false;
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting post.'
        });
      }
    },
  },
};
</script>

