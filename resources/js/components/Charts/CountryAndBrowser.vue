<template>
    <div class="card h-100">
        <div class="p-3 pb-0 card-header">
            <div class="d-flex align-items-center">
                <h6 class="mb-0">{{ title }}</h6>
                <button type="button"
                    class="mb-0 btn btn-icon-only btn-rounded btn-outline-secondary ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto"
                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                    :title="`${description}`">
                    <i class="fas fa-info"></i>
                </button>
            </div>
            <span class="fs-6">{{ subtitle }}</span>
        </div>
        <div class="p-3 card-body">
            <div class="row">
                <div class="text-center col-lg-5 col-12">
                    <div class="mt-5 chart">
                        <canvas :id="id" class="chart-canvas" :height="height"></canvas>
                    </div>
                </div>
                <div class="col-lg-7 col-12">
                    <div class="table-responsive">
                        <table class="table mb-0 align-items-center">
                            <tbody>
                                <tr v-for="(value, name) in this.chart.data">
                                    <td>
                                        <div class="px-2 py-1 d-flex">
                                            <div>
                                                <img v-if="type == 'browsers'" crossorigin="anonymous" :src="`https://raw.githubusercontent.com/alrra/browser-logos/main/src/${name.toLowerCase()}/${name.toLowerCase()}_32x32.png`" class="avatar avatar-sm me-2" alt="logo" />
                                                <img v-else-if="type == 'countries'" crossorigin="anonymous" :src="`https://flagsapi.com/${name}/flat/64.png`" class="avatar avatar-sm me-2" alt="logo" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm text-center align-middle">
                                        <span class="text-xs font-weight-bold"> {{ value }} </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";
export default {
    name: "DefaultDoughnutChart",
    props: {
        id: {
            type: String,
            default: "default-doughnut-chart",
        },
        height: {
            type: String,
            default: "200",
        },
        title: {
            type: String,
            default: "Default Doughnut Chart",
        },
        subtitle: {
            type: String,
            default: "Default Doughnut Chart",
        },
        description: {
            type: String,
            default: "Default Doughnut Chart",
        },
        type: {
            type: String,
            default: "doughnut",
        },
        chart: {
            type: Object,
            required: true,
            data: Array,
        },
    },
    mounted() {
        var chart = document.getElementById(this.id).getContext("2d");

        let chartStatus = Chart.getChart(this.id);
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }
        let dataa = this.chart.data;
        let labels = [];
        let data = [];
        for (const property in dataa) {
            labels.push(property);
            data.push(dataa[property]);
        }

        new Chart(chart, {
            type: "doughnut",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: labels,
                        weight: 9,
                        cutout: 60,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        backgroundColor: [
                            "#2152ff",
                            "#3A416F",
                            "#f53939",
                            "#a8b8d8",
                            "#4BB543 ",
                        ],
                        data: data,
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },
            },
        });
    },
};
</script>
