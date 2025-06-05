import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/Home.vue')
    },
    {
      path: '/articles',
      name: 'articles',
      component: () => import('@/views/articles/ArticleList.vue')
    },
    {
      path: '/articles/:id',
      name: 'article-view',
      component: () => import('@/views/articles/ArticleView.vue')
    },
    {
      path: '/articles/create',
      name: 'article-create',
      component: () => import('@/views/articles/ArticleCreate.vue')
    },
    {
      path: '/articles/:id/edit',
      name: 'article-edit',
      component: () => import('@/views/articles/ArticleEdit.vue')
    },
    {
      path: '/categories',
      name: 'categories',
      component: () => import('@/views/categories/CategoryList.vue')
    },
    {
      path: '/categories/:id',
      name: 'category-view',
      component: () => import('@/views/categories/CategoryView.vue')
    },
    {
      path: '/categories/create',
      name: 'category-create',
      component: () => import('@/views/categories/CategoryCreate.vue')
    },
    {
      path: '/categories/:id/edit',
      name: 'category-edit',
      component: () => import('@/views/categories/CategoryEdit.vue')
    },
    {
      path: '/tags',
      name: 'tags',
      component: () => import('@/views/tags/TagList.vue')
    },
    {
      path: '/tags/:id',
      name: 'tag-view',
      component: () => import('@/views/tags/TagView.vue')
    },
    {
      path: '/tags/create',
      name: 'tag-create',
      component: () => import('@/views/tags/TagCreate.vue')
    },
    {
      path: '/tags/:id/edit',
      name: 'tag-edit',
      component: () => import('@/views/tags/TagEdit.vue')
    }
  ]
})

export default router 