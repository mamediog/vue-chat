<template>
  <section class="chat-notify" id="chat-notify">
    <span>{{ msg }}</span>
  </section>
</template>

<script>
export default {
  data: () => ({
    msg: 'Alguma coisa'
  }),
  created () {
    this.$bus.$on('show-notify', this.showNotify)
  },
  destroyed () {
    this.$bus.$off('show-notify', this.showNotify)
  },
  methods: {
    showNotify (msg) {
      this.msg = msg

      var notify = document.getElementById('chat-notify')

      if (notify) {
        notify.classList.add('chat-notify--show')

        setTimeout(() => {
          notify.classList.remove('chat-notify--show')
        }, 3000)
      }
    }
  }
}
</script>

<style lang="sass">
@import '@/sass/style.sass'

.chat-notify
  display: flex
  justify-content: center
  align-items: center
  padding: 0px 20px
  background: $primaryColor
  color: white
  position: absolute
  height: 40px
  bottom: 20px
  left: 20px
  border-radius: $radius
  box-shadow: 0 0 10px 0 rgba(0,0,0,0.2)
  visibility: hidden
  transition: all .3s
  bottom: 0
  opacity: 0
  span
    color: white
    font-size: 14px

.chat-notify--show
  visibility: visible !important
  transition: all .3s
  bottom: 20px !important
  opacity: 1 !important
</style>
