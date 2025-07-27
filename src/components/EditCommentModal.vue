<template>
  <div
    class="modal fade"
    id="editCommentModal"
    tabindex="-1"
    aria-labelledby="editCommentModalLabel"
    aria-hidden="true"
    ref="modalEl"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <textarea
            v-model="content"
            class="form-control"
            rows="3"
            placeholder="Edit your comment"
          ></textarea>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Cancel
          </button>
          <button type="button" class="btn btn-primary" @click="submitEdit">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, ref, watch } from 'vue'
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle'

export default {
  props: {
    commentId: Number,
    initialContent: String,
    show: Boolean
  },
  emits: ['refresh', 'close'],
  setup(props, { emit }) {
    const content = ref(props.initialContent)
    const modalEl = ref(null)
    let modalInstance = null

    // Watch for changes in initialContent (when editing a different comment)
    watch(() => props.initialContent, (newVal) => {
      content.value = newVal
    })

    watch(() => props.show, (val) => {
      if (val && modalInstance) {
        modalInstance.show()
      }
    })

    const submitEdit = async () => {
      const userId = localStorage.getItem("userId")

      if (!props.commentId || !content.value.trim() || !userId) return

      const res = await fetch("http://localhost/cos20031_project/backend/update_comment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          commentId: props.commentId,
          authorId: parseInt(userId),
          content: content.value.trim()
        })
      })

      const data = await res.json()

      if (res.ok) {
        modalInstance.hide()
        emit('refresh')
      } else {
        alert(data.message || "Failed to update comment")
      }
    }

    onMounted(() => {
      if (modalEl.value) {
        modalInstance = new bootstrap.Modal(modalEl.value, {
          backdrop: 'static'
        })
        modalEl.value.addEventListener('hidden.bs.modal', () => {
          emit('close')
        })
      }
    })

    return {
      content,
      modalEl,
      submitEdit
    }
  }
}
</script>
