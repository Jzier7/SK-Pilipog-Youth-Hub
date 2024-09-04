<template>
  <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'BarChart',
  data() {
    return {
      series: [{
            name: 'Number of People',
            data: [150, 200, 100, 250, 300, 350, 400, 500, 600, 700, 800, 900]
          }],
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
          categories: ["Location A", "Location B", "Location C", "Location D", "Location E", "Location F", "Location G", "Location H", "Location I", "Location J", "Location K", "Location L"],
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
        },
        title: {
          text: 'Number of People per Location in the Barangay',
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
})
</script>

