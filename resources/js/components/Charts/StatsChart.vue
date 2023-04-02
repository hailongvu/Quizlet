<template>
    <div class="card">
        <div class="p-3 pb-0 card-header">
            <h6 class="mb-0">{{ title }}</h6>
            <span class="fs-6">{{ subtitle }}</span>
            <div class="d-flex align-items-center">
                                    <span class="badge badge-md badge-dot me-4">
                                        <i class="bg-success"></i>
                                        <span class="text-xs text-dark">{{badge[0]}}</span>
                                    </span>
                <span class="badge badge-md badge-dot me-4">
                                        <i class="bg-danger"></i>
                                        <span class="text-xs text-dark">{{badge[1]}}</span>
                                    </span>
            </div>
        </div>
        <div class="p-3 card-body">
            <div class="chart">
                <canvas :id="id" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";
export default {
    name: "TrafficChart",
    props: {
        title:{
            type: String,
            default: "Title of static chart",
        },
        subtitle:{
            type: String,
            default: "subtitle of static chart",
        },
        badge:{
            type: Array,
            default: ["badge", "badge"],
        },
        id: {
            type: String,
            default: "traffic-chart",
        },
        labels: {
            type: Array,
            default: () => ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"],
        },
        datasets: {
            type: Array,
            default: [
                {
                    label: 'no',
                    data: [0, 0, 0, 0, 0, 0, 0],
                },
                {
                    label: 'yes',
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 2,
                    pointBackgroundColor: "#f5365c",
                    borderColor: "#f5365c",
                    // eslint-disable-next-line no-dupe-keys
                    data: [0, 0, 0, 0, 0, 0, 0],
                    maxBarThickness: 6,
                },
            ],
        }
    },
    mounted() {
        const chart = document.getElementById(this.id).getContext("2d");
        const gradientStroke1 = chart.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, "rgba(203,12,159,0.2)");
        gradientStroke1.addColorStop(0.2, "rgba(72,72,176,0.0)");
        gradientStroke1.addColorStop(0, "rgba(203,12,159,0)"); //purple colors
        const gradientStroke2 = chart.createLinearGradient(0, 230, 0, 50);
        gradientStroke2.addColorStop(1, "rgba(20,23,39,0.2)");
        gradientStroke2.addColorStop(0.2, "rgba(72,72,176,0.0)");
        gradientStroke2.addColorStop(0, "rgba(20,23,39,0)"); //purple colors
        // get datasets
        let chart_data = this.datasets.map((dataset) => {
            return {
                label: dataset.label || "no",
                tension: dataset.tension || 0.4,
                // borderWidth: dataset.borderWidth || 0,
                pointRadius: dataset.pointRadius || 2,
                pointBackgroundColor: dataset.pointBackgroundColor || "#f5365c",
                borderColor: dataset.borderColor || "#f5365c",
                borderWidth: dataset.borderWidth || 3,
                data: dataset.data || [0, 0, 0, 0, 0, 0, 0],
                maxBarThickness: dataset.maxBarThickness || 6,
            };
        });
        // Line chart
        new Chart(chart, {
            type: "line",
            data: {
                labels: this.labels,
                datasets: chart_data,
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
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#9ca2b7",
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#9ca2b7",
                            padding: 10,
                        },
                    },
                },
            },
        });
    },
};
</script>

<style scoped>
    .chart-canvas {
        height: 300px !important;
    }
</style>
