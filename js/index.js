$(document).ready(function() {
	$('#di_issfol').load('ajax/index.issues.php');
	$('#im_search').on({
		mouseover: function() {
			$('#im_search').attr('src', 'img/search1.png');
		},
		mouseleave: function() {
			$('#im_search').attr('src', 'img/search.png');
		},
		click: function() {
			$.post('ajax/index.search.php', {s: $('#in_search').val()}, function (data) {
				$('#di_results').html(data);
			})
		}
	})
	$('#im_browse').on({
		mouseover: function() {
			$('#im_browse').attr('src', 'img/browse1.png');
		},
		mouseleave: function() {
			$('#im_browse').attr('src', 'img/browse.png');
		}
	})
	$('#im_find_candidates').on({
		mouseover: function() {
			$('#im_find_candidates').attr('src', 'img/find_candidates1.png');
		},
		mouseleave: function() {
			$('#im_find_candidates').attr('src', 'img/find_candidates.png');
		}
	})
	$('#im_find_groups').on({
		mouseover: function() {
			$('#im_find_groups').attr('src', 'img/find_groups1.png');
		},
		mouseleave: function() {
			$('#im_find_groups').attr('src', 'img/find_groups.png');
		}
	})
	$('#in_search').keyup(function (event) {
		if(event.keyCode == 13){
			$("#im_search").click();
		}
	});
	$('#search_help').dialog({ autoOpen: false });
	$('#im_search_help').click(function () {
		$('#search_help').dialog({width: 500});
		$('#search_help').dialog({modal: true});
	    $('#search_help').dialog('open');
	});
})
