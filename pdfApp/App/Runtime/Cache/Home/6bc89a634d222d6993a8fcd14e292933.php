<?php if (!defined('THINK_PATH')) exit();?><html>
  <body>
     <h1 align="center">
            <img src="/pdfApp/Public/images/rosette.gif" alt="" />
            Congratulations!
            <img src="/pdfApp/Public/images/rosette.gif" alt="" /></h1>

	<p>Well done <?php echo ($name); ?>, with a score of <?php echo ($score); ?>%,
            you have passed the exam.</p>

      <p>Please click here to download your certificate as
             a Microsoft Word (RTF) file.</p>
            <form action="rtf.php" method="post">
            <div align="center">
            <input type="image" src="/pdfApp/Public/images/certificate.gif" border="0">
            </div>
            <input type="hidden" name="score" value="<?php echo ($score); ?>"/>
            <input type="hidden" name="name" value="<?php echo ($name); ?>"/>
            </form>

            <p>Please click here to download your certificate as
            a Portable Document Format (PDF) file.</p>
            <form action="pdf.php" method="post">
            <div align="center">
            <input type="image" src="/pdfApp/Public/images/certificate.gif" border="0">
            </div>
            <input type="hidden" name="score" value="<?php echo ($score); ?>"/>
            <input type="hidden" name="name" value="<?php echo ($name); ?>"/>
            </form>

            <p>Please click here to download your certificate as
            a Portable Document Format (PDF) file generated with PDFLib.</p>
            <form action="pdflib" method="post">
            <div align="center">
            <input type="image" src="/pdfApp/Public/images/certificate.gif" border="0">
            </div>
            <input type="hidden" name="score" value="<?php echo ($score); ?>"/>
            <input type="hidden" name="name" value="<?php echo ($name); ?>"/>
            </form>
  </body>
</html>