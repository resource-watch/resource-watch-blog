$( document ).ready(function() {
    const searchMenu = $('body').find('.menu-search');
    const searchContainer = $('body').find('.c-search');
    const searchInput = $('body').find('.c-search input');
    const searchClose = $('body').find('.c-search .close');
    const header = $('body').find('#header_main .container');
    const content = $('body').find('#main');

    const openSearch = () => {
      searchContainer.toggleClass('open');
      header.toggleClass('hide');
      content.toggleClass('mask');
      searchInput.focus();
    }

    const closeSearch = () => {
      searchContainer.toggleClass('open');
      header.toggleClass('hide');
      content.toggleClass('mask');
    }

    searchMenu.on('click', () => {
        openSearch();
        content.on('click', () => {
          closeSearch();
          content.off('click');
        })
    })

    searchClose.on('click', () => {
        closeSearch();
    })
});
