import $ from 'jquery';

const header = () => {

    $(function() {
        //caches a jQuery object containing the header element
        var logo = $('.header__logo');
        $(window).scroll(function() {
          var scroll = $(window).scrollTop();
      
          if (scroll >= 200) {
            logo.addClass('header__logo--show');
          } else {
            logo.removeClass('header__logo--show');
          }

        });

        var nav = $('.header__li');
        $(window).scroll(function() {
          var scroll = $(window).scrollTop();
      
          if (scroll >= 200) {
            nav.addClass('header__li--dark');
          } else {
            nav.removeClass('header__li--dark');
          }

        });

        var bg = $('.header__bg');
        $(window).scroll(function() {
          var scroll = $(window).scrollTop();
      
          if (scroll >= 200) {
            bg.addClass('header__bg--white');
          } else {
            bg.removeClass('header__bg--white');
          }

        });

        var button = $('.button__outline');
        $(window).scroll(function() {
          var scroll = $(window).scrollTop();
      
          if (scroll >= 200) {
            button.addClass('button__outline--dark');
          } else {
            button.removeClass('button__outline--dark');
          }

        });

        
    }); 

};


export default header;

