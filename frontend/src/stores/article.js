import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

export const useArticleStore = defineStore('article', {
  state: () => ({
    articles: [],
    article: null,
    loading: false,
    error: null
  }),

  actions: {
    async fetchArticles(params = {}) {
      this.loading = true
      try {
        const response = await axios.get('/articles', { params })
        this.articles = response.data
        this.error = null
      } catch (error) {
        this.error = error.error
      } finally {
        this.loading = false
      }
    },

    async fetchArticle(id, params = {}) {
      this.loading = true
      try {
        const response = await axios.get(`/articles/${id}`, { params })
        this.article = response.data
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async createArticle(article) {
      this.loading = true
      try {
        const response = await axios.post('/articles', article)
        this.articles.push(response.data)
        this.error = null
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateArticle(article) {
      this.loading = true
      try {
        const response = await axios.put(`/articles/${article.id}`, article)
        const index = this.articles.findIndex(a => a.id === article.id)
        if (index !== -1) {
          this.articles[index] = response.data
        }
        return response.data
      } catch (error) {
        this.error = error.error
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteArticle(id) {
      this.loading = true
      try {
        await axios.delete(`/articles/${id}`)
        this.articles = this.articles.filter(a => a.id !== id)
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