<template>
    <div class="joining">
        {{ signature }}
    </div>
</template>
<script>
import {ZoomMtg} from '@zoomus/websdk';
import "@zoomus/websdk/dist/css/bootstrap.css"
import "@zoomus/websdk/dist/css/react-select.css"
export default {
    data() {
        return {
            meeting_number: '',
            meeting_password: '',
            role: 1,
            signature: '',
        }
    },
    mounted() {
        ZoomMtg.setZoomJSLib('https://source.zoom.us/2.3.0/lib', '/av');

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
                    this.joinMeeting();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        startMeeting(meeting_number, meeting_password, role) {
            this.meeting_number = meeting_number;
            this.meeting_password = meeting_password;
            this.role = role;
            this.getSignature();
            document.getElementById('zmmtg-root').style.display = 'block';
        },
        joinMeeting() {
            var leaveUrl = 'http://127.0.0.1:8000'
            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareWebSDK();
            ZoomMtg.i18n.load('en-US');
            ZoomMtg.i18n.reload('en-US');
            ZoomMtg.init({
                leaveUrl: leaveUrl,
                success: (success) => {

                    console.log(success)
                    ZoomMtg.join({
                        signature: this.signature,
                        apiKey: '2bvnb9ggTCKcoge9FS6UzQ',
                        meetingNumber: this.meeting_number,
                        userName: 'kaushal user',
                        passWord: this.meeting_password,
                        success: (success) => {
                            console.log(success);
                        },
                        error: (error) => {
                            console.log('nint error')
                            console.log(error);
                        },
                    });
                },
                error: (error) => {
                    console.log(error)
                }
            })
        }
    }
}
</script>
