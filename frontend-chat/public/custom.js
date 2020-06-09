window.custom = {
  changeActiveChat: function (elem) {
    const elements = document.getElementById('chat-header__conversation').children;
    for (var i = 0; i < elements.length; i++) {
      if (elements[i]) { elements[i].classList.remove('chat-header__conversation-friend--active'); }
    }

    if (elem) { elem.classList.add('chat-header__conversation-friend--active'); }
  },

  openAndHideItem: function (elem, classActive, childElem = null, parentElem = false) {

    if (childElem !== null) {
      elem = elem.children[childElem]
    } else if (parentElem) {
      elem = elem.parentElement
    }

    if (!elem.classList.contains(classActive)) {
      elem.classList.add(classActive);
    }else {
      elem.classList.remove(classActive);
    }
  }
}
