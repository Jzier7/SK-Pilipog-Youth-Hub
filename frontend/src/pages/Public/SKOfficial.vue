<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title class="toolbar-title">
          <h3 class="text-primary">SK OFFICIALS</h3>
          <span v-if="officials.length > 0" class="text-[13px]">{{ formattedDateRange }}</span>
          <span v-else class="text-[13px] text-secondary">No Active Officials</span>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none flex flex-col items-center">
      <div class="q-mb-md flex items-center"></div>

      <div class="flex flex-col items-center">
        <div v-for="(levelGroup, level) in groupedOfficials" :key="level" class="flex flex-wrap justify-center">
          <q-card
            v-for="official in levelGroup"
            :key="official.id"
            class="q-mb-md"
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
              <p class="position">{{ official.position.name }}</p>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import dateMixin from 'src/utils/mixins/dateMixin';
import officialService from 'src/services/officialService';

export default {
  mixins: [dateMixin],
  data() {
    return {
      officials: [],
      startDate: '',
      endDate: ''
    }
  },
  computed: {
    formattedDateRange() {
      return `${this.formatDate(this.startDate, 'D MMMM YYYY')} - ${this.formatDate(this.endDate, 'D MMMM YYYY')}`;
    },
    groupedOfficials() {
      const groups = {};

      this.officials.forEach(official => {
        const level = official.position.level;

        if (!groups[level]) {
          groups[level] = [];
        }

        groups[level].push(official);
      });

      return Object.keys(groups).sort((a, b) => b - a).reduce((acc, level) => {
        acc[level] = groups[level];
        return acc;
      }, {});
    }
  },
  mounted() {
    this.fetchOfficials();
  },
  methods: {
    async fetchOfficials() {
      try {
        const response = await officialService.getActiveOfficials();
        this.officials = response.data.body.officials || [];

        if (response.data.body.term) {
          this.startDate = response.data.body.term.start_date;
          this.endDate = response.data.body.term.end_date;
        }
      } catch (error) {
        console.error('Error fetching officials:', error);
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.q-card {
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

.position {
  color: #7d7d7d;
}
</style>

