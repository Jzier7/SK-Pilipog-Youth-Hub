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

    <q-card flat bordered>
      <q-card-section>
        <div class="q-mb-sm">
          <div class="text-subtitle1">John Wilche</div>
          <div class="text-caption">2 hours ago</div>
        </div>
        <div>
          <p>
            Tinood, gibati gayud sa mga Pilipino ang imong gugma ug dedikadong serbisyo publiko. Kami nagapasalamat sa imong pagpahinungod ug kontribusyon alang sa atong barangay.
          </p>
        </div>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat icon="chat_bubble_outline" class="q-ml-sm" @click="toggleComments">
          <q-badge color="primary" floating>{{ comments.length }}</q-badge>
        </q-btn>
      </q-card-actions>

      <q-slide-transition>
        <div v-if="showComments">
          <q-card-section class="bg-grey-2 q-pa-md">
            <div v-for="(comment, index) in comments" :key="index" class="q-mb-sm">
              <div class="text-body2"><strong>{{ comment.author }}</strong></div>
              <div class="text-caption text-grey">{{ comment.time }}</div>
              <div>{{ comment.text }}</div>
            </div>
            <q-input
              filled
              type="textarea"
              placeholder="Add a comment..."
              v-model="newComment"
              class="bg-white text-black q-mt-md"
            />
            <div class="text-right q-mt-sm">
              <q-btn
                label="COMMENT"
                color="primary"
                text-color="white"
                @click="submitComment"
              />
            </div>
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      newPost: '',
      newComment: '',
      showComments: false,
      comments: [
        {
          author: 'Jane Doe',
          time: '1 hour ago',
          text: 'Great post! Thanks for sharing.',
        },
      ],
    }
  },
  methods: {
    submitPost() {
      // Logic to submit a new post
    },
    toggleComments() {
      this.showComments = !this.showComments;
    },
    submitComment() {
      if (this.newComment.trim() !== '') {
        this.comments.push({
          author: 'You',
          time: 'Just now',
          text: this.newComment,
        });
        this.newComment = '';
      }
    },
  },
}
</script>

