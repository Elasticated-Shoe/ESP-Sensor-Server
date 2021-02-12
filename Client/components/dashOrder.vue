<template>
    <v-card>
        <v-container class="sensor-container">
            <v-row v-if="label" no-gutters>
                <v-col class="col-2 sensor-label-text">
                    {{ label }}
                </v-col>
                <v-col class="col-10 sensor-node" align-self="center"></v-col>
            </v-row>
            <v-row v-if="!label" no-gutters>
                <v-col class="col-8">
                    
                </v-col>
                <v-col class="col-4 text-right">
                    Last Fetched: {{ refreshString }}
                </v-col>
            </v-row>

            <v-row v-if="!Array.isArray(node)" class="sensor-label" no-gutters>
                <v-col class="col-12">
                    <node v-for="(objectValue, objectKey) in node" :key="objectKey" :node="objectValue" :label="objectKey">

                        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope">
                            <slot :name="slot" v-bind="scope"/>
                        </template>

                    </node>
                </v-col>
            </v-row>
            <v-row v-if="Array.isArray(node)" class="sensor-row" no-gutters>
                <slot name="header" v-bind:node="node">
                    
                </slot>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
export default {
    name: "node",
    data: () => ({
        refreshString: "Never"
    }),
    props: {
        node: null,
        refreshDateTime: null,
        label: null
    },
    created() {
        if(this.label || this.refreshDateTime === "Never") {
            return;
        }

        setInterval(() => {
            var timespanSeconds = Math.round( ( new Date().getTime() - this.refreshDateTime.getTime() ) / 1000 );

            var days = Math.floor(timespanSeconds / (3600*24));
            var hours = Math.floor(timespanSeconds % (3600*24) / 3600);
            var minutes = Math.floor(timespanSeconds % 3600 / 60);
            var seconds = Math.floor(timespanSeconds % 60);

            var dDisplay = days > 0 ? days + (days == 1 ? " day, " : " days, ") : "";
            var hDisplay = hours > 0 ? hours + (hours == 1 ? " hour, " : " hours, ") : "";
            var mDisplay = minutes > 0 ? minutes + (minutes == 1 ? " minute, " : " minutes, ") : "";
            var sDisplay = seconds > 0 ? seconds + (seconds == 1 ? " second" : " seconds") : "Now";

            var tempString = dDisplay + hDisplay + mDisplay + sDisplay;
            if(tempString !== "Now") {
                tempString += " ago"
            }

            this.refreshString = tempString;
        }, 1000);
    }
};
</script>