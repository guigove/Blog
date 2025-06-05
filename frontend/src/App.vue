<script>
export default {
  name: 'App'
}
</script>

<template>
  <v-app>
    <v-app-bar>
      <v-app-bar-title>Blog</v-app-bar-title>
      <v-spacer></v-spacer>
      <router-link to="/" class="text-decoration-none">
        <v-btn class="mr-2">Home</v-btn>
      </router-link>
      <router-link to="/articles" class="text-decoration-none">
        <v-btn class="mr-2">Articles</v-btn>
      </router-link>
      <router-link to="/categories" class="text-decoration-none">
        <v-btn class="mr-2">Categories</v-btn>
      </router-link>
      <router-link to="/tags" class="text-decoration-none">
        <v-btn>Tags</v-btn>
      </router-link>
    </v-app-bar>

    <v-main>
      <v-container>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>
