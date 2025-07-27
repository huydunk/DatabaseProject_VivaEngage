<template>
    <div class="container mt-4">
        <h2 class="mb-4">Communities</h2>
        <div v-if="loading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col" v-for="community in communities" :key="community.id">
                <div class="card h-100 shadow-sm" @click="goToCommunity(community.id)" style="cursor: pointer;">

                    <!-- Cover Image -->
                    <img :src="community.coverPicUrl || 'https://via.placeholder.com/400x200?text=No+Image'"
                        class="card-img-top" alt="Community cover" style="object-fit: cover; height: 200px;" />

                    <div class="card-body">
                        <h5 class="card-title">{{ community.name }}</h5>
                        <p class="card-text">{{ community.description }}</p>
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
            communities: [],
            loading: true
        }
    },
    mounted() {
        this.fetchCommunities()
    },
    methods: {
        async fetchCommunities() {
            try {
                const res = await fetch("http://localhost/cos20031_project/backend/communities.php")
                const data = await res.json()
                this.communities = data
            } catch (err) {
                console.error("Failed to load communities:", err)
            } finally {
                this.loading = false
            }
        },
        goToCommunity(id) {
            this.$router.push(`/community/${id}`)
        }
    }
}
</script>
