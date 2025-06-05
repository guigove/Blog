<template>
  <div>
    <h1>Create Tag</h1>
    <v-card class="mt-4">
      <v-card-text>
        <v-form @submit.prevent="submit" ref="form">
          <v-text-field
            v-model="form.name"
            label="Name"
            required
            :rules="rules.name"
          ></v-text-field>

          <div class="d-flex justify-end mt-4">
            <v-btn
              color="grey"
              class="mr-2"
              to="/tags"
            >
              Cancel
            </v-btn>
            <v-btn
              color="primary"
              type="submit"
              :loading="loading"
            >
              Create
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { useTagStore } from '@/stores/tag'

export default {
  name: 'TagCreate',
  data() {
    return {
      form: {
        name: ''
      },
      rules: {
        name: [
          v => !!v || 'Name is required'
        ]
      },
      loading: false,
      tagStore: useTagStore()
    }
  },
  methods: {
    async submit() {
      const { valid } = await this.$refs.form.validate()
      
      if (!valid) {
        return
      }

      this.loading = true
      try {
        await this.tagStore.createTag(this.form)
        this.$router.push('/tags')
      } catch (error) {
        console.error('Error creating tag:', error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script> 