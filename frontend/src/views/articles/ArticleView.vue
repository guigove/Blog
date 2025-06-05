<template>
  <div>
    <h1>Article Details</h1>
    <v-card class="mt-4">
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <h2 class="text-h5 mb-2">{{ article?.title }}</h2>
            <div class="text-subtitle-1 mb-4">
              Category: {{ article?.category?.name }}
            </div>
            <div class="mb-4">
              <v-chip
                v-for="tag in article?.tags"
                :key="tag.id"
                class="mr-2 mb-2"
                color="primary"
                variant="outlined"
              >
                {{ tag.name }}
              </v-chip>
            </div>
            <div class="text-body-1 mb-4">
              {{ article?.body }}
            </div>
            <WikipediaSummary
              v-model="wikipediaSummary"
              :title="article?.title"
            />
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="grey"
          class="mr-2"
          to="/articles"
        >
          Back
        </v-btn>
        <v-btn
          color="primary"
          :to="`/articles/${article?.id}/edit`"
        >
          Edit
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { useArticleStore } from '@/stores/article'
import WikipediaSummary from '@/components/shared/WikipediaSummary.vue'

export default {
  name: 'ArticleView',
  components: {
    WikipediaSummary
  },
  data() {
    return {
      wikipediaSummary: '',
      articleStore: useArticleStore()
    }
  },
  computed: {
    article() {
      return this.articleStore.article
    }
  },
  async mounted() {
    try {
      await this.articleStore.fetchArticle(this.$route.params.id, {include:'category,tags'})
    } catch (error) {
      console.error('Error fetching article:', error)
      this.$router.push('/articles')
    }
  }
}
</script> 