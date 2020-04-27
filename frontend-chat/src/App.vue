<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<style lang="sass">
@import './sass/colors'
body
  background-color: #fcfcfc

#app
  font-family: Avenir, Helvetica, Arial, sans-serif
  -webkit-font-smoothing: antialiased
  -moz-osx-font-smoothing: grayscale
  text-align: center
  color: #2c3e50

.chat-col--2
  display: flex
  flex-flow: row
  align-items: center
  justify-content: space-between
  width: 100%
  input
    width: calc(49% - 30px) !important
    &::placeholder
      color: #d1d1d1
  .chat-input__password
    width: 49%
    input
      width: calc(100% - 30px) !important

.chat-input
  border: none
  width: calc(100% - 30px)
  padding: 0 15px
  height: 40px
  box-shadow: 1px 1px 10px 1px rgba(0,0,0,0.07)
  border: 1px solid $inputBorder
  border-radius: $radius
  color: $inputTextColor

.chat-input__password
  position: relative
  .chat-seepass
    position: absolute
    right: 10px
    top: 17px
    font-size: 11px
    cursor: pointer

.chat-btn
  border: none
  background: $primary
  color: white
  padding: 10px 20px
  cursor: pointer
</style>
