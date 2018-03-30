function slideSwitch() {
    var $active = $('#slideshow a.active');

    if ( $active.length == 0 ) $active = $('#slideshow a:last');

    
    // use this to pull the images in the order they appear in the markup
    var $next =  $active.next().length ? $active.next()
        : $('#slideshow a:first');

    // uncomment the 3 lines below to pull the images in random order
    
    // var $sibs  = $active.siblings();
    // var rndNum = Math.floor(Math.random() * $sibs.length );
    // var $next  = $( $sibs[ rndNum ] );


    $active.addClass('last-active');

    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 1000, function() {
            $active.removeClass('active last-active');
        });
}

$(function() {
	//change 5000 to the number of milliseconds you desire between each roation (5000 = 1 second)
    setInterval( "slideSwitch()", 6000 );
});