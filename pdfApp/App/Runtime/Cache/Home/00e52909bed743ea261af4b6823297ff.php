<?php if (!defined('THINK_PATH')) exit();?><html>
  <body>
    <h1><p align="center">
			<img src="/pdfApp/Public/images/rosette.gif" alt="">
          Certification
		  <img src="/pdfApp/Public/images/rosette.gif" alt=""></p></h1>
    <p>You too can earn your highly respected PHP certification
       from the world famous Fictional Institute of PHP Certification.</p>
    <p>Simply answer the questions below:</p>

    <form action="score" method="post">

      <p>Your Name <input type="text" name="name" /></p>

      <p>What does the PHP statement echo do?</p>
      <ol>
        <li><input type="radio" name="q1" value="1" />
            Outputs strings.</li>
        <li><input type="radio" name="q1" value="2" />
            Adds two numbers together.</li>
        <li><input type="radio" name="q1" value="3" />
            Creates a magical elf to finish writing your code.</li>
      </ol>

      <p>What does the PHP function cos() do?</p>
      <ol>
        <li><input type="radio" name="q2" value="1" />
            Calculates a cosine in radians.</li>
        <li><input type="radio" name="q2" value="2" />
            Calculates a tangent in radians. </li>
        <li><input type="radio" name="q2" value="3" />
            It is not a PHP function. It is a lettuce. </li>
      </ol>

      <p>What does the PHP function mail() do?</p>
      <ol>
        <li><input type="radio" name="q3" value="1" />
            Sends a mail message.
        <li><input type="radio" name="q3" value="2" />
            Checks for new mail.
        <li><input type="radio" name="q3" value="3" />
            Toggles PHP between male and female mode.
      </ol>

      <p align="center"><input type="image" src="/pdfApp/Public/images/certify-me.gif" border="0"></p>

    </form>
  </body>
</html>