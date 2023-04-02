<template>
    <div class="w-100 bg-white container-fluid d-flex justify-content-between align-items-center py-2">
        <a href="#" class="btn m-0 rounded-circle d-flex justify-content-center align-items-center" style="width: 2.5rem; height: 2.5rem;" onclick="window.history.back()">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <div class="fs-5 fw-bold">
            {{ title }}
        </div>
        <button class="btn m-0" @click="$emit('toggle-options'); toggleSettings()">
            Options
        </button>
    </div>
    <div class="progress">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            :style="{ width: progress + '%' }"
            :aria-valuenow="progress"
            aria-valuemin="0"
            aria-valuemax="100"
        >
        </div>
    </div>
    <div v-if="showSettings" class="settings-overlay">
        <div class="settings-menu">
            <!-- add settings button -->
            <button class="settings-close-button" @click="toggleSettings">×</button>
            <h2>Tùy chọn</h2>
            <h6>Sắp xếp thẻ</h6>
            <p>Sắp xếp thẻ của bạn để tập trung vào những thuật ngữ cần chú tâm học. Tắt tính năng sắp xếp nếu
                bạn muốn nhanh chóng ôn lại các thẻ ghi nhớ.</p>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault">Âm thanh</label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LearnNavbar',
    props: {
        title: {
            type: String,
            required: true,
        },
        showOptions: {
            type: Boolean,
            required: true,
        },
        progress: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            showSettings: false,
        };
    },
    methods: {
        toggleSettings() {
            this.showSettings = !this.showSettings;
        },
    },
};
</script>

<style scoped>
.settings-button {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 999; /* make sure it appears above other elements */
    left: auto; /* remove left property */
}

/* add styles for settings menu */
.settings-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 998; /* make sure it appears below the settings button */
}

.settings-menu {
    position: relative;
    width: 800px;
    max-width: 90%;
    background-color: #f5f5f5;
    border-radius: 4px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    padding: 20px;
    animation-name: slide-in-down;
    animation-duration: 0.3s;
    animation-timing-function: ease-out;
}

.settings-close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px;
    font-size: 24px;
    line-height: 1;
    color: #666;
    border: none;
    background-color: transparent;
    cursor: pointer;
}

.settings-close-button:hover {
    color: #000;
}
</style>


