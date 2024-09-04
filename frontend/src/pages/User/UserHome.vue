<template>
  <q-page padding>

    <q-card class="q-my-lg bg-primary" flat bordered>
      <q-card-section>
        <q-toolbar class="q-pa-none">
          <q-toolbar-title>
            <h2 class="text-white">Hi! John Wilche</h2>
          </q-toolbar-title>
        </q-toolbar>
      </q-card-section>
    </q-card>

    <q-card flat bordered>
      <q-card-section>
        <q-table :rows="rows" :columns="columns" flat>
          <template v-slot:top>
            <q-toolbar class="q-pa-none">
              <q-toolbar-title class="text-bold text-primary">Announcement</q-toolbar-title>
              <q-space />
              <q-select
                outlined
                dense
                options-dense
                options="categories"
                label="Categories"
              />
            </q-toolbar>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      columns: [{
        name: 'announcement',
        align: 'left',
        label: 'Announcement',
        field: 'announcement'
        }],
      rows: [
      ],
      categories: [
      ]
    }
  }
}
</script>
