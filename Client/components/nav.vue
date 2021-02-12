<template>
    <v-container fluid class="ma-0 nav-bar">
        <v-row align="center" no-gutters class="nav-main-bar">
            <v-col class="col-1">
                <v-app-bar-nav-icon @click.stop="navVisible = !navVisible"></v-app-bar-nav-icon>
            </v-col>
            <v-col class="col-1">
                <v-btn text>Home</v-btn>
            </v-col>
            <v-col class="col-1">
                <v-btn text>Manage</v-btn>
            </v-col>
            <v-col class="col-1">
                <v-btn text>Login</v-btn>
            </v-col>
            <v-col align="right">
                <v-toolbar class="rounded" dense floating>
                    <v-text-field hide-details single-line></v-text-field>

                    <v-btn icon>
                        <v-icon color="black">mdi-magnify</v-icon>
                    </v-btn>
                </v-toolbar>
            </v-col>
        </v-row>

        <v-navigation-drawer absolute v-model="navVisible">
            <v-list>
                <v-list-item>
                    <v-row no-gutters>
                        <v-col>
                            <v-app-bar-nav-icon @click.stop="navVisible = !navVisible"></v-app-bar-nav-icon>
                        </v-col>
                        <v-col class="text-right">
                            <v-list-item-avatar>
                                <v-img src="../assets/avatar.png"></v-img>
                            </v-list-item-avatar>
                        </v-col>
                    </v-row>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title class="title">
                            Sensors
                        </v-list-item-title>
                        <v-list-item-subtitle>{{ title }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list nav dense>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-folder</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>My Files</v-list-item-title>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-account-multiple</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Shared with me</v-list-item-title>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-star</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Starred</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

    </v-container>
</template>

<script>
import {config} from "../Config";

export default {
    name: "nav-comp",
    data: () => ({
        navVisible: false,
        title: config.title
    }),
    created() {
        
    }
};
</script>