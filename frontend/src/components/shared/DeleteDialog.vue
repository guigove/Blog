<template>
  <v-dialog v-model="dialog" max-width="500px">
    <v-card>
      <v-card-title class="text-h5">Delete {{ itemName }}</v-card-title>
      <v-card-text>
        Are you sure you want to delete this {{ itemName.toLowerCase() }}?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="grey" variant="text" @click="close">Cancel</v-btn>
        <v-btn color="error" variant="text" @click="confirm">Delete</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DeleteDialog',
  props: {
    modelValue: {
      type: Boolean,
      required: true
    },
    itemName: {
      type: String,
      required: true
    }
  },
  emits: ['update:modelValue', 'confirm'],
  computed: {
    dialog: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    }
  },
  methods: {
    close() {
      this.dialog = false
    },
    confirm() {
      this.$emit('confirm')
      this.close()
    }
  }
}
</script> 
 
 
 