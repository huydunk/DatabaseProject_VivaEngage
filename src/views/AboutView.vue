<template>
  <div class="about container py-4">
    <h1 class="mb-3">About This Project</h1>

    <p>
      This website was developed as part of the COS20031 Software Engineering Project.
      It serves as a community discussion platform where users can join groups, create posts,
      and engage in meaningful conversations. The system supports real-time content sharing,
      user authentication, file attachments, and interactive discussions.
    </p>

    <h2 class="mt-4">Contributors</h2>
    <div class="row mt-3">
      <div class="col-md-4" v-for="student in students" :key="student.name">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">{{ student.name }}</h5>
            <p class="card-text">
              <strong>Student ID:</strong> {{ student.id }}<br />
              <strong>Email:</strong> <a :href="'mailto:' + student.email">{{ student.email }}</a><br />
              <strong>Role:</strong> {{ student.role }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AboutView',
  data() {
    return {
      students: [
        {
          name: 'Nguyen Dang Huy',
          id: '103836512',
          email: '103836512@student.swin.edu.au',
          role: 'Lead Developer'
        },
        {
          name: 'Tran Tuan Huy',
          id: 's2345678',
          email: 'brian.tran@student.university.edu',
          role: 'Backend Developer'
        },
        {
          name: 'Vu Huy Chris',
          id: 's3456789',
          email: 'chris.le@student.university.edu',
          role: 'Database & API Integration'
        }
      ]
    };
  }
}
</script>

<style scoped>
.about {
  max-width: 960px;
  margin: auto;
}
</style>
