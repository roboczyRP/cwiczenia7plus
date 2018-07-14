<?php
include_once('baza.php');

if( isSet($_POST['autor']) && isSet($_POST['tytul']) && isSet($_POST['recenzja'])  && isSet($_POST['cat_id']))
{
	if(!isSet($_POST['edit']))
	{
		$insert=$pdo->prepare('INSERT INTO regal (autor,tytul,recenzja,cat_id) VALUES (:autor, :tytul, :recenzja,:cat_id)');
	}
	else
	{
		$id_edit=intval($_POST['edit']);
		$insert=$pdo->prepare('UPDATE regal SET autor=:autor,tytul=:tytul,recenzja=:recenzja,cat_id=:cat_id WHERE id=:id ');
		$insert->bindParam(':id',$id_edit);
	}

	$insert->bindParam(':autor', $_POST['autor']);
	$insert->bindParam(':cat_id', $_POST['cat_id']);
	$insert->bindParam(':tytul', $_POST['tytul']);
	$insert->bindParam(':recenzja', $_POST['recenzja']);
	$insert->execute();

	header('location: loop.php');
	

}
	


if(isSet($_GET['id']))
{
	$id=intval($_GET['id']);
	$edit=$pdo->prepare('SELECT * FROM regal WHERE id=:id');
	$edit->bindParam(':id',$id);
	$edit->execute();
	$edit_result=$edit->fetch();
}
else
{
	$id=0;
	$edit_result=0;
}

?>


<form action="add.php" method="POST">
	

	<input type="text" name="autor" placeholder="Podaj autora"
	<?php if($edit_result<>0) {echo 'value="'.$edit_result['autor'].'"';}?>
	/>
	
	<br></br>
	<input type="text" name="tytul" placeholder="Podaj tytul"
	<?php if($edit_result<>0) {echo 'value="'.$edit_result['tytul'].'"';}?>
	/>
	

	<br></br>
	<textarea name="recenzja" placeholder="Podaj recenzje">
	<?php if($edit_result<>0) {echo $edit_result['recenzja'];}?>
	</textarea>
	<br></br>
	<select name="cat_id">
		<?php

		$cat_data=$pdo->prepare('SELECT * FROM category');
		$cat_data->execute();
		$cat_data_result=$cat_data->fetchAll();

		foreach($cat_data_result as $value)
		{
			if($edit_result<>0 && $value['id']==$edit_result['cat_id'])
			{
			echo '<option selected="selected" value="'.$value['id'].'">'.$value['nazwa'].'</option>';
			}
			else
			{
				echo '<option value="'.$value['id'].'">'.$value['nazwa'].'</option>';
			}
		}
			

		?>
	</select>

	<?php if($edit_result<>0) {echo '<input type="hidden" name="edit" value='.$id.'/>';}?>
	<br></br>
	<input type="submit" value="OK"/>


</form>