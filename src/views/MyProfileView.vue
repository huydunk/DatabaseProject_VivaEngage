<template>
    <div class="container py-5">
        <div v-if="!user">
            <p class="text-muted">Please log in to view your profile.</p>
        </div>

        <div v-else class="card shadow-lg border-0">
            <div class="row g-0">
                <!-- Profile Picture -->
                <div class="col-md-4 text-center bg-light p-4">
                    <img :src="user.profilePicUrl || defaultPic" class="rounded-circle img-fluid border" alt="Profile"
                        style="max-width: 180px;" />
                    <h4 class="mt-3">{{ user.name }}</h4>
                    <p class="text-muted">@{{ user.username }}</p>
                    <span v-if="user.isVerified" class="badge bg-info">Verified</span>
                    <span class="badge" :class="user.status === 'Online' ? 'bg-success' : 'bg-secondary'">
                        {{ user.status }}
                    </span>
                </div>

                <!-- User Info -->
                <div class="col-md-8 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">About</h5>
                        <small class="text-muted">Joined: {{ user.joinDate }}</small>
                    </div>

                    <p>{{ user.bio || 'No bio yet.' }}</p>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ user.email }}
                        </li>
                        <li class="list-group-item">
                            <strong>Department:</strong> {{ user.departmentId || 'N/A' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Role:</strong> {{ user.roleId }}
                        </li>
                        <li class="list-group-item">
                            <strong>Location:</strong> {{ user.location || 'N/A' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Last Login:</strong> {{ user.lastLogin }}
                        </li>
                    </ul>

                    <div v-if="user.deactivated == 1" class="alert alert-warning mt-3">
                        This account is currently <strong>deactivated</strong>.
                    </div>
                    <h5>Your Posts</h5>
                    <ul>
                        <li v-for="p in user.posts" :key="p.id">{{ p.content }} <br> <small>on {{ p.createdAt }}</small></li>
                    </ul>

                    <h5>Your Comments</h5>
                    <ul>
                        <li v-for="c in user.comments" :key="c.id">{{ c.content }} <br> <small>on {{ c.createdAt }}</small></li>
                    </ul>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            userId: localStorage.getItem("userId"),
            user: null,
            defaultPic: "https://via.placeholder.com/180"
        }
    },
    mounted() {
        if (this.userId) this.fetchProfile()
    },
    methods: {
        async fetchProfile() {
            const res = await fetch(
                `http://localhost/cos20031_project/backend/user_profile.php?userId=${this.userId}`
            )
            this.user = await res.json()
        }
    }
}
</script>
