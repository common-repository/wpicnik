<?php 

// taken from async-upload.php
require_once('../../../wp-config.php');

if ( empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
	$_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];

unset($current_user);
require_once('../../../wp-admin/admin.php');

if ( !current_user_can('upload_files') )
	wp_die(__('You do not have permission to upload files.'));

$id = (int) $_POST['_imageid'];

$oldimage = wp_get_attachment_metadata($id);

// replace old image
$newimage = file_get_contents($_POST['file']);
$fh = fopen($oldimage['file'], 'w');
fwrite($fh, $newimage);
fclose($fh);

// update db
wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $oldimage['file']));

?>
<script language="javascript" type="text/javascript">
	if (parent.frames.length > 0) {
	    setTimeout( "parent.onPicnikClose()", 10 );
	} else {
	    document.location.replace("<?php echo $_GET['redirect'] ?>");
	}
</script>