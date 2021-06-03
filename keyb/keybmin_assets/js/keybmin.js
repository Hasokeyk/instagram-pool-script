jQuery(function($){

	try{
		$('.multiSelectBox').multiSelect({
			selectAll: true
		});
	}catch(e){

	}

	try{
		$('.icon-selector').iconpicker().on('change', function(e){
			$('.iconClass').val(e.icon);
		});
	}catch(e){
		console.log(222)
	}

	//PAGE CREAT
	$('.pageName').on('keyup',function(){
		var pageName = $(this).val();

		var selectPageShortcode = $('.parentPageID option:selected').data('pageshortcode')+'-';
		var shortCode = (selectPageShortcode+toSeoUrl(pageName)).trimLeft('-');
		$('.pageLink').val('?page='+shortCode);
		$('.pageShortcode').val(shortCode);

	})

	$('.parentPageID').on('change',function(){
		var pageName = toSeoUrl($('.pageName').val());
		var selectPageShortcode = $(' option:selected',this).data('pageshortcode');
		if(selectPageShortcode == 0){
			selectPageShortcode = pageName;
		}else{
			selectPageShortcode = selectPageShortcode+'-'+pageName;
		}
		$('.pageLink').val('?page='+selectPageShortcode);
		$('.pageShortcode').val(selectPageShortcode);
	})
	//PAGE CREAT

	//DEL BUTTON
	$('.del-btn').on('click',function(){

		var src = $(this).attr('href');
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				window.location = src;
			}
		})

		return false;

	})
	//DEL BUTTON

	//INSTALL DB TYPE
	$('.dbtype').on('change',function(){
		var type = $(this).val();

		var port = '3360';
		if(type == 'mysqli'){
			port = '3306';
		}else if(type == 'postgresql'){
			port = '5432';
		}
		$('.port').val(port);

	})
	//INSTALL DB TYPE

})

function toSeoUrl(text) {
	var trMap = {
        'çÇ':'c',
        'ğĞ':'g',
        'şŞ':'s',
        'üÜ':'u',
        'ıİ':'i',
        'öÖ':'o'
    };
    for(var key in trMap) {
        text = text.replace(new RegExp('['+key+']','g'), trMap[key]);
    }
    return  text.replace(/[^-a-zA-Z0-9\s]+/ig, '') // remove non-alphanumeric chars
                .replace(/\s/gi, "-") // convert spaces to dashes
                .replace(/[-]+/gi, "-") // trim repeated dashes
                .toLowerCase();
}

String.prototype.trimLeft = function(charlist) {
	if (charlist === undefined)
		charlist = "\s";

	return this.replace(new RegExp("^[" + charlist + "]+"), "");
};

String.prototype.trimRight = function(charlist) {
	if (charlist === undefined)
		charlist = "\s";

	return this.replace(new RegExp("[" + charlist + "]+$"), "");
};