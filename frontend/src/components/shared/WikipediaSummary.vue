<template>
  <div>
    <v-textarea
      :model-value="modelValue"
      label="Wikipedia Summary"
      readonly
      :loading="loading"
      :error-messages="error"
    ></v-textarea>

    <div class="d-flex justify-end mt-2 mb-4">
      <v-btn
        color="primary"
        class="mr-2"
        :loading="loading"
        @click="search"
      >
        Search Wikipedia
      </v-btn>
      <v-btn
        color="error"
        @click="clear"
      >
        Clear
      </v-btn>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WikipediaSummary',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      error: ''
    }
  },
  methods: {
    async search() {
      if (!this.title) {
        return
      }
      this.loading = true
      this.error = ''
      try {
        const response = await this.$axios.get(`/wikipedia/${encodeURIComponent(this.title)}`)
        this.$emit('update:modelValue', response.data)
      } catch (error) {
        console.error('Error fetching Wikipedia summary:', error)
        this.error = error?.error || 'Failed to fetch Wikipedia summary'
      } finally {
        this.loading = false
      }
    },
    async clear() {
      if (!this.title) {
        return
      }
      try {
        await this.$axios.delete(`/wikipedia/${encodeURIComponent(this.title)}`)
        this.$emit('update:modelValue', '')
        this.error = ''
      } catch (error) {
        console.error('Error clearing Wikipedia summary:', error)
        this.error = error?.erro || 'Failed to clear Wikipedia summary'
      }
    }
  }
}
</script> 