<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">Login</h3>
                        <form @submit.prevent="handleLogin">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input v-model="username" type="text" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input v-model="password" type="password" class="form-control" required />
                            </div>
                            <div v-if="error" class="alert alert-danger">{{ error }}</div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            username: '',
            password: '',
            error: ''
        }
    },
    methods: {
        async handleLogin() {
            try {
                const res = await fetch("http://localhost/cos20031_project/backend/login.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        username: this.username,
                        password: this.password
                    })
                })

                const data = await res.json()

                if (!res.ok) {
                    this.error = data.message
                } else {
                    localStorage.setItem("isLoggedIn", "true")
                    localStorage.setItem("username", this.username)
                    localStorage.setItem("userId", data.userId) // ✅ store userId
                    this.$router.push('/home')
                }
            } catch (err) {
                this.error = "Login failed"
            }
        }

    }
}
</script>
