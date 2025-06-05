<template>
  <div>
    <h1>Edit Tag</h1>
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
              Update
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
  name: 'TagEdit',
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
        await this.tagStore.updateTag({
          id: this.$route.params.id,
          ...this.form
        })
        this.$router.push('/tags')
      } catch (error) {
        console.error('Error updating tag:', error)
      } finally {
        this.loading = false
      }
    }
  },
  async mounted() {
    try {
      const tag = await this.tagStore.fetchTag(this.$route.params.id)
      if (tag) {
        this.form = {
          name: tag.name
        }
      }
    } catch (error) {
      console.error('Error fetching tag:', error)
      this.$router.push('/tags')
    }
  }
}
</script> 