<template>
	<v-app>
		<NavBar></NavBar>
		<router-view>
				
		</router-view>
	</v-app>
</template>

<script>

import "./assets/css/app.scss";
import NavBar from "./components/nav";

export default {

	name: 'App',
	components: {
		NavBar
	},
	methods: {
		
	},
	mount(){
		
	}
};
</script>
