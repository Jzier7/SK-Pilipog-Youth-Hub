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
            @click="submitPost"
            class="q-ma-none text-primary"
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
            Tuod, natigmantan gayud sa mga Pilipinon ang imong pagmhal ug
            dedikadi nga serbisyo publiko. Kami mapasalamatan sa imong dedikasyon
            ug kontribusyon sa atong barangay.
          </p>
        </div>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat icon="chat_bubble_outline" class="q-ml-sm">
          <q-badge color="primary" floating>1</q-badge>
        </q-btn>
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      newPost: '',
    }
  },
  methods: {
    submitPost() {
    }
  }
}
</script>
