import axios from 'axios'
import decode from '@/utils/decode.native'

export default () => {
  const token = localStorage.getItem('user_token')

  return axios.create({
    baseURL: process.env.VUE_APP_API,
    headers: {
      'Content-Type': 'application/json',
      ...(token !== null ? { Authorization: decode(token) } : {})
    },
    timeout: process.env.VUE_APP_TIMEOUT
  })
}
