import { createStore } from "vuex";
import meta from "./modules/meta.js";
import app from "./modules/app.js";
import account from "./modules/account.js";
import alert from "./modules/alert.js";
import dashboard from "./modules/dashboard.js";
import adminSetting from "./modules/admin/setting.js";
import adminLesson from "./modules/admin/lesson.js";
import adminCourse from "./modules/admin/course.js";
import adminUser from "./modules/admin/user.js";
import adminRole from "./modules/admin/role.js";
import adminSchool from "./modules/admin/school.js";
import home from "./modules/home.js";
import learn from "./modules/learn";
import lesson from "./modules/lesson.js";
import course from "./modules/course.js";
// import classes from "./modules/class.js";
export default createStore({
    namespaced: true,
    modules: {
        meta,
        app,
        account,
        alert,
        home,
        learn,
        // classes,
        dashboard,
        adminSetting,
        adminLesson,
        adminCourse,
        adminUser,
        adminRole,
        adminSchool,
        lesson,
        course,
    },
});
