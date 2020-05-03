<template>
	<v-app>
		<NodeTree :node="sortedIndex">
			<template v-slot:header="scope">
				<v-container>
					<v-row>
						<v-col align-self="center" :class="'col-3 pa-0 sensor-reading ' + sensor.state" v-for="(sensor, index) in scope.node" :key="'main-' + sensor + '-' + index">
							<div>
								<span>{{ sensor.displayName }} = {{ sensor.lastValue }}</span>
							</div>
						</v-col>
					</v-row>
				</v-container>
			</template>
		</NodeTree>

		<v-btn v-on:click="sortBy = sortByDefault; getMeta();">default</v-btn>
	</v-app>
</template>

<script>
	import {config} from "../Config";
	import {api} from "../Services/api";
	import NodeTree from "../components/dashOrder";
	import FuzzySearch from "fuse.js"

	export default {
		name: "dash",
		components: {
			NodeTree
		},
		data: () => ({
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
				var self = this,
					indexedObjs = {};
				dataObj.forEach((objVal, objIndex) => {
					self.treeMe(indexedObjs, self.sortBy, objVal);
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
				var self = this;

				const readMeta = config.apiBaseUrl + "jacob/Sensor/Read";

				api({method: "GET", url: readMeta}).then(function(response) {
					response.forEach((sensorVal, sensorIndex) => {
						let currentTimestamp = Math.round(new Date().getTime()/1000) - config.inactivity;
						response[sensorIndex]["state"] = sensorVal["lastSeen"] >= currentTimestamp ? "active" : "inactive";
					});
					self.sortedIndex = self.indexBy(response, self.sortBy);
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