<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" v-if="isLoggedIn">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/home">VivaEngage</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link class="nav-link" to="/home">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/communities">Communities</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/about">About</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" :to="'/profile/' + userId">My Profile</router-link>
            </li>

          </ul>
          <span class="navbar-text text-light me-3" v-if="isLoggedIn">Welcome, #{{userId}} @{{ username }}!</span>
          <button class="btn btn-outline-light" @click="logout">Logout</button>
        </div>
      </div>
    </nav>

    <router-view />
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false,
      userId: localStorage.getItem("userId"),
      username: ''
    }
  },
  mounted() {
    this.checkLogin()
  },
  watch: {
    $route() {
      this.checkLogin()
    }
  },
  methods: {
    checkLogin() {
      this.isLoggedIn = localStorage.getItem("isLoggedIn") === "true"
      this.userId = localStorage.getItem("userId") || ''
      this.username = localStorage.getItem("username") || ''
    },
    logout() {
      localStorage.removeItem("isLoggedIn")
      localStorage.removeItem("username")
      this.isLoggedIn = false
      this.username = ''
      this.$router.push("/login")
    }
  }
}

</script>
