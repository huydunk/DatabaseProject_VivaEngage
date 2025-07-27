<template>
  <div class="container mt-4">
    <h2 class="mb-4">Posts</h2>
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Create a New Post</h5>
        <form @submit.prevent="submitPost">

          <div class="mb-3">
            <textarea v-model="newPost.content" class="form-control" rows="3" placeholder="What do you want to share?"
              required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Post</button>
        </form>
      </div>
    </div>

    <div v-if="loading" class="text-center">Loading...</div>

    <div v-else>
      <div v-if="posts.length === 0" class="alert alert-info">
        No posts in this community yet.
      </div>
      <div v-else>
        <CommunityPost v-for="post in posts" :key="post.id" :post="post" @refreshPosts="fetchPosts" />
      </div>
    </div>
  </div>
</template>

<script>
import CommunityPost from '../components/CommunityPost.vue'

export default {
  components: { CommunityPost },
  data() {
    return {
      posts: [],
      loading: true,
      communityId: this.$route.params.id,
      newPost: {
        title: '',
        content: ''
      }
    }
  },
  async mounted() {
    await this.fetchPosts()
  },
  methods: {
    async fetchPosts() {
      try {
        const res = await fetch(`http://localhost/cos20031_project/backend/post.php?communityId=${this.communityId}`)
        const data = await res.json()
        this.posts = data
      } catch (err) {
        console.error('Error loading posts:', err)
      } finally {
        this.loading = false
      }
    },
    async submitPost() {
      const userId = localStorage.getItem("userId")
      if (!userId) {
        alert("You must be logged in to post.")
        return
      }

      const payload = {
        ...this.newPost,
        authorId: parseInt(userId),
        communityId: parseInt(this.communityId)
      }

      try {
        const res = await fetch("http://localhost/cos20031_project/backend/add_post.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        })

        const data = await res.json()

        if (res.ok) {
          alert("Post added!")
          this.newPost.title = ''
          this.newPost.content = ''
          this.fetchPosts() // refresh post list
        } else {
          alert(data.message || "Failed to post.")
        }
      } catch (err) {
        console.error("Error adding post:", err)
      }
    }
  }
}
</script>
