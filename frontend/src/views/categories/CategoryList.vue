<template>
  <div>
    <DataTable
      :headers="headers"
      :items="categories"
      :loading="loading"
      title="Categories"
      item-name="Category"
      create-route="/categories/create"
      @edit="editCategory"
      @delete="confirmDelete"
    />

    <DeleteDialog
      v-model="deleteDialog"
      item-name="Category"
      @confirm="deleteCategory"
    />
  </div>
</template>

<script>
import { useCategoryStore } from '@/stores/category'
import DataTable from '@/components/shared/DataTable.vue'
import DeleteDialog from '@/components/shared/DeleteDialog.vue'

export default {
  name: 'CategoryList',
  components: {
    DataTable,
    DeleteDialog
  },
  data() {
    return {
      headers: [
        { title: 'Name', key: 'name' },
        { title: 'Actions', key: 'actions', sortable: false }
      ],
      deleteDialog: false,
      selectedItem: null,
      categoryStore: useCategoryStore()
    }
  },
  computed: {
    categories() {
      return this.categoryStore.categories
    },
    loading() {
      return this.categoryStore.loading
    }
  },
  methods: {
    editCategory(item) {
      this.$router.push(`/categories/${item.id}/edit`)
    },
    confirmDelete(item) {
      this.selectedItem = item
      this.deleteDialog = true
    },
    async deleteCategory() {
      if (this.selectedItem) {
        await this.categoryStore.deleteCategory(this.selectedItem.id)
        this.deleteDialog = false
        this.selectedItem = null
      }
    }
  },
  mounted() {
    this.categoryStore.fetchCategories()
  }
}
</script> 