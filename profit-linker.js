(function() {

	var l, d, p;
	var anchors = document.getElementsByTagName('a');

	for ( l in anchors ) {

		for ( d in profit_linker ) {

			if ( 'undefined' !== typeof anchors[l].href && -1 !== anchors[l].href.indexOf( d ) )
				anchors[l].href = add_query_var( anchors[l].href, profit_linker[d], true );

		}

	}

	function add_query_var( url, new_params, replace ) {

		var param_pairs = url.split('?')[1] ? url.split('?')[1].split('&') : [];
		var params = {};

		for ( var pp in param_pairs )
			params[ param_pairs[pp].split('=')[0] ] = param_pairs[pp].split('=')[1] || '';

		for ( var np in new_params ) {

			if ( ! replace && ( np in params ) && ( '' !== params[np] ) )
				continue;

			params[np] = new_params[np];

		}

		url = url.split('?')[0];

		for ( p in params )
			url += ( url.split('?')[1] ? '&' : '?' ) + p + '=' + params[p];
		
		return url;
	}

})();
