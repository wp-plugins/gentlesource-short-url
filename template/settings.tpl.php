
<form method="post" action="./options-general.php?page=gentlesource_shorturl-settings" id="gentlesource_shorturl_settings" style="margin-top:2em;margin-left:1em;">

<table class="form-table">


  <tr valign="top">
    <th scope="row">
        <label for="ApiUrl" style="font-weight:bold;"><?php echo __('Short URL Service API URL') ?></label><br />
        <?php echo __('Enter the short URL service\'s API URL in this field. Please make sure that you put a %s at the place where the long URL is supposed to be.') ?><br /><br />
        <?php echo __('Alternatively, you can run your on short URL service. All you have to do is install the <a href="http://www.gentlesource.com/short-url-script/" target="_blank">Short URL Script</a> from <a href="http://www.gentlesource.com/" target="_blank">GentleSource</a>.') ?>
    </th>
  </tr>
  <tr>
    <td>
        <input type="text" name="ApiUrl" value="<?php echo $opt['ApiUrl'] ?>" style="width:550px;" /><br />
        <p><?php echo __('Following API URLs are available') ?></p>
        <pre>
http://unrelo.com/?api=smile&u=%s
http://bit.ly/api?url=%s
http://u.nu/unu-api-simple?url=%s
http://is.gd/api.php?longurl=%s
http://tinyurl.com/api-create.php?url=%s
        </pre>
    </td>
  <tr>

  <tr valign="top">
    <th scope="row">
        <label for="Display" style="font-weight:bold;"><?php echo __('Display Short URL') ?></label>
    </th>
  </tr>
  <tr>
    <td>
        <input type="radio" name="Display" value="Y" <?php echo $opt['Display'] == 'Y' ? 'checked="checked"' : '' ?> /> <?php echo __('Yes') ?>
        <input type="radio" name="Display" value="N" <?php echo $opt['Display'] == 'N' ? 'checked="checked"' : '' ?> /> <?php echo __('No') ?>
    </td>
  <tr>

  <tr valign="top">
    <th scope="row">
        <label for="TwitterLink" style="font-weight:bold;"><?php echo __('Display Twitter Link') ?></label>
    </th>
  </tr>
  <tr>
    <td>
        <input type="radio" name="TwitterLink" value="Y" <?php echo $opt['TwitterLink'] == 'Y' ? 'checked="checked"' : '' ?> /> <?php echo __('Yes') ?>
        <input type="radio" name="TwitterLink" value="N" <?php echo $opt['TwitterLink'] == 'N' ? 'checked="checked"' : '' ?> /> <?php echo __('No') ?>
    </td>
  <tr>


  <tr valign="top">
    <th scope="row">
        <input type="submit" name="save" value="<?php echo __('Save') ?>" />
    </th>
    <td>

    </td>
  <tr>

</table>


</form>
