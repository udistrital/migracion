
jQuery.fn.dataTableExt.oSort['string-case-asc'] = function(x,y) {
return ((x < y) ? -1 : ((x > y) ? 1 : 0));
};

jQuery.fn.dataTableExt.oSort['string-case-desc'] = function(x,y) {
return ((x < y) ? 1 : ((x > y) ? -1 : 0));
};

jQuery('#mi_buscador input[name="T1"]').liveSearch(
		{
			url: Router.urlForModule('SearchResults') + '&T1='
		});