<!DOCTYPE html>
<html>
<head>
<title>Grading System</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-600 text-white">
    <div class="border-2 border-white rounded-lg pb-3 w-1/2 bg-black min-h-72 ml-40 mt-14 mb-32 ">
        <h2 class="m-4 font-bold uppercase">Enter Student's Marks</h2>
        <form class="m-5" action="" method="POST">
          <label for="percentage">Percentage: </label><br>
          <input class="text-black pl-1 rounded mt-2" type="number" name="percentage" step="any"><br><br>
          <button class="border-1 border-white bg-blue-700 text-white rounded p-1 font-semibold text-xs mr-4" type="submit" name="button">Display Grade</button>
          <button class="text-white bg-zinc-500 rounded p-1 font-semibold text-xs w-20" type="submit" name="reset">Reset</button>
        </form>
        <?php
        if(isset($_POST['button'])){
          grade();
        }
        if(isset($_POST['reset'])){
          
        }
        
        function grade(){
          $percentage = $_POST['percentage'];
          switch(true){
            case($percentage == ''):
              echo "<h3 class='ml-5'> Percentage shouldn't be empty.</h1>";
              break;
            case($percentage > 100):
            echo "<h3 class='ml-5'> Percentage should be less than 100%</h1>";
            break;
            case($percentage >= 90 && $percentage <= 100):
            $grade = "A";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage >= 80 && $percentage <= 89):
            $grade = "B";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage >= 70 && $percentage <= 79):
            $grade = "C";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage >= 60 && $percentage <= 69):
            $grade = "D";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage >= 50 && $percentage <= 59):
            $grade = "E";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage >= 40 && $percentage <= 49):
            $grade = "F";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage >= 30 && $percentage <= 39):
            $grade = "S";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3><h3 class='ml-5'>Grade: $grade</h3>";
            break;
            case($percentage < 30):
            $grade = "U";
            echo "<h3 class='ml-5'>Percentage: $percentage%</h3> <br> <h3 class='ml-5'>Grade: $grade</h3>";
            break;
          }
        }
        ?>
    </div>
</body>
</html>