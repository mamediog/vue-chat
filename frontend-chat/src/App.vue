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

.chat-input
  border: none
  box-shadow: 1px 1px 10px 1px rgba(0,0,0,0.1)

.chat-btn
  border: none
  background: $primary
  color: white
  padding: 10px 20px
  cursor: pointer
</style>
