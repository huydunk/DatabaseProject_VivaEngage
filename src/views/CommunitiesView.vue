<template>
    <div class="container mt-4">
        <h2 class="mb-4">Communities</h2>
        <div v-if="loading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div>
                <select v-model="sortOption" @change="fetchCommunities" class="form-select mb-3">
                    <option value="created_desc">Newest</option>
                    <option value="created_asc">Oldest</option>
                    <option value="alpha_asc">A → Z</option>
                    <option value="alpha_desc">Z → A</option>
                    <option value="comments_desc">Most Comments</option>
                    <option value="comments_asc">Fewest Comments</option>
                </select>
                <input type="text" v-model="searchQuery" placeholder="Search communities by name..."
                    class="form-control mb-3" />
            </div>


            <div v-for="community in filteredCommunities" :key="community.id">
                <div class="card h-100 shadow-sm" @click="goToCommunity(community.id)" style="cursor: pointer;">

                    <!-- Cover Image -->
                    <img :src="community.coverPicUrl || 'https://via.placeholder.com/400x200?text=No+Image'"
                        class="card-img-top" alt="Community cover" style="object-fit: cover; height: 200px;" />

                    <div class="card-body">
                        <h5 class="card-title">{{ community.name }}</h5>
                        <p class="card-text">{{ community.description }}</p>
                        <p class="text-muted">Total Comments: {{ community.totalComments }}</p>

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
            loading: true,
            searchQuery: '',
            sortOption: 'created_desc',

        }
    },
    computed: {
        filteredCommunities() {
            if (!this.searchQuery.trim()) return this.communities;

            const query = this.searchQuery.toLowerCase();
            return this.communities.filter(c =>
                c.name.toLowerCase().includes(query)
            );
        }
    },
    mounted() {
        this.fetchCommunities()
    },
    methods: {
        async fetchCommunities() {
            try {
                const res = await fetch(`http://localhost/cos20031_project/backend/communities.php?sort=${this.sortOption}`);
                const data = await res.json();
                if (res.ok) {
                    this.communities = data;
                    this.loading = false;
                } else {
                    alert(data.message || 'Failed to load communities.');
                }
            } catch (err) {
                alert('Error fetching communities');
            }
        },
        goToCommunity(id) {
            this.$router.push(`/community/${id}`)
        }
    }
}
</script>
