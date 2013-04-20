function displayRefs() {

	var id = $('#type_id').val();
	$.post('ajax/issue.reflist.php', {t: '<?php echo $type; ?>', tid: id}, function(data) {
		$('#di_refs').html(data);
		$('#di_refs p.ref').on({
			mouseenter: function () {
				$(this).addClass('highlight');
			},
			mouseleave: function () {
				$(this).removeClass('highlight');
			},
			click: function () {
				var id = $(this).find('span.hidden').text();
				$.getJSON('ajax/issue.ref.php', {m: 'r', ref_id: id}, loadRB);
			}
		});
	}, 'html');

}

function adjustRB() {

	var selectedType = $('#rb_ref_type option:selected').val();
	switch (selectedType) {
		case '<?php echo REF_TYPE_BOOK; ?>':
			$('#sp_isbn').show();
			$('#sp_location').show();
			$('#sp_page').show();
			$('#sp_volume').hide();
			$('#sp_number').hide();
			break;
		case '<?php echo REF_TYPE_JOURNAL; ?>':
			$('#sp_isbn').hide();
			$('#sp_location').hide();
			$('#sp_page').show();
			$('#sp_volume').show();
			$('#sp_number').show();
			break;
		case '<?php echo REF_TYPE_WEB; ?>':
		case '<?php echo REF_TYPE_NEWS; ?>':
		default:
			$('#sp_isbn').hide();
			$('#sp_location').hide();
			$('#sp_page').hide();
			$('#sp_volume').hide();
			$('#sp_number').hide();
			break;
	}

}

function loadRB(data) {

	$.each(data, function (ref_key, ref_val) {
		$('#rb_' + ref_key).val(ref_val);
	})
	adjustRB();

}

function postRef(mode) {

	var ref = '';
	if (mode == 'd') {
		ref = 'ref_id=' + $('#rb_ref_id').val();
	} else {
		$('#di_input :input').each(function (i) {
			ref += $(this).attr('name').substr(3) + '=' + encodeURI($(this).val()) + '&';
		})
	}
	var request = $.ajax('ajax/issue.ref.php?m=' + mode, {data: ref, type: 'post', success: loadRB, async: false, dataType: 'json'});
	request.fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
		return false;
	});
	displayRefs();

}

