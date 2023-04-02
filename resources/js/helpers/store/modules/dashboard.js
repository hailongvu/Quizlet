// import router from "../../router";
import {dashboardService} from "../../services";

const state = {
    quotes: null,
    analytics: {
        basic: null,
        duration: null,
        browser: null,
        country: null,
        week: null
    }
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        quotesRequest(state) {
            state.quotes = null;
        },
        setQuotes(state, quotes) {
            state.quotes = quotes;
        },
        quotesFailure(state) {
            state.quotes = null;
        },
        analyticsRequest(state) {
            state.analytics.local = null;
            state.analytics.duration = null;
            state.analytics.sessions = null;
            state.analytics.week = null;
            state.analytics.weekByEvent = null;
        },
        setAnalytics(state, analytics) {
            switch (analytics[0]) {
                case 'local':
                    state.analytics.local = analytics[1];
                    break;
                case 'duration':
                    // get list of duration
                    state.analytics.duration = {
                        duration: analytics[1].map(item => parseFloat(item.averageSessionDuration).toFixed(2)),
                        date: analytics[1].map(item => `${item.year}-${item.month}-${item.day}`)
                    };
                    break;
                case 'sessions':
                    state.analytics.browser = analytics[1].browser;
                    state.analytics.country = analytics[1].country;
                    break;
                case 'week':
                    state.analytics.week = analytics[1];
                    break;
                case 'weekByEvent':
                    state.analytics.weekByEvent = analytics[1];
                    break;
            }
        },
        analyticsFailure(state) {
            state.analytics.local = null;
            state.analytics.duration = null;
            state.analytics.sessions = null;
            state.analytics.week = null;
            state.analytics.weekByEvent = null;
        }
    },
    actions: {
        getQuotes({commit}) {
            commit('quotesRequest');
            dashboardService.quotes()
                .then(
                    quotes => {
                        commit('setQuotes', quotes.data);
                    },
                );
        },
        async getAnalytics({commit}) {
            try {
                commit('analyticsRequest');

                const [duration, sessions, week, weekByEvent, local] = await Promise.allSettled([
                    dashboardService.analytics('duration'),
                    dashboardService.analytics('sessions'),
                    dashboardService.analytics('week'),
                    dashboardService.analytics('weekByEvent'),
                    dashboardService.analytics('local'),
                ]);
                commit('setAnalytics', ['duration', duration.value]);
                commit('setAnalytics', ['sessions', sessions.value]);
                commit('setAnalytics', ['local', local.value]);
                // list of week day
                const weekday = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                // get last week data
                let last_week = weekday.reduce((acc, day, index) => {
                    const item = week.value.lastWeek.find(item => item.dayOfWeek === `${index}`);
                    acc.push(item ? item.screenPageViews : 0);
                    return acc;
                }, []);
                // get this week data
                let this_week = weekday.reduce((acc, day, index) => {
                    const item = week.value.thisWeek.find(item => item.dayOfWeek === `${index}`);
                    acc.push(item ? item.screenPageViews : 0);
                    return acc;
                }, []);
                // commit week data
                commit('setAnalytics', ['week', {lastweek: last_week, thisweek: this_week}]);
                // get last week data by event
                let last_week_by_event = weekday.reduce((acc, day, index) => {
                    const item = weekByEvent.value.lastWeek.find(item => item.dayOfWeek === `${index}`);
                    acc.push(item ? item.eventCount : 0);
                    return acc;
                }, []);
                // get this week data by event
                let this_week_by_event = weekday.reduce((acc, day, index) => {
                    const item = weekByEvent.value.thisWeek.find(item => item.dayOfWeek === `${index}`);
                    acc.push(item ? item.eventCount : 0);
                    return acc;
                }, []);
                // commit week data by event
                commit('setAnalytics', ['weekByEvent', {lastweek: last_week_by_event, thisweek: this_week_by_event}]);
            } catch (error) {
                console.error(error);
            }
        }
    },
    getters: {
        quotes(state) {
            return state.quotes;
        },
        analytics(state) {
            return state.analytics;
        }
    }
};
