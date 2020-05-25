<template>
  <div class="about">
    <span v-for="(user, index) in users" :key="index">
      <p>{{ user.name }}, {{ user.email }}, {{ user.phone }}</p>
    </span>
  </div>
</template>

<script>
import User from '@/api/user'
export default {

  data: () => ({
    users: null
  }),

  async created () {
    console.log('TELA ABOUT')
    this.user = new User()

    this.users = await this.user.allUsers()
  }
}
</script>
