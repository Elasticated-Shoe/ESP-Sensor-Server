<template>
    <v-container>
        <v-row v-if="label">
            <v-col class="col-2">
                {{ label }}
            </v-col>
            <v-col class="col-10 sensor-node" align-self="center"></v-col>
        </v-row>
        <v-row v-if="!label">
            <v-col class="col-8"></v-col>
            <v-col class="col-4">Last Fetched: {{ new Date().toLocaleString() }}</v-col>
        </v-row>

        <v-row v-if="!Array.isArray(node)">
            <v-col class="col-12">
                <node v-for="(objectValue, objectKey) in node" :key="objectKey" :node="objectValue" :label="objectKey">

                    <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope">
                        <slot :name="slot" v-bind="scope"/>
                    </template>

                </node>
            </v-col>
        </v-row>
        <v-row v-if="Array.isArray(node)">
            <slot name="header" v-bind:node="node">
                
            </slot>
        </v-row>
    </v-container>
</template>

<script>
export default {
  name: "node",
  props: {
    node: null,
    label: null
  }
};
</script>