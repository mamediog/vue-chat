<template>
  <div class="about">
    <span>{{currentUser}}</span>
  </div>
</template>

<script>
import User from '@/api/user'
export default {

  data: () => ({
    user: null,
    currentUser: null
  }),

  async created () {
    this.user = new User()
    this.currentUser = await this.user.getUser('5e9f27416774b64107177222')
  }
}
</script>
