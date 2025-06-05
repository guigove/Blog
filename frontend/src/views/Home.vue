<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-card class="mt-4">
          <v-card-title>Category Tree</v-card-title>
          <v-card-text>
            <v-list>
              <template v-for="category in categories" :key="category.id">
                <category-tree :category="category" />
              </template>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { useCategoryStore } from '@/stores/category'
import CategoryTree from '@/components/categories/CategoryTree.vue'

export default {
  name: 'Home',
  components: {
    CategoryTree
  },
  data() {
    return {
      categoryStore: useCategoryStore()
    }
  },
  computed: {
    categories() {
      return this.categoryStore.categories
    }
  },
  async mounted() {
    try {
      await this.categoryStore.getNestedCategories()
    } catch (error) {
      console.error('Error fetching nested categories:', error)
    }
  }
}
</script> 