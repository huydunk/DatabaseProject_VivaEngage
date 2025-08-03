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
  <div class="home container py-4">
    <h1 class="mb-3">5 Main Use Case Query Summary</h1>

    <div class="mb-4">
      <h2>Retrieve all posts in a community</h2>
      <p>
        In <code>post.php</code>, fetches all posts in a community with author details,
        reactions, comments, and attachments.
      </p>
      <pre><code>SELECT 
  post.id,
  post.content,
  post.createdAt,
  post.authorId,
  user.username AS authorUsername,
  user.name AS authorName
FROM post
JOIN user ON post.authorId = user.id
WHERE post.communityId = $communityId
ORDER BY post.createdAt DESC;</code></pre>
      <p>
        <strong>Used in:</strong> <code>CommunityView.vue</code> — Called via
        <code>fetchPosts()</code> when the component is mounted; displays post content,
        author, reactions, and comments.
      </p>
    </div>

    <div class="mb-4">
      <h2>Comment of a post</h2>
      <p>
        Within <code>post.php</code>, nested inside the loop for posts — retrieves all
        comments per post along with author info and timestamps.
      </p>
      <pre><code>SELECT 
  comment.id,
  comment.authorId, 
  comment.content, 
  comment.createdAt, 
  user.username AS author,
  comment.updatedAt
FROM comment
JOIN user ON comment.authorId = user.id
WHERE comment.postId = $postId
ORDER BY comment.createdAt ASC;</code></pre>
      <p>
        <strong>Used in:</strong> <code>CommunityView.vue</code> — Comments are rendered
        under each post using data from the backend, part of <code>fetchPosts()</code>
        result.
      </p>
    </div>

    <div class="mb-4">
      <h2>Reactions of a post</h2>
      <p>
        Still in <code>post.php</code>, grouped by reaction type and returned as a
        key-value dictionary.
      </p>
      <pre><code>SELECT reaction, COUNT(*) AS count
FROM reaction
WHERE postId = $postId
GROUP BY reaction;</code></pre>
      <p>
        <strong>Used in:</strong> <code>CommunityView.vue</code> — Displayed next to posts
        using icons (e.g., Like, Love) with reaction counts. No separate function; comes
        with the main post fetch.
      </p>
    </div>

    <div class="mb-4">
      <h2>Community Info</h2>
      <p>
        In <code>community.php</code>, returns name, description, cover image, creator
        username, creation date, member count, and more.
      </p>
      <pre><code>SELECT 
  community.name,
  community.description,
  community.coverPicUrl,
  community.createdAt,
  user.username AS createdBy
FROM community
JOIN user ON community.createdBy = user.id
WHERE community.id = $communityId;</code></pre>
      <p>
        <strong>Used in:</strong> <code>CommunityView.vue</code> — Loaded via
        <code>fetchCommunityInfo()</code> on mount; used to populate cover image, title,
        and metadata at the top of the community page.
      </p>
    </div>

    <div class="mb-4">
      <h2>Member List</h2>
      <p>
        Fetched in <code>community.php</code>, joins user and role data from
        <code>usercommunity</code> and <code>user</code>.
      </p>
      <pre><code>SELECT u.id, u.username, uc.role
FROM usercommunity uc
JOIN user u ON uc.userId = u.id
WHERE uc.communityId = $communityId
ORDER BY uc.role = 'Admin' DESC, u.username;</code></pre>
      <p>
        <strong>Used in:</strong> <code>CommunityView.vue</code> — Member list rendered in
        a <code>&lt;ul&gt;</code> using <code>community.members</code>. Admins are shown at
        the top. Possibly also used in <code>ProfileView.vue</code> for shared logic.
      </p>
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
