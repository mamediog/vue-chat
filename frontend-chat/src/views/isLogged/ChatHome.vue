<template>
  <div class="chat-home">
    <chat-header />
  </div>
</template>

<script>
// HEADER
import ChatHeader from '@/components/ChatHeader'

export default {
  name: 'Home',
  components: {
    ChatHeader
  }
}
</script>
<style lang="sass" scoped>
@import '@/sass/style.sass'
.chat-home
  width: 1300px
  height: 90vh
  top: calc((100% - 90vh) / 2)
  position: fixed
  margin-right: calc((100% - 1300px) / 2)
  margin-left: calc((100% - 1300px) / 2)
  backgound: white
  box-shadow: 0 0 10px 0 rgba(0,0,0,0.2)
  display: flex
  flex-flow: row
  justify-content: flex-start
  align-items: flex-start
  z-index: 1
  border-radius: $radius
</style>
