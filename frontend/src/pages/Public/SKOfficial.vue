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
              <img
                v-if="official.image"
                :src="official.image"
                alt="Official Image"
                class="official-image"
              />
              <q-icon v-else name="person" size="100px" class="text-primary" />
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
                <img
                  v-if="official.image"
                  :src="official.image"
                  alt="Official Image"
                  class="official-image"
                />
                <q-icon v-else name="person" size="100px" class="text-primary" />
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
        { id: 1, name: 'Juan Dela Cruz', position: 'SK Chairman', image: null },
        { id: 2, name: 'Maria Santos', position: 'Kagawad', image: null },
        { id: 3, name: 'Pedro Reyes', position: 'Kagawad', image: null },
        { id: 4, name: 'Josefa Garcia', position: 'Kagawad', image: null }
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
  justify-content: center;
}

.official-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin-bottom: 10px;
}

.toolbar-title {
  flex-grow: 1;
  text-align: center;
}

.q-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>

