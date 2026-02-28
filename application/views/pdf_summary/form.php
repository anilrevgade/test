<!DOCTYPE html>
<html>
<head>
	<title>PDF Summary</title>
</head>
<body>
	<h1>Upload PDF and Get Summary</h1>

	<?php if ($error !== ''): ?>
		<p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php endif; ?>

	<?php echo form_open_multipart('pdf-summary/summarize'); ?>
		<input type="file" name="pdf_file" accept="application/pdf" required />
		<button type="submit">Summarize PDF</button>
	</form>

	<?php if ($summary !== ''): ?>
		<h2>Summary</h2>
		<p><?php echo htmlspecialchars($summary, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php endif; ?>

	<?php if ($original_text !== ''): ?>
		<h2>Extracted Text (Preview)</h2>
		<p><?php echo htmlspecialchars(substr($original_text, 0, 1000), ENT_QUOTES, 'UTF-8'); ?>...</p>
	<?php endif; ?>
</body>
</html>
