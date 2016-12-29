<?php
require_once("dep/server/functions.php");
echo mCrypt('12345').'<br/>';
echo $_SESSION['sk']
?>
<script>
function hey(h){
	alert(h.checked);
}
</script>
<body>
<p><br>
  <input type="checkbox" id="id" value="somevalue" checked onChange="hey(this)">
</p>
<p>
  <label>
    <select name="select" size="4" id="select">
    <option>name</option>
    <option>name</option>
    <option>name</option>
    </select>
  </label>
  <optgroup><option>name</option>
    <option>name</option>
    <option>name</option></optgroup>
</p>
</body>