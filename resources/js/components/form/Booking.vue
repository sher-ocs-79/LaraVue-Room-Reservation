<template>
    <form @submit.prevent="saveBooking">
        <div class="form-group">
            <label>Select a Room</label>                       
            <select class="form-control" v-model="booking.room_id">                            
                <option v-for='room in rooms' :value='room.id' v-text='room.name'></option>
            </select>
        </div>     
        <div class="form-group">       
            <div class="row">
                <div class="col-sm">
                    <label>Select a Date</label>    
                    <div class="clearfix"></div>                        
                    <v-date-picker v-model="date" mode="dateTime" :minute-increment="30" :from-page="{month:calendarAttrs.month, year:calendarAttrs.year}"/>
                </div>
                <div class="col-sm">                                
                    <label>Select a Duration</label>   
                    <div class="clearfix"></div>                                  
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="duration" :value="getDuration(30)" id="duration-minute" checked>
                        <label class="form-check-label" for="duration-minute">30 Minutes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="duration" :value="getDuration(60)" id="duration-hour">
                        <label class="form-check-label" for="duration-hour">1 Hour</label>
                    </div>
                </div>
            </div>                            
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary btn-lg">Save Booking</button>
        </div>
    </form>
</template>

<script>
import moment from "moment";
const DATE_FORMAT = "YYYY-MM-DD HH:mm:00";
let durations = { 30: "30-minutes", 60: "1-hours" };
export default {
  props: ["id"],
  data() {
    return {
      action: "add",
      rooms: [],
      date: new Date(),
      duration: "30-minutes",
      booking: {
        room_id: 1,
        from: "",
        to: ""
      },
      calendarAttrs: {}
    };
  },
  mounted() {
    this.getRooms();
    if (this.id) {
      this.getBooking(this.id);
      this.action = "update/" + this.id;
    }
  },
  methods: {
    getDuration(duration) {
      return durations[duration];
    },
    getRooms() {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .get("/api/rooms")
          .then(response => {
            this.rooms = response.data;
          })
          .catch(function(error) {
            console.error(error);
          });
      });
    },
    getBooking(id) {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .get("/api/booking/get/" + id)
          .then(response => {
            this.booking = response.data;
            this.date = this.booking.from;
            this.calendarAttrs.month = parseInt(moment(this.date).format("M"));
            this.calendarAttrs.year = parseInt(
              moment(this.date).format("YYYY")
            );
            let diff = Math.abs(
              moment(this.booking.from).diff(this.booking.to, "minutes")
            );
            this.duration = durations[diff];
          })
          .catch(function(error) {
            console.error(error);
          });
      });
    },
    saveBooking() {
      let duration = this.duration.split("-");
      this.booking.from = moment(this.date).format(DATE_FORMAT);
      this.booking.to = moment(this.date)
        .add(parseInt(duration[0]), duration[1])
        .format(DATE_FORMAT);
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.commit();
      });
    },
    commit() {
      this.$axios
        .post("/api/booking/" + this.action, this.booking)
        .then(response => {
          if (!response.data.success) {
            alert(response.data.message);
          } else {
            this.$router.push({ name: "bookings" });
          }
        })
        .catch(function(error) {
          console.error(error);
        });
    }
  }
};
</script>

