<template>
    <div>
        <h4 class="text-center">{{ title }}</h4><br/>        
        <div class="search-wrapper float-right">
          <input type="text" v-model="search" placeholder="Search here ..."/>
        </div>  
        <table class="table table-bordered">
            <thead>
            <tr>
                <th :class="sortedClass('id')" @click="sortBy('id')">ID</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Room Name</th>
                <th :class="sortedClass('id')" @click="sortBy('id')" v-if="!isLoggedin">Fullname</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Booking Date</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Booking Time</th>
                <th :class="sortedClass('id')" @click="sortBy('id')">Date Created</th>
                <th v-if="isLoggedin">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in filteredItems" :key="item.id">
                <td>{{ item.id }}</td>
                <td>{{ item.room.name }}</td>
                <td v-if="!isLoggedin">{{ item.user.name }}</td>
                <td>{{ formatDate(item.from, "dddd, DD-MMMM-YYYY") }}</td>
                <td>{{ formatDate(item.from, "h:mm:ss a") }} - {{ formatDate(item.to, "h:mm:ss a") }}</td>
                <td>{{ formatDate(item.created_at, "dddd, DD-MMMM-YYYY") }}</td>
                <td v-if="isLoggedin">
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edit-booking', params: { id: item.id }}" class="btn btn-primary">Edit</router-link>
                        <button class="btn btn-danger" @click="deleteBooking(item.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div id="pagination" class="float-right">
          <v-pagination
            v-model="pagination.page"
            :pages="pagecount"
            :range-size="1"
            active-color="#DCEDFF"
            @update:modelValue="getBookings"
          />
        </div>
        <button type="button" class="btn btn-info" @click="navigateTo('/booking/add')" v-if="isLoggedin">Create New</button>
    </div>
</template>

<script>
import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";
import moment from "moment";
export default {
  components: {
    VPagination
  },
  data() {
    return {
      title: "Reservations",
      url: "/api/bookings",
      bookings: [],
      sort: {
        key: "",
        isAsc: false
      },
      pagination: {
        page: 1
      },
      search: ""
    };
  },
  created() {
    if (window.Laravel.isLoggedin) {
      this.title = "My Reservations";
      this.url = "/api/booking/all";
    }
    this.getBookings();
  },
  computed: {
    isLoggedin() {
      return window.Laravel.isLoggedin;
    },
    filteredItems() {
      let list = this.bookings.data;
      if (this.sortedItems) {
        list = this.sortedItems.filter(item => {
          let keys = [item.room.name];
          if (!this.isLoggedin) {
            keys.push(item.user.name);
          }
          return keys
            .join(" ")
            .toLowerCase()
            .includes(this.search.toLowerCase());
        });
      }
      return list;
    },
    sortedItems() {
      let list = this.bookings.data;
      if (!!this.sort.key) {
        list.sort((a, b) => {
          a = a[this.sort.key];
          b = b[this.sort.key];
          return (a === b ? 0 : a > b ? 1 : -1) * (this.sort.isAsc ? 1 : -1);
        });
      }
      return list;
    },
    pagecount() {
      return Math.ceil(this.bookings.total / this.bookings.per_page);
    }
  },
  methods: {
    navigateTo(page) {
      this.$router.push(page);
    },
    formatDate(value, format) {
      return moment(value).format(format);
    },
    getBookings(index) {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        let page = index || 1;
        this.$axios
          .get(this.url + `?page=${page}`)
          .then(response => {
            this.bookings = response.data;
          })
          .catch(function(error) {
            console.error(error);
          });
      });
    },
    deleteBooking(id) {
      this.$axios.get("/sanctum/csrf-cookie").then(response => {
        this.$axios
          .delete(`/api/booking/delete/${id}`)
          .then(response => {
            let i = this.bookings.data.map(item => item.id).indexOf(id); // find index of your object
            this.bookings.data.splice(i, 1);
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
    },
    paginate(page) {
      this.getBookings(page);
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
#pagination {
  margin-right: 13px;
}
.pagination {
  margin: 0px !important;
}
.search-wrapper {
  margin-bottom: 5px;
  border: 1px solid #dee2e6;
}
</style>