export default (str, json = false) => {
  str = json ? JSON.stringify(str) : str
  return window.btoa(str.split('').reverse().join(''))
}
