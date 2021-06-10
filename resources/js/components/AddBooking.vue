<template>
    <div>
        <h4 class="text-center">New Reservation</h4>
        <div class="row">
            <div class="col-md-6">
                <booking-form/>
            </div>
        </div>
    </div>
</template>

<script>
import BookingForm from "./form/Booking";
export default {
  components: {
    BookingForm
  },
  beforeRouteEnter(to, from, next) {
    if (!window.Laravel.isLoggedin) {
      window.location.href = "/";
    }
    next();
  }
};
</script>