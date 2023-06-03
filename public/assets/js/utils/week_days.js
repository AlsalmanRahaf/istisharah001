$( document ).ready( function(){
    let checkboxes = $( '.days-checkbox:checkbox' ),
        numberOfDays = checkboxes.length,
        span       = $( '#allChecked' );
    let allDaysText = $(".days-week-box").data("alldays-text");


    checkboxes.on( 'change', function(){
        let checked = checkboxes.filter( ':checked' ),
            daysNumberSelected = checked.length;

        if ( daysNumberSelected === numberOfDays )
            span.text( `( ${allDaysText} )` );
        else if(daysNumberSelected === 0){
            span.text('');
        } else {
            var days = jQuery.map( checked, function( n, i ){ return $(n).next().text(); } );
            span.text( '( ' + days.join(', ') + ' )' );
        }
    } );
});
