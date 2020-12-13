export default (route) => {
  console.log(route)
  if (route !== undefined && route.params.email !== undefined) {
    localStorage.setItem('user_email', route.params.email)
    return route.params.email
  } else if (localStorage.getItem('user_email') !== undefined && localStorage.getItem('user_email') !== '') {
    return localStorage.getItem('user_email')
  } else {
    setTimeout(() => {
      if (route.params.email === '' || route.params.email === null) {
        route.push('/')
      }
    }, 100)
    return ''
  }
}
