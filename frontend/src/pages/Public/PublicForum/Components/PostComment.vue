<template>
  <q-card-section class="bg-grey-1 q-pa-md">
    <h5 class="text-primary q-mb-sm">Comments</h5>
    <div class="q-pb-md text-start">
      <q-btn
        flat
        size="sm"
        label="Show Older Comments . . . ."
        color="primary"
        @click="loadMoreComments"
        v-if="currentPage < lastPage"
      />
    </div>
    <div v-for="comment in [...comments].reverse()" :key="comment.id" class="q-mb-md">
      <q-separator />
      <div class="q-pt-md text-secondary flex justify-between items-center">
        <strong>{{ isAuthor(comment) ? 'You' : `${comment.author.first_name} ${comment.author.last_name}` }}</strong>
        <div v-if="isAuthor(comment)">
          <q-btn
            flat
            dense
            round
            icon="more_vert"
            size="sm"
            class="q-ml-sm"
          >
            <q-menu v-model="menuStates[comment.id]">
              <q-list>
                <q-item clickable @click="editComment(comment)">
                  <q-item-section>Edit</q-item-section>
                </q-item>
                <q-item clickable @click="deleteComment(comment.id)">
                  <q-item-section class="text-negative">Delete</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </div>
      <span class="text-caption text-gray-500">{{ timeAgo(comment.created_at) }}</span>
      <div>{{ comment.comment }}</div>
    </div>

    <div v-show="!isGuest">
      <q-input
        outlined
        type="textarea"
        placeholder="Add a comment..."
        v-model="newComment"
        class="bg-white text-black q-mt-lg"
      />
      <div class="text-right q-mt-sm">
        <q-btn
          v-if="!isEditing"
          label="COMMENT"
          color="primary"
          text-color="white"
          @click="submitComment"
        />
        <q-btn
          v-else
          label="EDIT"
          color="primary"
          text-color="white"
          @click="updateComment"
        />
      </div>
    </div>

  </q-card-section>
</template>

<script>
import { Notify } from 'quasar';
import forumCommentService from 'src/services/forumCommentService';
import dateMixin from 'src/utils/mixins/dateMixin';
import { useUserStore } from 'src/stores/modules/userStore';
import { USER_ROLES } from 'src/utils/constants';

export default {
  mixins: [dateMixin],
  props: {
    post: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      newComment: '',
      comments: [],
      search: '',
      currentPage: 1,
      pageSize: 5,
      lastPage: 1,
      total: 0,
      orderBy: 'desc',
      errors: {},
      menuStates: {},
      isEditing: false,
      editingCommentId: null,
    };
  },
  computed: {
    userId() {
      const userStore = useUserStore();
      return userStore.userData?.id;
    },
    isGuest() {
      const userStore = useUserStore();
      return userStore.userData?.role.slug === USER_ROLES.GUEST;
    }
  },
  mounted() {
    this.fetchComments();
  },
  methods: {
    isAuthor(comment) {
      return comment.author.id === this.userId;
    },
    toggleMenu(commentId) {
      this.menuStates[commentId] = !this.menuStates[commentId];
    },
    async editComment(comment) {
      this.newComment = comment.comment;
      this.isEditing = true;
      this.editingCommentId = comment.id;
      this.menuStates[comment.id] = false;
    },
    async submitComment() {
      try {
        const response = await forumCommentService.storeComment({
          post: this.post,
          comment: this.newComment,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchComments();
        this.newComment = '';
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding comment.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async updateComment() {
      try {
        const response = await forumCommentService.updateComment({
          id: this.editingCommentId,
          comment: this.newComment,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchComments();
        this.newComment = '';
        this.isEditing = false;
        this.editingCommentId = null;
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error updating comment.'
        });
      }
    },
    async fetchComments() {
      try {
        const response = await forumCommentService.getComments({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          orderBy: this.orderBy,
          post: this.post
        });
        this.comments = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    },
    async loadMoreComments() {
      if (this.currentPage < this.lastPage) {
        this.currentPage++;
        try {
          const response = await forumCommentService.getComments({
            search: this.search,
            currentPage: this.currentPage,
            pageSize: this.pageSize,
            orderBy: this.orderBy,
            post: this.post
          });
          this.comments = this.comments.concat(response.data.body || []);
        } catch (error) {
          console.error('Error loading more comments:', error);
        }
      }
    },
    async deleteComment(commentId) {
      try {
        await forumCommentService.deleteComment({
          id: commentId
        });
        Notify.create({
          type: 'positive',
          position: 'top',
          message: 'Comment deleted successfully.'
        });
        this.fetchComments();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting comment.'
        });
      }
      this.menuStates[commentId] = false;
    },
  },
};
</script>

<style scoped>
.flex {
  display: flex;
}
.justify-between {
  justify-content: space-between;
}
.items-center {
  align-items: center;
}
</style>

