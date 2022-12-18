<template>
  <application>
    <template v-slot:content>
      <v-container class="pa-4" >
        <v-card :loading="loading">
          <v-card-title>
            Send Messages
          </v-card-title>
          <v-card-text>
            <v-text-field
              v-model="recipients"
              filled
              :placeholder="'Sally, Tom, #wholeteam'"
            />
            <v-textarea
              v-model="message"
              filled
              label="Message"
              :placeholder="'Message...'"
              hint="Enter your message here."
              persistent-hint
            />
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" width="100%" @click="handleSend">
              Send Message(s)
            </v-btn>
          </v-card-actions>
        </v-card>
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
        message: '',
        recipients: '',
      }
    },
    methods: {
      handleSend() {
        let msg = this.message;
        let recipients = this.recipients;
        let output = {};
        output.msg = msg;
        output.recipients = recipients;
        console.log(output);

        axios.post('/api/messages', output)
        .then(function(response) {
          console.log(response.data.result);
        });

        this.message = '';
        this.recipients = '';
      }
    }
  }
</script>
