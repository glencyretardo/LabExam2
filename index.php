<?php
session_start();

if (!isset($_SESSION['countries'])) {
    $_SESSION['countries'] = array();
}

if (!isset($_SESSION['checked'])) {
    $_SESSION['checked'] = array();
}
 

// process when you add a country to the form
if (isset($_POST['submit'])) {
    
    $location = $_POST['country'];
    
    if (!empty($location)) {
        
        //convert to lower case to check if nag exist na nga country
        $locationLowerCase = strtolower($location);
        
        
        $countriesLowerCase = array_map('strtolower', $_SESSION['countries']);
        
        if (in_array($locationLowerCase, $countriesLowerCase)) {
            $errorMess = "Oops! It seems that $location is already in your travel list. Please enter a different destination.";
        } else {
            array_unshift($_SESSION['countries'], $location); 
            
            $_SESSION['checked'][$location] = isset($_POST['items']) && in_array($location, $_POST['items']);
        }
    } else {
        
        $errorMess = "Please type in your next travelll !";
    }
}

// to remove a country
if (isset($_POST['remove'])) {
    
    $index = $_POST['remove'];
    $removedLocation = $_SESSION['countries'][$index];
    
    unset($_SESSION['countries'][$index]);
    unset($_SESSION['checked'][$removedLocation]);
   
    $_SESSION['countries'] = array_values($_SESSION['countries']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Travel List</title>
<link rel = "stylesheet" type= "text/css" href= "styles.css">
</head>
<body>

<div class="container">
    <h1>Travel List</h1>
    <hr>
    <?php if (empty($_SESSION['countries'])): ?>
        <p>Adventure awaits, add something to your travel list, girl go go!</p>
    <?php else: ?>
        <form method="post"> 
            <destinationList id="travelList">
                <?php foreach ($_SESSION['countries'] as $index => $location): ?>
                    <itemsInList>
                        <input type="checkbox" class="checkbox" id="item<?php echo $index; ?>" name="items[]" value="<?php echo $location; ?>" <?php if (isset($_SESSION['checked'][$location]) && $_SESSION['checked'][$location]) echo 'checked'; ?>>
                        <label for="item<?php echo $index; ?>"><?php echo $location; ?></label>
                        <button type="submit" name="remove" value="<?php echo $index; ?>" class="remove-button">X</button>
                    </itemsInList>
                <?php endforeach; ?>
            </destinationList>
        </form> 
    <?php endif; ?>
    <hr>
    <form method="post">
        <input type="text" name="country" id="destinationInput" placeholder="Country Name" value="<?php echo isset($errorMess) ? htmlspecialchars($_POST['country']) : ''; ?>">
        <button type="submit" name="submit" id="addButton">Add</button>
    </form>
    <?php if (isset($errorMess)): ?>
        <div>
            <p class="error-message"><?php echo $errorMess; ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
