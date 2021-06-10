<template>
    <div>
        <h4 class="text-center">{{ title }}</h4><br/>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th :class="sortedClass('id')" @click="sortBy('id')">ID</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Room Name</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Fullname</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Booking Date</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Booking Time</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Date Created</th>
                <th v-if="isLoggedin">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="booking in sortedItems" :key="booking.id">
                <td>{{ booking.id }}</td>
                <td>{{ booking.room.name }}</td>
                <td>{{ booking.user.name }}</td>
                <td>{{ formatDate(booking.from, "dddd, DD-MMMM-YYYY") }}</td>
                <td>{{ formatDate(booking.from, "h:mm:ss a") }} - {{ formatDate(booking.to, "h:mm:ss a") }}</td>
                <td>{{ formatDate(booking.created_at, "dddd, DD-MMMM-YYYY") }}</td>
                <td v-if="isLoggedin">
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edit-booking', params: { id: booking.id }}" class="btn btn-primary">Edit</router-link>
                        <button class="btn btn-danger" @click="deleteBooking(booking.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-info" @click="navigateTo('/booking/add')" v-if="isLoggedin">Create New</button>
    </div>
</template>

<script>
import moment from "moment";
export default {
  data() {
    return {
      title: "Reservations",
      url: "/api/bookings",
      bookings: [],
      sort: {
        key: "",
        isAsc: false
      }
    };
  },
  created() {
    if (window.Laravel.isLoggedin) {
      this.title = "My Reservations";
      this.url = "/api/booking/all";
    }
    this.$axios.get("/sanctum/csrf-cookie").then(response => {
      this.$axios
        .get(this.url)
        .then(response => {
          this.bookings = response.data;
        })
        .catch(function(error) {
          console.error(error);
        });
    });
  },
  computed: {
    isLoggedin() {
      return window.Laravel.isLoggedin;
    },
    sortedItems() {
      const list = this.bookings.slice();
      if (!!this.sort.key) {
        list.sort((a, b) => {
          a = a[this.sort.key];
          b = b[this.sort.key];
          return (a === b ? 0 : a > b ? 1 : -1) * (this.sort.isAsc ? 1 : -1);
        });
      }
      return list;
    }
  },
  methods: {
    navigateTo(page) {
      this.$router.push(page);
    },
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
    },
    sortedClass(key) {
      return this.sort.key === key
        ? `sorted ${this.sort.isAsc ? "asc" : "desc"}`
        : "";
    },
    sortBy(key) {
      this.sort.isAsc = this.sort.key === key ? !this.sort.isAsc : false;
      this.sort.key = key;
    }
  }
  // beforeRouteEnter(to, from, next) {
  //   if (!window.Laravel.isLoggedin) {
  //     window.location.href = "/";
  //   }
  //   next();
  // }
};
</script>

<style scoped>
table th.sorted.asc::after {
  display: inline-block;
  content: "▼";
}
table th.sorted.desc::after {
  display: inline-block;
  content: "▲";
}
</style>