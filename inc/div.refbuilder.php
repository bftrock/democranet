<div id="di_RB">
	<div id="di_input">
		<span id="sp_ref_id"><input type="hidden" name="rb_ref_id" id="rb_ref_id"/></span>
		<span id="sp_typ_id"><input type="hidden" name="rb_type_id" id="rb_type_id" value="<?php echo $type_id; ?>"/></span>
		<span id="sp_type"><input type="hidden" name="rb_type" id="rb_type" value="i"/></span>
		<span id="sp_ref_type">
			<label for="rb_ref_type">Reference Type:</label>
			<select name="rb_ref_type" id="rb_ref_type">
				<option value="<?php echo REF_TYPE_WEB; ?>">Web</option>
				<option value="<?php echo REF_TYPE_BOOK; ?>">Book</option>
				<option value="<?php echo REF_TYPE_NEWS; ?>">News</option>
				<option value="<?php echo REF_TYPE_JOURNAL; ?>">Journal</option>
			</select>
		</span>
		<span id="sp_title">
			<label for="rb_title">Title:</label><input type="text" name="rb_title" id="rb_title" size="45" /><br>
		</span>
		<span id="sp_author">
			<label for="rb_author">Author:</label><input type="text" name="rb_author" id="rb_author" />
		</span>
		<span id="sp_publisher">
			<label for="rb_publisher">Publisher:</label><input type="text" name="rb_publisher" id="rb_publisher" />
		</span>
		<span id="sp_date">
			<label for="rb_date">Date:</label><input type="text" name="rb_date" id="rb_date" size="15"/><br>
		</span>
		<span id="sp_url">
			<label for="rb_url">URL:</label><input type="text" name="rb_url" id="rb_url" size="50" />
		</span>
		<span id="sp_isbn">
			<label for="rb_isbn">ISBN:</label><input type="text" name="rb_isbn" id="rb_isbn" size="15" /><br>
		</span>
		<span id="sp_location">
			<label for="rb_location">Location:</label><input type="text" name="rb_location" id="rb_location" />
		</span>
		<span id="sp_page">
			<label for="rb_page">Page:</label><input type="text" name="rb_page" id="rb_page" size="15" /><br>
		</span>
		<span id="sp_volume">
			<label for="rb_volume">Volume:</label><input type="text" name="rb_volume" id="rb_volume" />
		</span>
		<span id="sp_number">
			<label for="rb_number">Number:</label><input type="text" name="rb_number" id="rb_number" />
		</span>
	</div>
	<div id="di_buttons">
		<a class="btn" name="bu_save" id="bu_save">Save</a>
		<a class="btn" name="bu_add" id="bu_add">Add</a>
		<a class="btn" name="bu_delete" id="bu_delete">Delete</a>
	</div>
	<div id="di_refs"></div>
	<div id="ref_help" title="Reference Help">To add a new reference, fill in the form 
		and click Add. To modify a reference, select it by hovering over it with your
		mouse and clicking. Make any edits with the form, and click Save. To delete a 
		reference, select it and click Delete.
	</div>
</div>
