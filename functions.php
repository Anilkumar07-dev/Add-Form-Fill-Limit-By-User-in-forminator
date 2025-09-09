//add in functions.php file worpress

// form data limit
add_filter( 'forminator_custom_form_submit_errors', function( $submit_errors, $form_id, $field_data_array ){
	$your_list_forms = [31951]; //form id
	if( empty( $submit_errors ) && in_array( $form_id, $your_list_forms ) ){
		$your_unique_field_name = 'email-1'; // email field id
		$your_error_msg = 'The email already sumitted';
		foreach( $field_data_array as $field ){
			if( $field['name'] === $your_unique_field_name ){
				global $wpdb;
				$table_meta = $wpdb->prefix . 'frmt_form_entry_meta';
				$table_entry = $wpdb->prefix . 'frmt_form_entry';
				if( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $table_meta as m LEFT JOIN $table_entry as e ON m.entry_id = e.entry_id WHERE m.meta_key = %s AND m.meta_value=%s AND e.form_id = %d LIMIT 1;", $field['name'], $field['value'], $form_id ) ) ){
					$submit_errors[][$your_unique_field_name] = $your_error_msg;
				}
				break;
			}
		}
	}
	return $submit_errors;
}, 10, 3);
