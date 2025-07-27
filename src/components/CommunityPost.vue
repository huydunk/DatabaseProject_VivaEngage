<template>
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">{{ post.title }}</h5>
      <p class="card-text">{{ post.content }}</p>
      <p class="text-muted">
        Posted by <b>{{ post.username }}</b> on {{ post.createdAt }}
      </p>
      <button v-if="isAuthor" @click="deletePost" class="btn btn-sm btn-outline-danger mt-2">
        Delete Post
      </button>
      <!-- Reactions -->
      <div v-if="post.reactions && Object.keys(post.reactions).length > 0" class="mb-2">
        <strong>Reactions: </strong>
        <span v-for="(count, type) in post.reactions" :key="type" class="me-2">
          {{ type }} ({{ count }})
        </span>
      </div>
      <!-- Add Comment Form -->
      <div v-if="isLoggedIn" class="mt-3">
        <textarea v-model="newComment" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
        <button @click="submitComment" class="btn btn-sm btn-primary mt-2">
          Post Comment
        </button>
      </div>

      <!-- Comments -->
      <div v-if="post.comments && post.comments.length" class="mt-3">
        <h6>Comments</h6>
        <ul class="list-group">
          <li v-for="(comment, index) in post.comments" :key="index" class="list-group-item">
            <b>{{ comment.author }}:</b>
            <p class="mb-1">{{ comment.content }}</p>
            <small class="text-muted">
              on {{ comment.createdAt }} (last updated at {{comment.updatedAt}})
            </small>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      newComment: ''
    }
  },
  props: {
    post: Object
  },
  computed: {
    isAuthor() {
      const loggedInId = parseInt(localStorage.getItem("userId"))
      const isAuthor = loggedInId == parseInt(this.post.authorId);
      console.log(this.post.authorId);
      console.log(isAuthor);
      return isAuthor;
    },
    isLoggedIn() {
      return localStorage.getItem("isLoggedIn") === "true"
    }
  },
  methods: {
    async deletePost() {
      if (!confirm("Are you sure you want to delete this post?")) return;

      const res = await fetch("http://localhost/cos20031_project/backend/delete_post.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          postId: this.post.id,
          userId: localStorage.getItem("userId")
        })
      });

      const data = await res.json();

      if (res.ok) {
        alert("Post deleted.");
        this.$emit("refreshPosts"); // tell parent to reload posts
      } else {
        alert(data.message || "Failed to delete.");
      }
    },
    async submitComment() {
      const content = this.newComment.trim()
      const authorId = localStorage.getItem("userId")

      if (!content || !authorId) {
        alert("You must be logged in and write something.")
        return
      }

      const res = await fetch("http://localhost/cos20031_project/backend/add_comment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          postId: this.post.id,
          authorId: parseInt(authorId),
          content
        })
      })

      const data = await res.json()

      if (res.ok) {
        this.newComment = ''
        this.$emit("refreshPosts") // refresh all posts/comments
      } else {
        alert(data.message || "Failed to add comment")
      }
    }

  }
}
</script>
