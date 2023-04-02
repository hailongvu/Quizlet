<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div v-if="this.analytics.local" class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Users"
                            :value="analytics.local.users.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${analytics.local.users.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-users',
                                background: 'bg-gradient-primary',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Lessons"
                            :value="analytics.local.lessons.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${analytics.local.lessons.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-books',
                                background: 'bg-gradient-success',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Courses"
                            :value="analytics.local.courses.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${analytics.local.courses.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-folder-open',
                                background: 'bg-gradient-warning',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Classes"
                            :value="analytics.local.classes.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${analytics.local.classes.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-screen-users',
                                background: 'bg-gradient-danger',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                </div>
                <div class="row">
                    <div v-if="this.analytics.duration" class="col-lg-7 mb-lg">
                        <gradient-line-chart id="chart-line"
                                             title="Average Duration" description="<i class='fa-solid fa-timer text-success'></i>
        <span class='font-weight-bold'>average session duration</span> of last 30 days" :chart="{
            labels: this.analytics.duration.date,
            datasets: [
                {
                    label: 'Duration (s)',
                    data: this.analytics.duration.duration,
                }
            ]
        }"/>
                    </div>
                    <div v-if="quotes" class="col-lg-5">
                        <carousel :items="[
                            {
                                img: 'https://source.unsplash.com/1600x900/?nature',
                                title: `“${quotes[0].quoteAuthor}”`,
                                description: `“${quotes[0].quoteText}”`,
                                icon: {
                                    component: 'ni ni-camera-compact text-dark',
                                    background: 'bg-white'
                                }
                            },
                            {
                                img: 'https://source.unsplash.com/1600x900/?travel',
                                title: `“${quotes[1].quoteAuthor}”`,
                                description: `“${quotes[1].quoteText}”`,
                                icon: {
                                    component: 'ni ni-bulb-61 text-dark',
                                    background: 'bg-white'
                                }
                            },
                            {
                                img: 'https://source.unsplash.com/1600x900/?street-photography',
                                title: `“${quotes[2].quoteAuthor}”`,
                                description: `“${quotes[2].quoteText}”`,
                                icon: {
                                    component: 'ni ni-trophy text-dark',
                                    background: 'bg-white'
                                }
                            }
                        ]"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 col-md-12">
                        <StatsChart
                            v-if="this.analytics.week"
                            :title="'This week vs Last week'"
                            :subtitle="'By Page views'"
                            :badge="['This week', 'Last week']"
                            :id="'page-views-week'"
                            :labels="this.weekday"
                            :datasets="[
                                {
                                    label: 'This week',
                                    pointBackgroundColor: 'white',
                                    borderColor: '#2dce89',
                                    data: this.analytics.week.lastweek,
                                },
                                {
                                    label: 'Last week',
                                    pointBackgroundColor: 'white',
                                    borderColor: '#f5365c',
                                    data: this.analytics.week.thisweek,
                                }
                            ]"
                        />
                    </div>
                    <div class="mt-4 col-lg-5 col-md-12 mt-lg-0">
                        <doughnut-chart
                            v-if="this.analytics.browser"
                            id="top-browsers"
                            title="Top Browsers"
                            subtitle="by sessions"
                            description="This is top browsers by sessions"
                            type="browsers"
                            :chart="{ data: this.analytics.browser }"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 col-md-12">
                        <StatsChart
                            v-if="this.analytics.weekByEvent"
                            :title="'This week vs Last week'"
                            :subtitle="'By Events'"
                            :badge="['This week', 'Last week']"
                            :id="'page-views-week-by-event'"
                            :labels="this.weekday"
                            :datasets="[
                                {
                                    label: 'This week',
                                    pointBackgroundColor: 'white',
                                    borderColor: '#2dce89',
                                    data: this.analytics.weekByEvent.lastweek,
                                },
                                {
                                    label: 'Last week',
                                    pointBackgroundColor: 'white',
                                    borderColor: '#f5365c',
                                    data: this.analytics.weekByEvent.thisweek,
                                }
                            ]"
                        />
                    </div>
                    <div class="mt-4 col-lg-5 col-md-12 mt-lg-0">
                        <doughnut-chart
                            v-if="this.analytics.country"
                            id="top-countries"
                            title="Top Countries"
                            subtitle="by sessions"
                            description="This is top countries by sessions"
                            type="countries"
                            :chart="{ data: this.analytics.country }"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MiniStatisticsCard from "@/components/Cards/MiniStatisticsCard.vue";
import GradientLineChart from "@/components/Charts/GradientLineChart.vue";
import Carousel from "@/components/Carousel.vue";
import StatsChart from "@/components/Charts/StatsChart.vue";
import DoughnutChart from "@/components/Charts/CountryAndBrowser.vue";
import {mapActions, mapState} from "vuex";
import overlay from "@/helpers/overlay";

export default {
    name: "AdminDashboard",
    components: {
        MiniStatisticsCard,
        GradientLineChart,
        Carousel,
        StatsChart,
        DoughnutChart,
    },
    data() {
        return {
            weekday: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        };
    },
    title() {
        return 'Admin Dashboard' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    beforeCreate() {
        overlay();
        this.$store.dispatch('dashboard/getQuotes');
        this.$store.dispatch('dashboard/getAnalytics').then(() => {
            overlay();
        });
    },
    methods: {},
    computed: {
        ...mapState({
            quotes: state => state.dashboard.quotes,
            analytics: state => state.dashboard.analytics,
        }),
    },
};
</script>
