$(document).ready(function(){new Swiper(".swiper-container",{pagination:".swiper-pagination",nextButton:".swiper-button-next",prevButton:".swiper-button-prev",paginationClickable:!0,spaceBetween:20,centeredSlides:!0,autoplay:6e4,loop:!0,autoplayDisableOnInteraction:!1})}),$(document).ready(function(){$(window).scroll(function(){$(document).scrollTop()>100?$(".navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse").addClass("scroll"):$(".navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse").removeClass("scroll")}),$(window).scroll(function(){$(document).scrollTop()>80?$(".navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse").addClass("scroll-midd"):$(".navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse").removeClass("scroll-midd")}),$(window).scroll(function(){$(document).scrollTop()>50?$(".navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse, div.navbar-header.header").addClass("scroll-small"):$(".navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse, div.navbar-header.header").removeClass("scroll-small")})});