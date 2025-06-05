<template>
  <div>
    <h1>Edit Category</h1>
    <v-card class="mt-4">
      <v-card-text>
        <v-form @submit.prevent="submit" ref="form">
          <v-text-field
            v-model="form.name"
            label="Name"
            required
            :rules="rules.name"
          ></v-text-field>

          <v-select
            v-model="form.parent_id"
            :items="categories"
            item-title="name"
            item-value="id"
            label="Parent Category"
            clearable
          ></v-select>

          <div class="d-flex justify-end mt-4">
            <v-btn
              color="grey"
              class="mr-2"
              to="/categories"
            >
              Cancel
            </v-btn>
            <v-btn
              color="primary"
              type="submit"
              :loading="loading"
            >
              Update
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { useCategoryStore } from '@/stores/category'

export default {
  name: 'CategoryEdit',
  data() {
    return {
      form: {
        name: '',
        parent_id: null
      },
      rules: {
        name: [
          v => !!v || 'Name is required'
        ]
      },
      loading: false,
      categoryStore: useCategoryStore()
    }
  },
  computed: {
    categories() {
      return this.categoryStore.categories.filter(c => c.id !== parseInt(this.$route.params.id))
    }
  },
  methods: {
    async submit() {
      const { valid } = await this.$refs.form.validate()
      
      if (!valid) {
        return
      }

      this.loading = true
      try {
        await this.categoryStore.updateCategory({
          id: this.$route.params.id,
          ...this.form
        })
        this.$router.push('/categories')
      } catch (error) {
        console.error('Error updating category:', error)
      } finally {
        this.loading = false
      }
    }
  },
  async mounted() {
    try {
      const [category, categories] = await Promise.all([
        this.categoryStore.fetchCategory(this.$route.params.id),
        this.categoryStore.fetchCategories()
      ])
      
      if (category) {
        this.form = {
          name: category.name,
          parent_id: category.parent_id
        }
      }
    } catch (error) {
      console.error('Error fetching category:', error)
      this.$router.push('/categories')
    }
  }
}
</script> 