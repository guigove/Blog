<template>
  <div>
    <DataTable
      :headers="headers"
      :items="tags"
      :loading="loading"
      title="Tags"
      item-name="Tag"
      create-route="/tags/create"
      @edit="editTag"
      @delete="confirmDelete"
    />

    <DeleteDialog
      v-model="deleteDialog"
      item-name="Tag"
      @confirm="deleteTag"
    />
  </div>
</template>

<script>
import { useTagStore } from '@/stores/tag'
import DataTable from '@/components/shared/DataTable.vue'
import DeleteDialog from '@/components/shared/DeleteDialog.vue'

export default {
  name: 'TagList',
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
      tagStore: useTagStore()
    }
  },
  computed: {
    tags() {
      return this.tagStore.tags
    },
    loading() {
      return this.tagStore.loading
    }
  },
  methods: {
    editTag(item) {
      this.$router.push(`/tags/${item.id}/edit`)
    },
    confirmDelete(item) {
      this.selectedItem = item
      this.deleteDialog = true
    },
    async deleteTag() {
      if (this.selectedItem) {
        await this.tagStore.deleteTag(this.selectedItem.id)
        this.deleteDialog = false
        this.selectedItem = null
      }
    }
  },
  mounted() {
    this.tagStore.fetchTags()
  }
}
</script> 