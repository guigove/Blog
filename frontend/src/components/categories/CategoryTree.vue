<template>
  <div :style="{ paddingLeft: `${depth * 16}px` }">
    <v-list-item>
      <v-list-item-title>
        <router-link :to="`/categories/${category.id}`">
          {{ category.name }}
        </router-link>
      </v-list-item-title>
    </v-list-item>

    <div v-if="category.children && category.children.length > 0">
      <category-tree
        v-for="child in category.children"
        :key="child.id"
        :category="child"
        :depth="depth + 1"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'CategoryTree',
  props: {
    category: {
      type: Object,
      required: true
    },
    depth: {
      type: Number,
      default: 0
    }
  }
}
</script>
