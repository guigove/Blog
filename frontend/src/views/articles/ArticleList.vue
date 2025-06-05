<template>
  <div>
    <DataTable
      :headers="headers"
      :items="articles"
      :loading="loading"
      title="Articles"
      item-name="Article"
      create-route="/articles/create"
      @edit="editArticle"
      @delete="confirmDelete"
    />

    <DeleteDialog
      v-model="deleteDialog"
      item-name="Article"
      @confirm="deleteArticle"
    />
  </div>
</template>

<script>
import { useArticleStore } from '@/stores/article'
import DataTable from '@/components/shared/DataTable.vue'
import DeleteDialog from '@/components/shared/DeleteDialog.vue'

export default {
  name: 'ArticleList',
  components: {
    DataTable,
    DeleteDialog
  },
  data() {
    return {
      headers: [
        { title: 'Title', key: 'title' },
        { title: 'Category', key: 'category.name' },
        { title: 'Actions', key: 'actions', sortable: false }
      ],
      deleteDialog: false,
      itemToDelete: null,
      articleStore: useArticleStore()
    }
  },
  computed: {
    articles() {
      return this.articleStore.articles
    },
    loading() {
      return this.articleStore.loading
    }
  },
  methods: {
    async fetchArticles() {
      await this.articleStore.fetchArticles({include:'category'})
    },
    editArticle(item) {
      this.$router.push(`/articles/${item.id}/edit`)
    },
    confirmDelete(item) {
      this.itemToDelete = item
      this.deleteDialog = true
    },
    async deleteArticle() {
      if (this.itemToDelete) {
        await this.articleStore.deleteArticle(this.itemToDelete.id)
        this.itemToDelete = null
      }
    }
  },
  mounted() {
    this.fetchArticles()
  }
}
</script> 