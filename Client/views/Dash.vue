<template>
	<v-app>
		<NodeTree :node="sortedIndex" :refreshDateTime="lastFetched">
			<template v-slot:header="scope">
				<v-col :class="'col-1 pa-0 sensor-reading ' + sensor.state" v-for="(sensor, index) in scope.node" :key="'main-' + sensor + '-' + index">
					{{ sensor.displayName }} = {{ sensor.lastValue }}
				</v-col>
			</template>
		</NodeTree>

		<v-btn v-on:click="sortBy = sortByDefault; getMeta();">default</v-btn>
	</v-app>
</template>

<script>
	import {config, demo} from "../Config";
	import {api} from "../Services/api";
	import NodeTree from "../components/dashOrder";
	import FuzzySearch from "fuse.js"

	export default {
		name: "dash",
		components: {
			NodeTree
		},
		data: () => ({
			lastFetched: null,
			sortByDefault: ["sensorLocation", "sensorType"],
			sortBy: ["sensorLocation", "sensorType", "displayName"],
			sortedIndex: {}
		}),
		methods: {
			treeMe(currentLevel, remainingLevels, levelValue) {
				var level = remainingLevels[0],
					keyValue = levelValue[ level ] === null ? level + " Undefined" : levelValue[ level ];
				if(remainingLevels.length === 1) {
					if(keyValue in currentLevel) {
						currentLevel[keyValue].push(levelValue);
					}
					else {
						currentLevel[keyValue] = [levelValue];
					}
					return;
				}
				if(!(keyValue in currentLevel)) {
					currentLevel[keyValue] = {};
				}
				this.treeMe(currentLevel[keyValue], remainingLevels.slice(1), levelValue);
			},
			indexBy(dataObj, keys) {
				var indexedObjs = {};
				dataObj.forEach((objVal, objIndex) => {
					this.treeMe(indexedObjs, this.sortBy, objVal);
				});
				//v
				return indexedObjs;
			},
			convertToTimestamp(timeStr) {
				timeStr = timeStr.replace(" ", "T");
				
				var timeStamp = new Date(timeStr).getTime() / 1000;
				if(timeStamp === NaN) {
					return false;
				}
				return timeStamp;
			},
			getMeta() {
				const readMeta = demo.Enabled 	? config.apiBaseUrl + "/public/sensors/metadata/user/" + demo.UserId 
													: config.apiBaseUrl + "/sensors/metadata/user/1";

				api({method: "GET", url: readMeta}).then((response) => {
					response.forEach((sensorVal, sensorIndex) => {
						let currentTimestamp = Math.round(new Date().getTime()/1000) - config.inactivity,
							sensorTimestamp = Math.round(new Date(sensorVal["lastSeen"].replace(" ", "T")).getTime()/1000);
						response[sensorIndex]["state"] = sensorTimestamp >= currentTimestamp ? "active" : "inactive";

						this.lastFetched = new Date();
					});

					this.sortedIndex = this.indexBy(response, this.sortBy);
				});
			},
		},
		mounted(){
			document.title = "Dashboard";

			this.getMeta();

			setInterval(this.getMeta, config.refresh * 1000); // 60 * 1000 milsec
		}
	}
</script>