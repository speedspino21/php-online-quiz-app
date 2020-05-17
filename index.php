<?php
$msg = "";
$color = "";
$score = 0;
$count = '';
// form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    // number of question
    $color = '';
    //questionText file reading section
    if ($fh = fopen('questionText.txt', 'r'))
    {
        $array_answer = explode("\n", fread($fh, filesize("questionText.txt")));
        fclose($fh);
    }
    $count = count($array_answer);

    foreach ($_POST as $key => $value)
    {

        foreach ($array_answer as $result)
        {
            $pos = strpos($result, $value);
            if ($pos > 0) $score++;
        }
    }

    //percent score color setting section
    $score = $score * 100 / $count;
    if ($score >= 80)
    {
        $color = "green";
    }
    elseif ($score >= 60 && $score < 80)
    {
        $color = "yellow";
    }
    elseif ($score >= 50 && $score < 60)
    {
        $color = "red";
    }
    else
    {
        $color = "black";
    }

    //color setting part end
    //here are some options about decimal or integer of percents
    // $msg = "<h3 style='color:". $color .";'>Your score is a ". (int) $score ."% on this quiz</h3>";
    // (int) $score code to
    // number_format($score,1)
    $msg = "<h3 >Your score is a <span style='color:" . $color . ";'>" . (int)$score . "%</span> on this quiz</h3>";

}
?>

<!DOCTYPE html>
<html>
   <head>
      <!-- css styling section -->
       <link rel="stylesheet" href="style.css">      
      <!-- css styling end -->
   </head>
   <body>
      <div id="outer">
      <h2>Quiz Online</h2>
      <div id="inner">
      <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ; ?>" name="quiz">
      <!-- answersText reading section -->
      <?php
      if ($fh = fopen('answerText.txt', 'r'))
      {
          $array_answer = explode("\n", fread($fh, filesize("answerText.txt")));
          fclose($fh);
      }

      if ($fh = fopen('questionText.txt', 'r'))
      {
          while (!feof($fh))
          {
              $line = fgets($fh);

              $pos = strpos($line, "|");
              $questionNumber = trim(substr($line, 0, $pos - 1));
              $question = substr($line, $pos + 1);
              $pos = strpos($question, "|");
              $question = substr($question, 0, $pos - 1);
              echo "<b>" . (int)$questionNumber . ".</b> " . $question . "<br/>";

              foreach ($array_answer as $result)
              {
                  if (strpos($result, $questionNumber))
                  {
                      $pos = strpos($result, "|");
                      $answercode = trim(substr($result, 0, $pos - 1));
                      $answer = substr($result, $pos + 1);
                      $pos = strpos($answer, "|");
                      $answer = substr($answer, 0, $pos - 1);

                      echo '<input type="radio" name="' . $questionNumber . '" value="' . $answercode . '">
                  <label for="' . $questionNumber . '">' . $answer . '</label><br>';

                  }
              }

          }

          fclose($fh);
      }
      ?>

      <input type="submit" value="Submit quiz">
      </form>
      </div>
      <?php echo $msg; ?>
      <div align="center"><a href="<?php echo $_SERVER["PHP_SELF"] ; ?>">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;Last Mofified <?php date_default_timezone_set("America/New_York"); echo date("Y/m/d") . " " . date("h:i:sa") ?></div>
      </div>    
    </body>
</html>