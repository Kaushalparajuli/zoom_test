<template>
    <div class="joining">
        {{ signature }}




    </div>
</template>
<script>
import {ZoomMtg} from '@zoomus/websdk';

export default {
    data() {
        return {
            meeting_number: '',
            meeting_password: '',
            role: 1,
            signature: ''
        }
    },
    methods: {
        getSignature() {
            var data = {
                meeting_number: this.meeting_number,
                role: this.role
            }
            axios.post('/meetings/generate-signature', data)
                .then(response => {
                    this.signature = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        startMeeting(meeting_number,meeting_password, role) {
            this.meeting_number = meeting_number;
            this.meeting_password = meeting_password;
            this.role = role;
            this.getSignature();


        },
        joinMeeting() {

        }
    }
}
</script>
