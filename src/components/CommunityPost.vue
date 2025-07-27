<template>
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">{{ post.title }}</h5>
      <p class="card-text">{{ post.content }}</p>
      <p class="text-muted">
        Posted by <b>{{ post.username }}</b> on {{ post.createdAt }}
      </p>

      <!-- Reactions -->
      <div v-if="post.reactions && Object.keys(post.reactions).length > 0" class="mb-2">
        <strong>Reactions: </strong>
        <span v-for="(count, type) in post.reactions" :key="type" class="me-2">
          {{ type }} ({{ count }})
        </span>
      </div>

      <!-- Comments -->
      <div v-if="post.comments && post.comments.length" class="mt-3">
        <h6>Comments</h6>
        <ul class="list-group">
          <li
            v-for="(comment, index) in post.comments"
            :key="index"
            class="list-group-item"
          >
            <p class="mb-1">{{ comment.content }}</p>
            <small class="text-muted">
              by {{ comment.author }} on {{ comment.createdAt }}
            </small>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    post: Object
  }
}
</script>
