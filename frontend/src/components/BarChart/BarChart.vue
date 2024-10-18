<template>
  <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
</template>

<script>
import { defineComponent, watch, nextTick } from 'vue';

export default defineComponent({
  name: 'BarChart',
  props: {
    data: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      series: [],
      chartOptions: {
        chart: {
          height: 350,
          type: 'bar',
        },
        colors: ['#053c54'],
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 10,
            dataLabels: {
              position: 'right',
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val;
          },
          offsetX: 0,
          style: {
            fontSize: '12px',
            colors: ["#3a7e8a"]
          }
        },
        xaxis: {
          categories: [],
          position: 'bottom',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          labels: {
            show: true,
          },
          max: undefined,
        },
        title: {
          text: 'Number of People per Purok in the Barangay',
          floating: true,
          offsetY: 10,
          align: 'center',
          style: {
            color: '#053c54'
          }
        }
      },
    }
  },
  mounted() {
    nextTick(() => {
      this.updateChartData(this.data);
    });
  },
  watch: {
    data: {
      deep: true,
      immediate: true,
      handler(newData) {
        if (newData && newData.length > 0) {
          this.updateChartData(newData);
        }
      }
    }
  },
  methods: {
    updateChartData(newData) {
      this.series = [{
        name: 'Number of People',
        data: newData.map(item => item.count)
      }];

      this.chartOptions.xaxis.categories = newData.map(item => item.name);

      const maxCount = Math.max(...newData.map(item => item.count));
      const range = maxCount - Math.min(...newData.map(item => item.count));
      this.chartOptions.yaxis.max = range > 5 ? maxCount + Math.ceil(range * 0.1) : maxCount;
    }
  }
});
</script>

