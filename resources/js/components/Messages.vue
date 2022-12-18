<template>
  <application>
    <template v-slot:content>
      <v-container class="pa-4" fluid>
        <v-card v-show="recipient">
          <v-card-title>
            {{ recipientName }}
            <div class="flex-grow-1" />
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
              @input="handleSearch"
            />
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="messages"
            :loading="loading"
            hide-default-footer
            disable-sort
        >
            <template v-slot:body="{ items }">
              <tbody>
                <tr v-for="item in filteredItems" :key="item.id">
                  <td>
                    <strong>{{ item.user_from_name }}: </strong>
                    {{ item.message }}
                  </td>
                  <td>{{ new Date(item.created_at).toLocaleString() }}</td>
                  <td>
                    <v-btn icon @click="handleDelete(item, item.id)">
                      <v-icon>mdi-delete</v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-data-table>
        </v-card>
        <v-card v-show="!recipient">
          <v-card-text>
            <v-row align="center">
              <v-col cols="8">
                <div class="headline mb-2">Group Map</div>
                Last known locations of all users.
              </v-col>
              <v-col cols="4">
              <v-btn color="primary" width="100%" @click="handleInfoDelete">
                Delete My Location
              </v-btn>
              </v-col>
              <v-col cols="12">
                <div id="map" class="map"></div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
        <v-footer absolute v-show="recipient">
          <v-row>
            <v-col cols="10">
              <v-text-field
                v-model="message"
                filled
                :placeholder="'Message...'"
              />
            </v-col>
            <v-col cols="2">
              <v-btn color="primary" width="100%" @click="handleSend">
                Send
              </v-btn>
            </v-col>
          </v-row>
        </v-footer>
      </v-container>
    </template>
  </application>
</template>

<script>
  import axios from 'axios';

  export default {
    data () {
      return {
        loading: false,
        search: '',
        headers: [
          { text: 'Message', value: 'message', },
          { text: 'Sent', value: 'created_at' },
          { text: 'Actions' }
        ],
        messages: [],
        message: '',
        recipient: null,
        recipientName: '',
        isChannel: 0,
        polling: null,
        users: {},
      }
    },
    mounted() {
      this.$root.$on('refreshMessages', (item, index, isChannel) => {
        if(index) {
          this.fetchMessages(item, index, isChannel);
        } else {
          this.recipient = null
          this.recipientName = ''
          this.isChannel = 0
          setTimeout(this.refreshMap,500);
        }
      });
      if(!this.recipient) {
        this.getUsersInfo();
      }
      this.polling = setInterval(function () {
        this.messagePoll();
      }.bind(this), 5000);
    },
    beforeDestroy () {
      clearInterval(this.polling)
    },
    computed: {
      filteredItems() {
        var self=this;
         return this.messages.filter(
         function(item) {
           return item.message.toLowerCase().indexOf(self.search.toLowerCase())>=0;
         });
      }
    },
    methods: {
      async fetchMessages(item, index, isChannel) {
        try {
          this.loading = true;
          const messages = await axios.get('/api/messages/'+index+'/'+isChannel);
          this.messages = messages.data;
          this.recipient = index;
          this.isChannel = isChannel;
          if(!isChannel) {
            this.recipientName = item;
          } else {
            if(item.channel_name) {
              this.recipientName = '# '+ item.channel_name;
            }
          }
        }
        catch (e) {
          console.log(e);
        }
        finally {
          this.loading = false;
        }
      },
      async messagePoll() {
        if(this.recipient) {
          const unack = await axios.get('/api/unack/'+this.recipient+'/'+this.isChannel);
          if(unack.data.length) {
            this.fetchMessages(this.recipientName, this.recipient, this.isChannel);
          }
        }
      },
      handleSend() {
        var vm = this;
        let output = {};
        output.msg = this.message;
        output.recipient = this.recipient;
        output.channel = this.isChannel;
        console.log(output);

        axios.post('/api/messages', output)
        .then(function(response) {
          console.log(response.data.result);
          vm.fetchMessages(vm.recipientName, vm.recipient, vm.isChannel);
        });

        this.message = '';
      },
      handleSearch(value) {
      },
      handleDelete(item, index) {
        axios.delete('/api/messages/'+item.id).then(response => this.messages = this.messages.filter(item => item.id !== index));
      },
      async getUsersInfo() {
        try {
          this.loading = true;
          const users = await axios.get('/api/usersinfo');
          this.users = users.data;
        }
        catch (e) {
          console.log(e);
        }
        finally {
          this.loading = false;
          this.refreshMap();
        }
      },
      handleInfoDelete() {
        axios.delete('/api/removelocinfo').then(
          this.refreshMap()
        );
      },
      refreshMap() {
        document.getElementById('map').innerHTML = "";

        var map = new ol.Map({
          target: 'map',
          layers: [
            new ol.layer.Tile({
              source: new ol.source.OSM()
            })
          ],
          view: new ol.View({
            center: ol.proj.fromLonLat([630, 38]),
            zoom: 4
          })
        });

        var features = [];

        for (var i = 0; i < this.users.length; i++) {
          var item = this.users[i];
          var longitude = item.lng;
          var latitude = item.lat;
          var initial = item.name.charAt(0);
          var randomHex = Math.floor(Math.random()*16777215).toString(16);
          console.log (longitude + "," + latitude);

          var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857'))
          });

          var iconStyle = new ol.style.Style({
            image: new ol.style.Icon(({
              anchor: [0.5, 1],
              src: "http://cdn.mapmarker.io/api/v1/pin?text=" + initial + "&size=50&hoffset=1&background=%23"+randomHex
            }))
          });

          iconFeature.setStyle(iconStyle);
          if(latitude) {
            features.push(iconFeature);
          }

        }

        var vectorSource = new ol.source.Vector({
          features: features
        });

        var vectorLayer = new ol.layer.Vector({
          source: vectorSource
        });
        map.addLayer(vectorLayer);
      }
    }
  }
</script>
