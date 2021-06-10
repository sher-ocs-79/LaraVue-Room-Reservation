<template>
    <div>
        <h4 class="text-center">My Bookings</h4><br/>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Room Name</th>
                <th>Fullname</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="booking in bookings" :key="booking.id">
                <td>{{ booking.id }}</td>
                <td>{{ booking.room.name }}</td>
                <td>{{ booking.user.name }}</td>
                <td>{{ formatDate(booking.from, "dddd, DD-MMMM-YYYY") }}</td>
                <td>{{ formatDate(booking.from, "h:mm:ss a") }} - {{ formatDate(booking.to, "h:mm:ss a") }}</td>
                <td>{{ formatDate(booking.created_at, "dddd, DD-MMMM-YYYY") }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edit-booking', params: { id: booking.id }}" class="btn btn-primary">Edit
                        </router-link>
                        <button class="btn btn-danger" @click="deleteBooking(booking.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-info" @click="this.$router.push('/booking/add')">Create New</button>
    </div>
</template>

<script>
import moment from "moment";
export default {
  data() {
    return {
      bookings: []
    };
  },
  created() {
    this.$axios.get("/sanctum/csrf-cookie").then(response => {
      this.$axios
        .get("/api/booking/list")
        .then(response => {
          this.bookings = response.data;
        })
        .catch(function(error) {
          console.error(error);
        });
    });
  },
  methods: {
    formatDate(value, format) {
      return moment(value).format(format);
    },
    deleteBooking(id) {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .delete(`/api/booking/delete/${id}`)
          .then(response => {
            let i = this.bookings.map(item => item.id).indexOf(id); // find index of your object
            this.bookings.splice(i, 1);
          })
          .catch(function(error) {
            console.error(error);
          });
      });
    }
  },
  beforeRouteEnter(to, from, next) {
    if (!window.Laravel.isLoggedin) {
      window.location.href = "/";
    }
    next();
  }
};
</script>