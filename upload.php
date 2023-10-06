<?php
$imageData = '';
if (isset($_FILES['file']['name'][0])) {
  foreach ($_FILES['file']['name'] as $keys => $values) {
    $fileName = uniqid() . '_' . $_FILES['file']['name'][$keys];
    if (move_uploaded_file($_FILES['file']['tmp_name'][$keys], 'uploads/' . $fileName)) {
      $imageData .= '<div class="div_thumbnail"><img src="uploads/' . $fileName . '" class="thumbnail" /> <p link ="uploads/' . $fileName . '" class="remove_pict" onclick="remove_pic(this)">X Remover imagem</p></div>';
    }
  }
}
echo $imageData;