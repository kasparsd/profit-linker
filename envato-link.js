(function() {
	var l, d;
	var anchors = document.getElementsByTagName('a');

	for ( l in anchors ) {
		for ( d in envato_aff_data.domains ) {
			if ( 'undefined' !== typeof anchors[l].href && -1 !== anchors[l].href.indexOf( envato_aff_data.domains[d] ) ) {
				if ( -1 !== anchors[l].href.indexOf('?') ) {
					anchors[l].href += '&ref=' + envato_aff_data.ref;
				} else {
					anchors[l].href += '?ref=' + envato_aff_data.ref;
				}
			}
		}
	}
})();
