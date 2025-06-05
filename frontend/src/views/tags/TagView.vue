<template>
  <div>
    <h1>Tag Details</h1>
    <v-card class="mt-4">
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <h2 class="text-h5 mb-2">{{ tag?.name }}</h2>
            <div v-if="tag?.articles?.length" class="mt-6">
              <h3 class="text-h6 mb-2">Articles with this Tag</h3>
              <v-list>
                <v-list-item
                  v-for="article in tag.articles"
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
          to="/tags"
        >
          Back
        </v-btn>
        <v-btn
          color="primary"
          :to="`/tags/${tag?.id}/edit`"
        >
          Edit
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { useTagStore } from '@/stores/tag'

export default {
  name: 'TagView',
  data() {
    return {
      tagStore: useTagStore()
    }
  },
  computed: {
    tag() {
      return this.tagStore.tag
    }
  },
  async mounted() {
    try {
      await this.tagStore.fetchTag(this.$route.params.id, {include:'articles'})
    } catch (error) {
      console.error('Error fetching tag:', error)
      this.$router.push('/tags')
    }
  }
}
</script> 