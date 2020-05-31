<template>
  <div class="chat-home" v-if="currentUser !== null">
    {{ currentUser }}
    <img :src="currentUser.image"/>
  </div>
</template>

<script>
import User from '@/api/user'
export default {
  name: 'Home',
  data: () => ({
    currentUser: 'haha'
  }),
  async created () {
    this.user = new User()
    this.userLogged()
  },
  methods: {
    async userLogged () {
      this.currentUser = await this.user.tryAuthJWT()
    }
  }
}
</script>
