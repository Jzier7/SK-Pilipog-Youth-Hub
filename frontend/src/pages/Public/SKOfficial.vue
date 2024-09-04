<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title class="toolbar-title">
          <h3 class="text-primary">SK OFFICIALS</h3>
          <span class="text-[13px]">{{ formattedDateRange }}</span>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none flex flex-col items-center">
      <div class="q-mb-md flex items-center"></div>

      <div class="flex flex-col items-center">
        <q-card
          v-for="official in skChairman"
          :key="official.id"
          class="q-pa-md q-mb-md q-ma-sm"
          flat
          bordered
          style="width: 200px; text-align: center;"
        >
          <q-card-section>
            <div class="image-container">
              <img :src="official.image" alt="Official Image" class="official-image" />
            </div>
            <h3 class="text-primary">{{ official.name }}</h3>
            <div class="text-subtitle1">{{ official.position }}</div>
          </q-card-section>
        </q-card>

        <div class="flex flex-wrap justify-center">
          <q-card
            v-for="official in otherOfficials"
            :key="official.id"
            class="q-pa-md q-mb-md q-ma-sm"
            flat
            bordered
            style="width: 200px; margin: 10px; text-align: center;"
          >
            <q-card-section>
              <div class="image-container">
                <img :src="official.image" alt="Official Image" class="official-image" />
              </div>
              <h3 class="text-primary">{{ official.name }}</h3>
              <div class="text-subtitle1">{{ official.position }}</div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      barangayName: 'Barangay Example',
      officials: [
        { id: 1, name: 'Juan Dela Cruz', position: 'SK Chairman' },
        { id: 2, name: 'Maria Santos', position: 'Kagawad' },
        { id: 3, name: 'Pedro Reyes', position: 'Kagawad' },
        { id: 4, name: 'Josefa Garcia', position: 'Kagawad' }
      ],
      startDate: 'January 1, 2024',
      endDate: 'December 31, 2024'
    }
  },
  computed: {
    formattedDateRange() {
      return `${this.startDate} - ${this.endDate}`;
    },
    skChairman() {
      return this.officials.filter(official => official.position === 'SK Chairman');
    },
    otherOfficials() {
      return this.officials.filter(official => official.position !== 'SK Chairman');
    }
  },
  methods: {
    addOfficial() {
      console.log('Add Official button clicked');
    }
  }
}
</script>

<style lang="scss" scoped>
.q-card {
  background-color: #f9f9f9;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.text-primary {
  color: $primary;
}

.image-container {
  display: flex;
  justify-content: center; /* Center the image horizontally */
}

.official-image {
  width: 100px;
  height: 100px;
  border-radius: 50%; /* Make the image circular */
  margin-bottom: 10px; /* Spacing below the image */
}

.toolbar-title {
  flex-grow: 1; /* Make it take up all available space */
  text-align: center; /* Center text */
}

.q-page {
  display: flex;
  flex-direction: column;
  align-items: center; /* Center horizontally */
  justify-content: center; /* Center vertically */
}
</style>
