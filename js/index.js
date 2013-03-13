$(document).ready(function() {
	$('#bu_search').on('click', function() {
		$.post('ajax/index.search.php', {s: $('#in_search').val()}, function (data) {
			$('#di_results').html(data);
		})
	})
	$('#bu_browse').on('click', function () {
		window.location.assign('issbrws.php');
	})
	$('#in_search').keyup(function (event) {
		if(event.keyCode == 13){
			$("#bu_search").click();
		}
	});
	$('#search_help').dialog({autoOpen: false});
	$('#bu_search_help').click(function () {
		$('#search_help').dialog({width: 500});
		$('#search_help').dialog({modal: true});
	    $('#search_help').dialog('open');
	});
});
