function brands_list_to_select( list , selectid ) {
	var data = jQuery.parseJSON( list );
	var html = '';
	for (var i = 0; i <= data.length - 1; i++) {
		var brand = data[i];
		html += '<option value="' + brand.value + '" data-url="' + brand.url + '">' + brand.name + ' (' + brand.url + ')</option>' + "\r\n";
	};
	jQuery("#" + selectid).html(html);
	jQuery("#" + selectid).trigger('chosen:updated');
}