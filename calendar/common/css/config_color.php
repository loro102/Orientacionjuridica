<?
$color = array("cc3333","dd4477","994499","6633cc","336699","3366cc","22aa99","329262","109618","66aa00","aaaa11","d6ae00","ee8800","dd5511","a87070","8c6d8c","627487","7083a8","5c8d87","898951","b08b59");
$color_name = array("Red", "Pink", "Violet", "Lavender", "Navy", "Blue", "Mixed Green", "Dark Green", "Green", "Fresh Green", "Light Green", "Light Green","Orange","Dark Orange","Dark Purple","Purple","Mixed Purple","Blue Purple","Light Blue","Light Brown","Brown");

function optionColor($exist_color=''){
	global $color, $color_name;
		echo '<select name="color" id="color">';
		for($i=0;$i<count($color);$i++){
			$selected = ($exist_color == $color[$i]) ? ' selected' : ''; 
	        echo '<option value="'.$color[$i].'" style="background:#'.$color[$i].'"'.$selected.'>'.$color_name[$i].' ['.$color[$i].']'.'</option>';
		}
		echo '</select>';
} // end of function
?>