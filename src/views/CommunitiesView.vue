<template>
    <div class="container mt-4">
        <h2 class="mb-4">Communities</h2>
        <div v-if="loading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else>
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
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div v-for="community in paginatedCommunities" :key="community.id">
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
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button class="btn btn-outline-primary" :disabled="currentPage === 1"
                    @click="currentPage-- && fetchCommunities()">
                    ← Previous
                </button>

                <span>Page {{ currentPage }} of {{ totalPages }}</span>

                <button class="btn btn-outline-primary" :disabled="currentPage === totalPages"
                    @click="currentPage++ && fetchCommunities()">
                    Next →
                </button>
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
            currentPage: 1,
            perPage: 5,

        }
    },
    computed: {
        filteredCommunities() {
            if (!this.searchQuery.trim()) return this.communities;

            const query = this.searchQuery.toLowerCase();
            return this.communities.filter(c =>
                c.name.toLowerCase().includes(query)
            );
        },
        paginatedCommunities() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredCommunities.slice(start, end);
        },
        totalPages() {
            return Math.ceil(this.filteredCommunities.length / this.perPage);
        }

    },
    mounted() {
        this.fetchCommunities()
    },
    watch: {
        searchQuery() {
            this.currentPage = 1;
        }
    },
    methods: {
        async fetchCommunities() {
            try {
                const res = await fetch(
                    `http://localhost/cos20031_project/backend/communities.php?page=${this.currentPage}&sort=${this.sortOption}`
                );
                const data = await res.json();
                console.log(data)
                if (res.ok) {
                    this.communities = data;
                    this.totalPages = Math.ceil(data.total / 5);
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
