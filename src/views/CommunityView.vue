<template>
  <div class="container mt-4">
    <div class="card mb-4 shadow-sm">
      <img v-if="community.coverUrl" :src="community.coverUrl" class="card-img-top" alt="Cover image"
        style="height: 200px; object-fit: cover" />
      <div class="card-body">
        <h2 class="card-title">{{ community.name }}</h2>
        <p class="text-muted mb-1">
          Created by {{ community.createdBy }} on {{ community.createdAt }}
        </p>
        <p class="text-muted mb-1">
          Members: {{ community.memberCount }}
        </p>

        <div v-if="community.members && community.members.length">
          <h6>Members</h6>
          <ul class="list-group">
            <li v-for="member in community.members" :key="member.id"
              class="list-group-item d-flex justify-content-between align-items-center">
              {{ member.username }}
              <span class="badge bg-secondary">{{ member.role }}</span>
            </li>
          </ul>
        </div>

        <p>{{ community.description }}</p>
      </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Posts</h4>
      <select v-model="sortOption" class="form-select w-auto">
        <option value="recent">Most Recent</option>
        <option value="reactions">Most Reactions</option>
        <option value="comments">Most Comments</option>
        <option value="author">Author A-Z</option>
      </select>
    </div>

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
        <CommunityPost v-for="post in sortedPosts" :key="post.id" :post="post" @refreshPosts="fetchPosts"/>

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
      newPost: {
        title: '',
        content: ''
      },
      sortOption: 'recent',
      posts: [],
      loading: true,
      communityId: this.$route.params.id,
      community: {
        name: '',
        description: '',
        coverUrl: '',
        createdBy: '',
        createdAt: '',
        memberCount: 0
      }
    }
  },
  computed: {
    sortedPosts() {
      return [...this.posts].sort((a, b) => {
        if (this.sortOption === 'reactions') {
          const aTotal = this.countReactions(a.reactions)
          const bTotal = this.countReactions(b.reactions)
          return bTotal - aTotal
        } else if (this.sortOption === 'comments') {
          return b.comments.length - a.comments.length
        } else if (this.sortOption === 'author') {
          return a.username.localeCompare(b.username)
        } else {
          // default: sort by createdAt descending
          return new Date(b.createdAt) - new Date(a.createdAt)
        }
      })
    }
  },
  mounted() {
    this.fetchCommunity()
    this.fetchPosts()
  },

  methods: {
    async fetchCommunity() {
      const res = await fetch(`http://localhost/cos20031_project/backend/community.php?communityId=${this.communityId}`)
      const data = await res.json()
      this.community = data
    },

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
    countReactions(reactions) {
      return Object.values(reactions || {}).reduce((sum, val) => sum + val, 0)
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
          this.newPost.content = ''
          this.fetchPosts() // refresh post list
          this.$emit("refreshPosts")
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
