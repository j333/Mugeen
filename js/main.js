$(document).ready(function(){
	$('.carousel').carousel({
	  	interval: 3000
	});
    $("#zoom").elevateZoom({
        zoomType: "lens",
        lensShape: "round",
        lensSize: 250,
        gallery: "gallery",
        galleryActiveClass: "active",
    });
    if ($(window).width() < 800) {
        $("#zoom").elevateZoom({
            zoomType: "inner",
        });
    }
})