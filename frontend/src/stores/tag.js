import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

export const useTagStore = defineStore('tag', {
  state: () => ({
    tags: [],
    tag: null,
    loading: false,
    error: null
  }),

  actions: {
    async fetchTags(params = {}) {
      this.loading = true
      try {
        const response = await axios.get('/tags', { params })
        this.tags = response.data
        this.error = null
      } catch (error) {
        this.error = error.error
      } finally {
        this.loading = false
      }
    },

    async fetchTag(id, params = {}) {
      this.loading = true
      try {
        const response = await axios.get(`/tags/${id}`, { params })
        this.tag = response.data
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async createTag(tag) {
      this.loading = true
      try {
        const response = await axios.post('/tags', tag)
        this.tags.push(response.data)
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateTag(tag) {
      this.loading = true
      try {
        const response = await axios.put(`/tags/${tag.id}`, tag)
        const index = this.tags.findIndex(t => t.id === id)
        if (index !== -1) {
          this.tags[index] = response.data
        }
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteTag(id) {
      this.loading = true
      try {
        await axios.delete(`/tags/${id}`)
        this.tags = this.tags.filter(t => t.id !== id)
        this.error = null
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    }
  }
}) 