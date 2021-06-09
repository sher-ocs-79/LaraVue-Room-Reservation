<template>
    <div>
        <h4 class="text-center">Edit Booking</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="updateBooking">
                    <div class="form-group">
                        <label>Room Name</label>
                        <input type="text" class="form-control" v-model="booking.name">
                    </div>                    
                    <button type="submit" class="btn btn-primary">Update Booking</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      booking: {}
    };
  },
  created() {
    this.$axios.get("/sanctum/csrf-cookie").then(response => {
      this.$axios
        .get(`/api/booking/edit/${this.$route.params.id}`)
        .then(response => {
          this.book = response.data;
        })
        .catch(function(error) {
          console.error(error);
        });
    });
  },
  methods: {
    updateBooking() {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .post(`/api/booking/update/${this.$route.params.id}`, this.book)
          .then(response => {
            this.$router.push({ name: "bookings" });
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