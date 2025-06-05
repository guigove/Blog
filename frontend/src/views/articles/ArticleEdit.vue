<template>
  <div>
    <h1>Edit Article</h1>
    <v-card class="mt-4">
      <v-card-text>
        <v-form @submit.prevent="submit" ref="form">
          <v-text-field
            v-model="form.title"
            label="Title"
            required
            :rules="rules.title"
          ></v-text-field>

          <v-select
            v-model="form.category_id"
            :items="categories"
            item-title="name"
            item-value="id"
            label="Category"
            required
            :rules="rules.category_id"
          ></v-select>

          <v-textarea
            v-model="form.body"
            label="Content"
            required
            :rules="rules.body"
          ></v-textarea>

          <v-select
            v-model="form.tag_ids"
            :items="tags"
            item-title="name"
            item-value="id"
            label="Tags"
            multiple
            chips
          ></v-select>

          <WikipediaSummary
            v-model="wikipediaSummary"
            :title="form.title"
          />

          <div class="d-flex justify-end mt-4">
            <v-btn
              color="grey"
              class="mr-2"
              to="/articles"
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
import { useArticleStore } from '@/stores/article'
import { useCategoryStore } from '@/stores/category'
import { useTagStore } from '@/stores/tag'
import WikipediaSummary from '@/components/shared/WikipediaSummary.vue'

export default {
  name: 'ArticleEdit',
  components: {
    WikipediaSummary
  },
  data() {
    return {
      form: {
        title: '',
        body: '',
        category_id: null,
        tag_ids: []
      },
      rules: {
        title: [
          v => !!v || 'Title is required',
        ],
        body: [
          v => !!v || 'Body is required',
        ],
        category_id: [
          v => !!v || 'Category is required'
        ]
      },
      loading: false,
      wikipediaSummary: '',
      articleStore: useArticleStore(),
      categoryStore: useCategoryStore(),
      tagStore: useTagStore()
    }
  },
  computed: {
    categories() {
      return this.categoryStore.categories
    },
    tags() {
      return this.tagStore.tags
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
        await this.articleStore.updateArticle({
          id: this.$route.params.id,
          ...this.form
        })
        this.$router.push('/articles')
      } catch (error) {
        console.error('Error updating article:', error)
      } finally {
        this.loading = false
      }
    }
  },
  async mounted() {
    try {
      await Promise.all([
        this.categoryStore.fetchCategories(),
        this.tagStore.fetchTags()
      ])

      const article = await this.articleStore.fetchArticle(this.$route.params.id, {include: 'tags'})
      if (article) {
        this.form = {
          title: article.title,
          body: article.body,
          category_id: article.category_id,
          tag_ids: article.tags?.map(tag => tag.id) || []
        }
        this.wikipediaSummary = article.wikipedia_summary || ''
      }
    } catch (error) {
      console.error('Error fetching article:', error)
      this.$router.push('/articles')
    }
  }
}
</script> 