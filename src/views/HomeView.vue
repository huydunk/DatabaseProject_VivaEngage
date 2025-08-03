<template>
  <div class="home container py-5">
    <div class="text-center mb-5">
      <h1 class="display-4">Welcome to VivaEngage</h1>
      <p class="lead">
        A collaborative platform for communities to connect, share ideas, and grow together.
      </p>
    </div>

    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Join a Community</h5>
            <p class="card-text">
              Explore public groups that match your interests and start participating in discussions.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Create & Share</h5>
            <p class="card-text">
              Post your thoughts, upload attachments, and collaborate with others in a structured space.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Stay Engaged</h5>
            <p class="card-text">
              React, comment, and contribute to an active network of like-minded individuals.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <router-link to="/communities" class="btn btn-primary btn-lg">
        Explore Communities
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeView'
}
</script>

<style scoped>
.home {
  max-width: 960px;
  margin: auto;
}
</style>
