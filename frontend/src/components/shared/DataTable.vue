<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="items"
      :loading="loading"
      :items-per-page="10"
      class="elevation-1"
      @click:row="onRowClick"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>{{ title }}</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            :to="createRoute"
          >
            New {{ itemName }}
          </v-btn>
        </v-toolbar>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon
          size="small"
          class="me-2"
          @click.stop="$emit('edit', item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          size="small"
          @click.stop="$emit('delete', item)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  name: 'DataTable',
  props: {
    headers: {
      type: Array,
      required: true
    },
    items: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      required: true
    },
    itemName: {
      type: String,
      required: true
    },
    createRoute: {
      type: String,
      required: true
    }
  },
  emits: ['edit', 'delete'],
  methods: {
    onRowClick(event, item) {
      const route = this.createRoute.split('/').slice(0, -1).join('/')
      this.$router.push(`${route}/${item.item.id}`)
    }
  }
}
</script> 