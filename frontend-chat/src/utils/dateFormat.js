import Vue from 'vue'

Vue.mixin({
  methods: {
    $$dateFormatBR (date) {
      return date.toLocaleString('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      }).replace(/\//g, '/')
    }
  }
})
