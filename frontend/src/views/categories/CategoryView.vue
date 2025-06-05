<template>
  <div>
    <h1>Category Details</h1>
    <v-card class="mt-4">
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <h2 class="text-h5 mb-2">{{ category?.name }}</h2>
            <div v-if="category?.children?.length" class="mt-6">
              <h3 class="text-h6 mb-2">Children of this Category</h3>
              <v-list>
                <v-list-item
                  v-for="children in category.children"
                  :key="children.id"
                  :to="`/categories/${children.id}`"
                >
                  <v-list-item-title>{{ children.name }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </div>
            <div v-if="category?.articles?.length" class="mt-6">
              <h3 class="text-h6 mb-2">Articles in this Category</h3>
              <v-list>
                <v-list-item
                  v-for="article in category.articles"
                  :key="article.id"
                  :to="`/articles/${article.id}`"
                >
                  <v-list-item-title>{{ article.title }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="grey"
          class="mr-2"
          to="/categories"
        >
          Back
        </v-btn>
        <v-btn
          color="primary"
          :to="`/categories/${$route.params.id}/edit`"
        >
          Edit
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { useCategoryStore } from '@/stores/category'

export default {
  name: 'CategoryView',
  data() {
    return {
      categoryStore: useCategoryStore()
    }
  },
  computed: {
    category() {
      return this.categoryStore.category
    }
  },
  async mounted() {
    await this.loadCategory()
  },
  watch: {
    '$route.params.id': {
      handler: 'loadCategory',
      immediate: true
    }
  },
  methods: {
    async loadCategory() {
      try {
        await this.categoryStore.fetchCategory(this.$route.params.id, {include:'children,articles'})
      } catch (error) {
        console.error('Error fetching category:', error)
        this.$router.replace('/categories')
      }
    }
  }
}
</script> 