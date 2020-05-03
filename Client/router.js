import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
	mode: "history",
	base: process.env.BASE_URL,
	routes: [
		{
			path: "",
			redirect:"/Dash",
		},
		{
			path: "/Dash",
			name: "dash",
			props: true,
			component: () => import("./views/Dash.vue")
		},
	]
});