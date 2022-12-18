<template>
  <v-app>
    <v-navigation-drawer v-model="drawer" app clipped>
      <h4>{{this.userName}}</h4>
      <v-list dense>
        <h6>Channels</h6>
        <template v-for="(item,index) in myChannels">
        <v-list-item to="/" :key="index" @click="setMessagedUser(item, item.cid, 1)">
          <v-list-item-content>
            <v-list-item-title># {{item.channel_name}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </template>
        <h6>People</h6>
        <template v-for="(item,index) in messagedUsers">
        <v-list-item to="/" :key="index" @click="setMessagedUser(item, index, 0)">
          <v-list-item-content>
            <v-list-item-title>{{item}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </template>
        <h6>Tools</h6>
        <v-list-item to="/" @click="setMessagedUser()">
          <v-list-item-content>
            <v-list-item-title>Map</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-left>
          <v-app-bar-nav-icon @click.stop="toggleDrawer" />
          <v-toolbar-title>Simple Messaging</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu left bottom>
            <template v-slot:activator="{ on }">
            <v-btn icon v-on="on">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
            </template>
            <v-list>
            <v-list-item>
              <v-list-item-title>
                <a href="http://localhost/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item">Logout</a>
              </v-list-item-title>
            </v-list-item>
          </v-list>

          </v-menu>
    </v-app-bar>

    <v-content>
        <slot name="content"></slot>
    </v-content>

    <v-btn
        fab
        color="primary"
        bottom
        right
        fixed
        to="/messages/send"
        >
        <v-icon>mdi-plus</v-icon>
    </v-btn>

    <v-footer app dark>
      <v-card-text class="py-2 white--text text-center">
        <span>Simple Messaging</span>
      </v-card-text>
    </v-footer>
  </v-app>
</template>

<script>
  import store from '../store';
  import axios from 'axios';

  export default {
    store,
    data: () => ({
        drawer: null,
        messagedUsers: {},
        myChannels: {},
        userName: '',
        userId: 0,
        userLat: 0,
        userLng: 0,
    }),
    created () {
        this.$vuetify.theme.dark = this.$store.getters['settings/isDarkMode'];
    },
    mounted() {
        this.fetchMessagedUsers();
        this.fetchMyChannels();
        this.getUserInfo();
    },
    methods: {
        toggleDrawer() {
            this.drawer = !this.drawer;
        },
        refreshMessages: function(item, index, isChannel) {
         this.$root.$emit('refreshMessages',item, index, isChannel);
        },
        setMessagedUser(item, index, isChannel) {
          this.refreshMessages(item, index, isChannel);
        },
        async fetchMessagedUsers() {
            try {
                this.loading = true;
                const data = await axios.get('/api/messagedusers');
                this.messagedUsers = data.data;
            }
            catch (e) {
                console.log(e);
            }
            finally {
                this.loading = false;
            }
        },
        async fetchMyChannels() {
            try {
                this.loading = true;
                const data = await axios.get('/api/channels');
                this.myChannels = data.data;
            }
            catch (e) {
                console.log(e);
            }
            finally {
                this.loading = false;
            }
        },
        getUserInfo() {

            try {
              var vm = this;
                this.loading = true;
                if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                    console.log("Latitude: " + position.coords.latitude +
                    ", Longitude: " + position.coords.longitude);
                    vm.userLat = position.coords.latitude;
                    vm.userLng = position.coords.longitude;

                    let sendData = {lat: position.coords.latitude, lng: position.coords.longitude};
                    axios.post('/api/userinfo', sendData)
                    .then(function(response) {
                      vm.userName = response.data.username;
                    });
                  });
                }

            }
            catch (e) {
                console.log(e);
            }
            finally {
                this.loading = false;
            }
        }
    },

  }
</script>
