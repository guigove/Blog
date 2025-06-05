import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

export const useCategoryStore = defineStore('category', {
  state: () => ({
    categories: [],
    category: null,
    loading: false,
    error: null
  }),

  actions: {
    async fetchCategories(params = {}) {
      this.loading = true
      try {
        const response = await axios.get('/categories', { params })
        this.categories = response.data
        this.error = null
      } catch (error) {
        this.error = error.error
      } finally {
        this.loading = false
      }
    },

    async fetchCategory(id, params = {}) {
      this.loading = true
      try {
        const response = await axios.get(`/categories/${id}`, { params })
        this.category = response.data
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async createCategory(category) {
      this.loading = true
      try {
        const response = await axios.post('/categories', category)
        this.categories.push(response.data)
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateCategory(category) {
      this.loading = true
      try {
        const response = await axios.put(`/categories/${category.id}`, category)
        const index = this.categories.findIndex(c => c.id === category.id)
        if (index !== -1) {
          this.categories[index] = response.data
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

    async deleteCategory(id) {
      this.loading = true
      try {
        await axios.delete(`/categories/${id}`)
        this.categories = this.categories.filter(c => c.id !== id)
        this.error = null
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async getNestedCategories(params = {}) {
      this.loading = true
      try {
        const response = await axios.get('/categories/nested', { params })
        this.categories = response.data
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    }
  }
}) 