import Vue from 'vue'
import App from './App.vue'
import router from "./router";
import vuetify from './assets/js/vuetify';
import Vuetify from 'vuetify'

Vue.config.productionTip = false

Vue.use(router)
Vue.use(Vuetify, {})

new Vue({
	vuetify,
	router,
	render: h => h(App),
	mounted() {
		this.$vuetify.theme.dark = false;
	}
}).$mount('#app')