<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">FORUM</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <q-card flat bordered class="q-mb-md">
      <q-card-section class="bg-primary q-pa-none">
        <q-input
          filled
          type="textarea"
          placeholder="What's new?"
          v-model="newPost"
          class="bg-white text-white"
        />
        <div class="q-pa-md text-right">
          <q-btn
            label="POST"
            color="white"
            text-color="primary"
            @click="submitPost"
            class="q-ma-none lowercase"
          />
        </div>
      </q-card-section>
    </q-card>

    <div class="row justify-between q-mb-md q-mt-lg">
      <q-space />
      <form @submit.prevent="fetchPosts">
        <q-input
          rounded
          outlined
          dense
          v-model="search"
          placeholder="Search Posts"
          @input="filterPosts"
          color="primary"
        >
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </form>
    </div>

    <Post
      v-for="(post) in posts"
      :key="post.id"
      :post="post"
      :fetchPosts="fetchPosts"
      class="q-my-sm"
    />

    <div class="q-pa-md text-center">
      <q-btn
        flat
        size="md"
        label="Show More Posts . . . ."
        color="primary"
        @click="loadMorePosts"
        v-if="currentPage < lastPage"
      />
    </div>
  </q-page>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import forumPostService from 'src/services/forumPostService';

export default {
  components: {
    Post: defineAsyncComponent(() => import('./Components/ForumPost.vue')),
  },
  data() {
    return {
      newPost: '',
      posts: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
    };
  },
  watch: {
    search(newVal) {
      this.currentPage = 1;

      clearTimeout(this.debounceTimeout);

      this.debounceTimeout = setTimeout(() => {
        this.fetchPosts();
      }, 1000);
    },
  },
  mounted() {
    this.fetchPosts();
  },
  methods: {
    filterPosts() {
      this.currentPage = 1;
      this.fetchPosts();
    },
    async submitPost() {
      try {
        const response = await forumPostService.storePost({
          post: this.newPost,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchPosts();
        this.newPost = '';
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding post.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchPosts() {
      try {
        if (this.currentPage === 1) {
          this.posts = [];
        }

        const response = await forumPostService.getPosts({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        });
        this.posts = [...this.posts, ...response.data.body || []];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    },
    async loadMorePosts() {
      if (this.currentPage < this.lastPage) {
        this.currentPage++;
        try {
          const response = await forumPostService.getPosts({
            search: this.search,
            currentPage: this.currentPage,
            pageSize: this.pageSize,
          });
          this.posts = this.posts.concat(response.data.body || []);
        } catch (error) {
          console.error('Error loading more posts:', error);
        }
      }
    },
  },
};
</script>

