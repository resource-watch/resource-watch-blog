$( document ).ready(function() {
    const header = $('body').find('#header');
    const headerContainer = $('body').find('#header_main .container');
    const content = $('body').find('#main');

    const searchTemplate = '<div class="c-search"><form action="/search"><img class="search-icon" src="/wp-content/uploads/2018/02/search.svg" /><input placeholder="Search" name="term"/><div class="close"><img class="close-icon" src="/wp-content/themes/resource-watch/assets/cross.svg" /></div></form></div>';
    const initSearch = function() {
      header.append(searchTemplate);
    }
    initSearch();
    const searchMenu = $('body').find('.menu-search');
    const searchContainer = $('body').find('.c-search');
    const searchInput = $('body').find('.c-search input');
    const searchClose = $('body').find('.c-search .close');

    const openSearch = function() {
      searchContainer.toggleClass('open');
      headerContainer.toggleClass('hide');
      content.toggleClass('mask');
      searchInput.focus();
    }

    const closeSearch = function() {
      searchContainer.toggleClass('open');
      headerContainer.toggleClass('hide');
      content.toggleClass('mask');
    }

    searchMenu.on('click', function() {
        openSearch();
        content.on('click', function() {
          closeSearch();
          content.off('click');
        })
    })

    searchClose.on('click', function() {
        closeSearch();
    })

    // append user icon if logged in
    const setUserMenu = function(data) {
      const nav = $('body').find('.main_menu');
      const navLoggedIn = $('body').find('.logged-in-menu .avia-menu-text').first();
      const userInfo = data.Photo ?
        '<div class="user-info" style="background-image: url(' + data.Photo + ')"></div>'
        :
        '<div class="user-info">' + data.name && data.name.charAt(0) + '</div>';
      nav.addClass('rw-logged-in');
      if (data.role === 'ADMIN') {
        nav.addClass('is-admin');
      }
      navLoggedIn.html(userInfo);
      nav.addClass('rw-logged-out');
    }

    // fetch user data
    $.ajax({
      type: 'GET',
      url: window.location.origin + '/auth/user',
      dataType: 'json',
      username: 'resourcewatch',
      password: 'password123',
      success: function(data) {
        setUserMenu(data);
      },
      error: function(msg) {
        console.error(msg);
      }
    })
});

