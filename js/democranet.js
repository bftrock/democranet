// site-wide javascript 

$(document).ready(function() {
	$('#bu_issues').on('click', function () {
		window.location.assign('issbrws.php');
	})
	$('#in_search').keyup(function (event) {
		if(event.keyCode == 13){
			search();
		}
	});
	$('#search_help').dialog({autoOpen: false});
	$('img.ec').click(function () {
		var id;
		id = $(this).attr('id');
		if ($(this).attr('src') == 'img/collapse.png') {
			$(this).attr('src', 'img/expand.png');
			$('#di_' + id).slideUp();
		} else {
			$(this).attr('src', 'img/collapse.png');
			$('#di_' + id).slideDown();
		}
	});
});

function search()
{
	$.post('ajax/start.search.php', {s: $('#in_search').val()}, function (data) {
		$('#di_results').html(data);
	})
}

function help()
{
	$('#search_help').dialog({width: 500});
	$('#search_help').dialog({modal: true});
	$('#search_help').dialog('open');
}
