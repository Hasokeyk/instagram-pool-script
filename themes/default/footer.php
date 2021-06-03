</div>
<?php
    global $keybmin;
	$keybmin->js_minify($globalJs??null, 'global');
	$keybmin->js_minify($jses??null, $fileName);
?>

</body>
</html>