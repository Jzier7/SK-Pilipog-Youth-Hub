<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">ANNOUNCEMENT</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <q-card flat bordered class="q-pa-md text-white">

      <q-form>
        <!-- Title -->
        <q-input filled v-model="title" label="Title" class="q-mb-md" color='primary'></q-input>

        <!-- Frame (Dropdown) -->
        <q-select filled v-model="selectedCategory" :options="categories" label="categories" class="q-mb-md" color='primary'></q-select>

        <!-- Announcement Textarea -->
        <q-input
          filled
          v-model="announcement"
          label="Announcement"
          type="textarea"
          rows="6"
          class="q-mb-md"
        ></q-input>

        <!-- Action Buttons -->
        <div class="row justify-end">
          <q-btn label="Publish" color="primary" @click="publish"></q-btn>
        </div>
      </q-form>
    </q-card>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      title: '',
      selectedCategory: '',
      categories: [
        'Category 1',
        'Category 2',
        'Category 3',
      ],
      announcement: '',
    };
  },
  methods: {
    cancel() {
    },
    publish() {
    }
  }
};
</script>

