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
            <tr v-for="bookings in slot" :key="booking.id">
                <td>{{ booking.id }}</td>
                <td>{{ booking.name }}</td>
                <td>{{ booking.user.fullname }}</td>
                <td>{{ booking.booking_date }}</td>
                <td>{{ booking.booking_time }}</td>
                <td>{{ booking.created_at }}</td>
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
    </div>
</template>

<script>
export default {
  data() {
    return {
      books: []
    };
  },
  created() {
    this.$axios.get("/sanctum/csrf-cookie").then(response => {
      this.$axios
        .get("/api/bookings")
        .then(response => {
          this.books = response.data;
        })
        .catch(function(error) {
          console.error(error);
        });
    });
  },
  methods: {
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