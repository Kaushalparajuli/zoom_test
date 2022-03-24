<template>
    <div class="container">
        <notifications group="foo"/>
        <div class="form mt-5 justify-content-center">
            <form @submit.prevent="create">
                <div class="row">
                    <div class="form-group mb-2 col-6">
                        <label>Meeting Agenda</label>
                        <input type="text" class="form-control" required v-model="agenda">
                    </div>
                    <div class="form-group mb-2 col-6">
                        <label>Meeting Topic</label>
                        <input type="text" class="form-control" required v-model="topic">
                    </div>
                    <div class="form-group mb-2 col-12">
                        <label>Meeting Password</label>
                        <input type="text" class="form-control" required v-model="password">
                    </div>
                    <div class="form-group mb-2 col-6">
                        <label>Start Time</label>
                        <input type="datetime-local" required class="form-control" v-model="start_time">
                    </div>
                    <div class="form-group mb-2 col-6">
                        <label>Duration (in minutes)</label>
                        <input type="number" class="form-control" required v-model="duration">
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Create Meeting</button>
                </div>
            </form>
        </div>

        <div class="meeting-list mt-5">
            <table class="table table-dark">
                <thead>
                <tr>
                    <th>Meeting ID</th>
                    <th>Meeting Password</th>
                    <th>Meeting Topic</th>
                    <th>Meeting Agenda</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="m in data" :key="m.index">
                    <td>{{ m.meeting_id }}</td>
                    <td>{{ m.password }}</td>
                    <td>{{ m.topic }}</td>
                    <td>{{ m.agenda }}</td>
                    <td>{{ m.start_time }}</td>
                    <td>{{ m.end_time }}</td>
                    <td>{{ m.duration }}</td>
                    <td>
                        <div class="d-flex flex-column" v-if="getDate() < m.end_time">
                            <button class="btn btn-success mb-1" @click="copyStart(m.start_url)">
                                Copy Start Url
                            </button>
                            <button class="btn btn-success mb-1" @click="copyJoin(m.join_url)">
                                Copy Join Url
                            </button>
                            <button  class="btn btn-danger mb-1" @click="start(m.meeting_id,m.password,'start')">
                                Start Meeting
                            </button>
                            <button  class="btn btn-primary mb-1" @click="start(m.meeting_id,m.password,'join')">
                                Join Meeting
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <join-meeting ref="joinmeeting"></join-meeting>
    </div>
</template>

<script>
import {random} from "lodash/number";
import moment from "moment";
import JoinMeeting from "./JoinMeeting";

export default {
    components: {
        JoinMeeting
    },

    data() {
        return {
            start_time: moment().format('YYYY-MM-DDTHH:mm'),
            password: random(100000, 999999),
            duration: 30,
            topic: 'Meeting Zoom',
            agenda: 'Agenda of meeting',

            type: 1,

            data: []
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/meetings/get')
                .then(response => {
                    this.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        create() {
            this.start_time = moment(this.start_time).format('YYYY-MM-DDTHH:mm:ss');
            var data = {
                "agenda": this.agenda,
                "default_password": true,
                "duration": this.duration,
                "pre_schedule": true,
                "schedule_for": "kaushal123parajuli@gmail.com",
                "start_time": this.start_time,
                "topic": this.topic,
                "timezone": "Asia/Kathmandu",
                'password': this.password,
                "settings": {
                    "host_video": true,
                    "participant_video": false,
                    "in_meeting": true,
                    "join_before_host": true,
                    "jbh_time": 0
                },

                "type": 1
            }
            var params = {
                data: data
            }

            axios.post('/meetings/create', params).then(response => {
                this.getData();
                console.log(response.data);
            });
        },
        copyStart(elem) {
            navigator.clipboard.writeText(elem);
            this.$notify({
                group: 'foo',
                title: 'Link Copied',
            });

        },
        copyJoin(elem) {

            navigator.clipboard.writeText(elem);
            this.$notify({
                group: 'foo',
                title: 'Link Copied',
            });
        },
        start(id,pass,mode) {
            console.log(id)
            var m = 1
            if (mode=='start'){
                m = 1
            }else{
                m = 0
            }
            this.$refs.joinmeeting.startMeeting(id, pass,m);
        },
        getDate(){
            console.log(moment().format('YYYY-MM-DD HH:mm:ss'))
          return  moment().format('YYYY-MM-DD HH:mm:ss');
        }
    }
}
</script>
