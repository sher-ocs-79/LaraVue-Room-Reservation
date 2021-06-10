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
                    <v-date-picker v-model="date" mode="dateTime" :minute-increment="30" :attributes='attrs' is-required />
                </div>
                <div class="col-sm">                                
                    <label>Select a Duration</label>   
                    <div class="clearfix"></div>                                  
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="duration" value="30-minutes" id="duration-minute" checked>
                        <label class="form-check-label" for="duration-minute">30 Minutes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" v-model="duration" value="1-hours" id="duration-hour">
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
const DATE_FORMAT = "YYYY-MM-DD HH:mm";
export default {
  data() {
    return {
      rooms: [],
      date: new Date(),
      duration: "30-minutes",
      booking: {
        room_id: 1,
        from: "",
        to: ""
      },
      attrs: [
        {
          key: "today",
          highlight: true,
          dates: new Date()
        }
      ]
    };
  },
  mounted() {
    this.getRooms();
  },
  methods: {
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
    saveBooking() {
      let duration = this.duration.split("-");
      this.booking.from = moment(this.date).format(DATE_FORMAT);
      this.booking.to = moment(this.date)
        .add(parseInt(duration[0]), duration[1])
        .format(DATE_FORMAT);
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .post("/api/booking/add", this.booking)
          .then(response => {
            if (!response.data.added) {
              alert(response.data.message);
            } else {
              this.$router.push({ name: "bookings" });
            }
          })
          .catch(function(error) {
            console.error(error);
          });
      });
    }
  }
};
</script>

