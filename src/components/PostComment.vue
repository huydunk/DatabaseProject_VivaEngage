<!-- PostComment.vue -->
<template>
    <div>
        <!-- Comments -->
        <div v-if="comments.length">
            <h6>Comments</h6>
            <ul class="list-group">
                <li v-for="(comment, index) in comments" :key="comment.id || index" class="list-group-item">
                    <b>{{ comment.author }}:</b> {{ comment.content }} <br>
                    <small class="text-muted">
                        on {{ comment.createdAt }} last updated at {{ comment.updatedAt }}
                    </small>
                    <button v-if="isCommentAuthor(comment)" class="btn btn-sm btn-outline-primary ms-2"
                        @click="openEdit(comment)">
                        Edit
                    </button>
                    <button v-if="isCommentAuthor(comment)" class="btn btn-sm btn-outline-danger ms-2"
                        @click="deleteComment(comment.id)">
                        Delete
                    </button>

                </li>
            </ul>
        </div>


    </div>
    <!-- Add Comment Form -->
    <!-- <div v-if="isLoggedIn" class="mt-3">
        <textarea v-model="newComment" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
        <button @click="submitComment" class="btn btn-sm btn-primary mt-2">
            Post Comment
        </button>
    </div> -->
    <EditCommentModal v-show="showEditModal" :comment-id="editingCommentId" :initial-content="editingContent"
        :show="showEditModal" @refresh="refreshPosts" @close="closeModal" />


</template>

<script>
import EditCommentModal from './EditCommentModal.vue'



export default {
    components: { EditCommentModal },
    props: {
        postId: Number,
        comments: Array
    },
    data() {
        return {
            editingCommentId: null,
            editingContent: '',
            showEditModal: false
        }
    },
    computed: {
        isLoggedIn() {
            return localStorage.getItem("isLoggedIn") === "true"
        }
    },
    methods: {
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
                    postId: this.postId,
                    authorId: parseInt(authorId),
                    content
                })
            })

            const data = await res.json()

            if (res.ok) {
                this.newComment = ''
                this.$emit("refreshPosts")
            } else {
                alert(data.message || "Failed to add comment")
            }
        },
        isCommentAuthor(comment) {
            return comment.authorId === localStorage.getItem("userId")
        },
        openEdit(comment) {
            this.editingCommentId = comment.id
            this.editingContent = comment.content
            this.showEditModal = true
        },
        closeModal() {
            this.showEditModal = false
            this.editingCommentId = null
            this.editingContent = ''
        },
        refreshPosts() {
            this.$emit("refreshPosts")
        },
        async deleteComment(commentId) {
            const confirmDelete = confirm("Are you sure you want to delete this comment?")
            if (!confirmDelete) return

            const userId = localStorage.getItem("userId")

            const res = await fetch("http://localhost/cos20031_project/backend/delete_comment.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    commentId,
                    authorId: parseInt(userId)
                })
            })

            const data = await res.json()

            if (res.ok) {
                this.$emit("refreshPosts")
            } else {
                alert(data.message || "Failed to delete comment.")
            }
        }
    }
}
</script>
