<?php
// $post = get_the_ID();
$field_name = 'your_field';
$field_value = get_post_meta(get_the_ID(), $field_name, true);
wp_nonce_field('study_nonce', 'study_nonce');
?>
<table class="form-table">
    <tr>
        <th> <label for="<?php echo $field_name; ?>">PDF</label></th>
        <td>
            <input id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" type="file" value="<?php echo esc_attr($field_value); ?>" />
        </td>
    </tr>
</table>
