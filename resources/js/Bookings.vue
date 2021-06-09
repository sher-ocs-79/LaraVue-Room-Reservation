<template>
    <div>
        <h4 class="text-center">Bookings</h4><br/>
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
            <tr v-for="bookings in slot" :key="slot.id">
                <td>{{ slot.id }}</td>
                <td>{{ slot.name }}</td>
                <td>{{ slot.user.fullname }}</td>
                <td>{{ slot.booking_date }}</td>
                <td>{{ slot.booking_time }}</td>
                <td>{{ slot.created_at }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edit-slot', params: { id: slot.id }}" class="btn btn-primary">Edit
                        </router-link>
                        <button class="btn btn-danger" @click="deleteSlot(slot.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-info" @click="this.$router.push('/books/add')">Add Book</button>
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
    deleteSlot(id) {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .delete(`/api/bookings/delete/${id}`)
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