<template>
  <div class="post-attachments" v-if="attachments && attachments.length">
    <h6>Attachments:</h6>
    <ul>
      <li v-for="file in attachments" :key="file.id">
        📎 
        <a
          :href="file.fileUrl"
          :download="file.fileName"
          target="_blank"
          rel="noopener"
        >
          {{ file.fileName }} ({{ formatSize(file.fileSize) }}) – Click to download
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'PostAttachment',
  props: {
    attachments: {
      type: Array,
      required: true
    }
  },
  methods: {
    formatSize(size) {
      const kb = size / 1024;
      return kb < 1024
        ? `${kb.toFixed(1)} KB`
        : `${(kb / 1024).toFixed(1)} MB`;
    }
  }
}
</script>

<style scoped>
.post-attachments ul {
  padding-left: 20px;
}
.post-attachments a {
  text-decoration: underline;
  color: #007bff;
  cursor: pointer;
}
</style>
