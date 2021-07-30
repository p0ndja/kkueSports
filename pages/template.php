<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <div class="container mb-3">
    <textarea>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nec luctus tortor. Suspendisse aliquet, justo faucibus luctus imperdiet, enim massa malesuada eros, ut semper urna nibh ac libero. Maecenas vitae blandit turpis, ullamcorper suscipit nulla. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus dapibus dapibus condimentum. Donec hendrerit fringilla iaculis. Vivamus vestibulum orci a mollis mattis. Phasellus quis libero lacinia, vehicula urna vel, sagittis nulla. Nam viverra semper est, vel viverra enim mattis pharetra. Mauris venenatis, purus quis ultricies volutpat, augue lorem tincidunt justo, ut dictum lorem purus id diam. Nam ultrices, metus quis dictum tempor, metus felis congue turpis, id dignissim leo risus quis risus. Mauris in diam erat. Phasellus dapibus est sit amet tristique finibus.

  </textarea>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
   });
  </script>
    </div>
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
</body>

</html>