import $ from 'jquery';

const hamburger = () => {
    $('.hamburger, .off-canvas a').click(function(){
        $('.hamburger').toggleClass('is-active');   
    });
};

export default hamburger;