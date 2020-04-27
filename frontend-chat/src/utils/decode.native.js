export default (str, json = false) => {
  str = window.atob(str).split('').reverse().join('')

  return json ? JSON.parse(str) : str
}
