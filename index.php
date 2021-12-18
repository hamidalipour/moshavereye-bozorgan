<?php
$answers = file("messages.txt", FILE_IGNORE_NEW_LINES);
$name_json = file_get_contents("people.json");
$names = json_decode($name_json);
$names_copy = [];
foreach($names as $en_value => $fa_value){
	$new = array_push($names_copy, $en_value);
}
$question = '';
$msg = 'سوال خود را بپرس';
$en_name = $names_copy[array_rand($names_copy,1)];
$fa_name = $names->$en_name;
if($_POST['person']){
	$question_array = explode(' ', $_POST['question']);
	$question_letters = str_split($_POST['question']);
	$size = count($question_letters);
	if($question_array[0] == 'آیا' && ($question_letters[$size-1] == '?' || preg_match('/(؟)/', $_POST['question']))){
		$en_name = $_POST['person'];
		$fa_name = $names->$en_name;
		$question = $_POST['question'];
		$hash_que = crc32($_POST['question']);
		$hash_name = crc32($_POST['person']);
		$hash_final = ($hash_name + $hash_que)%16;
		$msg = $answers[$hash_final];
	}
	else{
		$en_name = $_POST['person'];
		$fa_name = $names->$en_name;
		$question = $_POST['question'];
		$msg = "سوال درستی پرسیده نشده";
	}
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
	<div id="title">
	<?php
    if($_POST['person']){
    	echo "<span id='label'>پرسش:</span>";
        echo "<span id='question'>$question</span>";
    }?>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post" action="index.php">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                
                foreach($names as $en_value => $fa_value)
                	{
                	if ($en_value == $en_name){
                		echo "<option value=\"$en_value\" selected>$fa_value</option>";
                	}
                	else{
                		echo "<option value=\"$en_value\">$fa_value</option>";
                	}
                	
                	}
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>